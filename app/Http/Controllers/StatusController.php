<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class StatusController extends Controller
{
    private $webhook = "https://b24-8obbje.bitrix24.com.tr/rest/1/katgpontpqne39dg/";

    public function index()
    {
        $response = Http::get($this->webhook . 'crm.status.list', [
            'filter' => ['ENTITY_ID' => 'STATUS']
        ]);

        $statuses = $response->json()['result'] ?? [];

        return response()->json($statuses);
    }
}
