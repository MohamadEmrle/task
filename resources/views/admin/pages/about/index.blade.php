@extends('admin.layout.main')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    @if (Session::has('store'))
        <div class="row mr-2 ml-2">
            <label class="alert alert-success">{{ Session::get('store') }}</label>
        </div>
    @endif
    <button style="margin-bottom: 10px" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        create
    </button>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Store Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="identities_form" method="">
                        @csrf
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Description</label>
                            <textarea name="description" class="form-control" placeholder="Enter The Identitie Description"></textarea>
                        </div>
                        @error('description')
                            <h4 class="alert alert-danger">{{ $message }}</h4>
                        @enderror

                        <div class="form-group">
                            <button id="identitie_add" class="btn btn-success" class="form-control">save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModale" tabindex="-1" role="dialog" aria-labelledby="editModaleLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form id="Identitie_form_edit" action="" method="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" data-id="aboutId" id="aboutId" name="id" class="form-control about_id">
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Description</label>
                            <textarea name="description" id="Identitie_description" class="form-control"
                                placeholder="Enter The Identitie Description"></textarea>
                        </div>
                        @error('description')
                            <h4 class="alert alert-danger">{{ $message }}</h4>
                        @enderror

                        <div class="form-group">
                            <button id="Identitie_update" class="btn btn-success" class="form-control">update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <table id="identities_table" class="table table-bordered data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#identities_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('about.create') }}",
                dataType: 'json',
                dataSrc: 'data',
                serverSide: true,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        title: 'Action',
                        render: function(data, type, row) {
                            return '<a id="identitie_edit" class="btn btn-info" data-toggle="modal" data-target="#editModale" data-id="' +
                                row.id +
                                '">Edit</a> <a id="identitie_delete" class="btn btn-danger" data-id="' +
                                row.id + '">Delete</a>';

                        }
                    }
                ]
            })
        })
    </script>

    <script>
        $(document).on('click', '#identitie_add', function(e) {
            e.preventDefault();
            var form = document.getElementById('identities_form');
            var formdata = new FormData(form);
            $.ajax({
                type: 'post',
                url: "{{ route('about.store') }}",
                data: formdata,
                processData: false,
                contentType: false,
                cache: false,
                success: (data) => {
                    var oTable = $("#identities_table").DataTable().ajax.reload();
                    oTable.ajax.reload();
                },
            });
        });
    </script>
    <script>
        $(document).on('click', '#identitie_edit', function(e) {
            var aboutId = $(this).data('id');
            $.ajax({
                url: "{{ route('about.index') }}" + "/" + aboutId + "/edit",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $('#editModale').modal('show');
                    $('#Identitie_description').val(data.description);
                    $('#aboutId').val(data.id);
                },
            });
        });
    </script>
    <script>
        $(document).on('click', '#Identitie_update', function(e) {
            e.preventDefault();
            // Assuming you have an element with the class 'about_id'
            var id = $(".about_id").val();
            console.log(id);

            var form = document.getElementById('Identitie_form_edit');
            var formdata = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ url('admin/about') }}/" + id, // Use correct route name
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                cache: false,
                success: (data) => {
                    $("#identities_table").DataTable().ajax.reload();
                },
                fail: (jqXHR, textStatus, errorThrown) => {
                    // Handle errors here
                    console.error(textStatus, errorThrown);
                    alert('An error occurred during the update.');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '#identitie_delete', function(e) {
            var id = $(this).data('id');
            console.log(id)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('admin/about') }}/" + id,
                type: 'DELETE',
                success: function() {
                    $('#identities_table').DataTable().row($(this).closest('tr')).remove().draw();
                },
                error: function(xhr, status, error) {
                    alert('Error deleting about. ' + xhr.responseText);
                }
                });
            });
    </script>
@endsection
@endsection
