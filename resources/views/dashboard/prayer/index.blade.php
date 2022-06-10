@extends('adminlte::page')

@section('title', 'Personal Prayer Requests')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">My Personal Prayer Requests</h1>
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
                        <th width="30%">Prayer Request</th>
                        <th>Visibility</th>
                        <th width="10%">Target Date Answer</th>
                        <th width="10%">Date Answered</th>
                        <th width="10%">Status</th>
                        <th>Recurring</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add prayer request')
        <div class="modal fade prayer-modal" id="add-prayer-request">
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
                            <div class="form-group request">
                                <label for="request">Request</label><span class="required">*</span>
                                <textarea name="request" class="form-control" id="request" maxlength="1000"></textarea>
                            </div>
                            <div class="form-group visibility">
                                <label for="visibility">Visibility</label><span class="required">*</span>
                                <select name="visibility" class="form-control" id="visibility">
                                    <option value="private">Private</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
                            <div class="form-group recurring">
                                <input type="checkbox" name="recurring" class="form-control" id="recurring"> Recurring
                            </div>
                            <div class="form-group target_completion">
                                <label for="target_completion">Target Completion</label>
                                <input type="date" name="target_completion" class="form-control" id="target_completion">
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
    <style>
        textarea[name="request"]{
            min-height:150px;
        }
        input[type="date"]{
            width:100%;
        }
        input[name="recurring"]{
            width:auto;
            height:initial;
            display: inline-block;
        }
    </style>
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
                ajax: '/personal-prayer-request',
                columns: [
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

        @can('add prayer request')

        $('#recurring').bind('change', function () {

            if ($(this).is(':checked'))
                $('input[name="target_completion"]').attr('disabled',true);
            else
                $('input[name="target_completion"]').attr('disabled',false);
        });

        $(document).on('click','.add-prayer-btn',function(){

            prayerModal.find('.modal-title').text('Add Prayer Request');
            prayerModal.find('.text-danger').remove();

            prayerModal.find('.form-submit').trigger('reset');
            prayerModal.find('.form-submit').removeAttr('id').attr('id','add-prayer-request-form');
        });

        $(document).on('submit','#add-prayer-request-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/prayer-requests',
                type: 'POST',
                data: data,
                beforeSend: function (){

                },success: function(response){
                    console.log(response);
                    errorDisplay(response)

                    if(response.success === true)
                    {
                        let table = $('#prayer-requests').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                        prayerModal.find('.form-submit').trigger('reset');
                        prayerModal.find('input[name="target_completion"]').attr('disabled',false);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
            clear_errors('request','visibility');
        });
        @endcan

        @can('edit prayer request')
        let requestId;
        $(document).on('click','.edit-prayer-request-btn',function(){
            requestId = this.id;

            prayerModal.find('.modal-title').text('Edit Prayer Request');
            prayerModal.find('.text-danger').remove();

            prayerModal.find('.form-submit').removeAttr('id').attr('id','edit-prayer-request-form');
            $.ajax({
                url: '/prayer-requests/'+requestId,
                type: 'GET',
                beforeSend: function(){

                },success: function(response){

                    if(response.recurring == true)
                    {
                        prayerModal.find('input[name="recurring"]').prop('checked',true);
                        prayerModal.find('input[name="target_completion"]').attr('disabled',true);
                    }else{
                        prayerModal.find('input[name="recurring"]').prop('checked',false);
                        prayerModal.find('input[name="target_completion"]').attr('disabled',false);
                    }
                    prayerModal.find('textarea[name="request"]').text(response.request);
                    prayerModal.find('select[name="visibility"]').val(response.visibility).trigger('change');
                    prayerModal.find('input[name="target_completion"]').val(response.target_completion);
                    prayerModal.modal('toggle');

                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });
        $(document).on('submit','#edit-prayer-request-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/prayer-requests/'+requestId,
                type: 'PUT',
                data: data,
                beforeSend: function(){

                },success: function(response){
                    // console.log(response);
                    if(response.success === true)
                    {
                        let table = $('#prayer-requests').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    }else if(response.success === false)
                    {
                        Toast.fire({
                            type: 'warning',
                            title: response.message
                        });
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        });
        @endcan

        @can('delete prayer request')
            $(document).on('click','.delete-prayer-request-btn',function(){
                requestId = this.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        url: '/prayer-requests/'+requestId,
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response){
                            console.log(response);
                            if(response.success === true)
                            {
                                let table = $('#prayer-requests').DataTable();
                                table.ajax.reload(null, false);
                                Swal.fire(
                                    'Removed!',
                                    response.message,
                                    'success'
                                )
                            }
                        },error: function(xhr, status, error){
                            console.log(xhr);
                        }
                    });

                }
            })
            });
        @endcan

    </script>
@stop
