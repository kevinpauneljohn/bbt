@extends('adminlte::page')

@section('title', 'Member Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Member Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Member Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')


@stop
@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{asset('js/errorDisplay.js')}}"></script>
    <script src="{{asset('js/errorChecker.js')}}"></script>
    <script>
        let prayerModal = $('.prayer-modal');
        let Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(function() {
            $('#prayer-requests').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/all-prayer-request',
                columns: [
                    { data: 'requester', name: 'requester'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'request', name: 'request'},
                    { data: 'visibility', name: 'visibility'},
                    { data: 'target_completion', name: 'target_completion'},
                    { data: 'date_completed', name: 'date_completed'},
                    { data: 'status', name: 'status'},
                    { data: 'recurring', name: 'recurring'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });

    </script>
@stop
