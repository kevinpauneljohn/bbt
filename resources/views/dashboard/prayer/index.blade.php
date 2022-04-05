@extends('adminlte::page')

@section('title', 'Prayer Requests')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Prayer Requests</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Prayer Requests</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add prayer request')
                    <button class="btn btn-primary btn-sm add-prayer-btn" data-toggle="modal" data-target="#add-prayer-request">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="prayer-requests" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th width="15%">Date Requested</th>
                        <th>Full Name</th>
                        <th>Prayer Request</th>
                        <th width="10%">Category</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add prayer request')
        <div class="modal fade permission-modal" id="add-prayer-request">
            <div class="modal-dialog">
                <form id="add-prayer-request-form" class="form-submit">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Prayer Request</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group permission">
                                <label for="permission">Permission</label><span class="required">*</span>
                                <input name="permission" class="form-control" id="permission">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save">Save</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </form>
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    @endcan
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
