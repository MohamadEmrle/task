<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class LeadController extends Controller
{
    private $webhook = "https://b24-8obbje.bitrix24.com.tr/rest/1/katgpontpqne39dg/";

    public function index(Request $request)
    {
        $allLeads = [];
        $start = 0;

        // Pagination loop
        do {
            $response = Http::get($this->webhook . 'crm.lead.list', [
                'start' => $start,
                'select' => ['ID', 'TITLE', 'NAME', 'LAST_NAME', 'STATUS_ID', 'OPPORTUNITY', 'CURRENCY_ID', 'DATE_CREATE']
            ]);

            $data = $response->json();

            if (isset($data['result'])) {
                $allLeads = array_merge($allLeads, $data['result']);
            }

            $start = $data['next'] ?? null;
        } while ($start !== null);

        // Apply filters if any
        if ($request->has('name')) {
            $nameFilter = strtolower($request->input('name'));
            $allLeads = array_filter($allLeads, function ($lead) use ($nameFilter) {
                return str_contains(strtolower($lead['TITLE']), $nameFilter)
                    || str_contains(strtolower($lead['NAME']), $nameFilter)
                    || str_contains(strtolower($lead['LAST_NAME']), $nameFilter);
            });
        }

        if ($request->has('total_from')) {
            $totalFrom = floatval($request->input('total_from'));
            $allLeads = array_filter($allLeads, fn($lead) => floatval($lead['OPPORTUNITY']) >= $totalFrom);
        }

        if ($request->has('total_to')) {
            $totalTo = floatval($request->input('total_to'));
            $allLeads = array_filter($allLeads, fn($lead) => floatval($lead['OPPORTUNITY']) <= $totalTo);
        }

        // Fetch statuses
        $statusResponse = Http::get($this->webhook . 'crm.status.list', [
            'filter' => ['ENTITY_ID' => 'STATUS']
        ]);

        $statuses = $statusResponse->json()['result'] ?? [];

        // Map status names
        $statusMap = [];
        foreach ($statuses as $status) {
            $statusMap[$status['STATUS_ID']] = [
                'name' => $status['NAME'],
                'sort' => $status['SORT']
            ];
        }

        // Compute statistics
        $statistics = [
            'total_leads' => count($allLeads),
            'total_opportunity' => array_sum(array_map(fn($lead) => floatval($lead['OPPORTUNITY']), $allLeads)),
            'by_status' => []
        ];

        foreach ($allLeads as $lead) {
            $statusId = $lead['STATUS_ID'];
            if (!isset($statistics['by_status'][$statusId])) {
                $statistics['by_status'][$statusId] = [
                    'count' => 0,
                    'total_opportunity' => 0
                ];
            }
            $statistics['by_status'][$statusId]['count']++;
            $statistics['by_status'][$statusId]['total_opportunity'] += floatval($lead['OPPORTUNITY']);
        }

        // Format leads for frontend
        $formattedLeads = array_map(function ($lead) use ($statusMap) {
            return [
                'id' => $lead['ID'],
                'title' => $lead['TITLE'],
                'status_id' => $lead['STATUS_ID'],
                'status_name' => $statusMap[$lead['STATUS_ID']]['name'] ?? $lead['STATUS_ID'],
                'opportunity' => floatval($lead['OPPORTUNITY']),
                'currency' => $lead['CURRENCY_ID'],
                'date_create' => $lead['DATE_CREATE'],
            ];
        }, $allLeads);

        return response()->json([
            'leads' => array_values($formattedLeads),
            'statistics' => $statistics,
            'statuses' => array_values($statusMap)
        ]);
    }
}
