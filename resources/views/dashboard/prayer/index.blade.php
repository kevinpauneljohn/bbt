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
{{--                @can('add customer')--}}
                    <button class="btn btn-primary btn-sm add-user-btn" data-toggle="modal">Add</button>
{{--                @endcan--}}
            </div>
            <div class="card-body">
                <table id="prayer-requests" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Facebook</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
