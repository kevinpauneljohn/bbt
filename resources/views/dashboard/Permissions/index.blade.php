@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Permissions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active">Permissions</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add permission')
                    <button class="btn btn-primary btn-sm add-permission-btn" data-toggle="modal" data-target="#add-permission">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="permissions" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Permissions</th>
                        <th>Roles</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add permission')
        <div class="modal fade permission-modal" id="add-permission">
            <div class="modal-dialog">
                <form id="add-permission-form" class="form-submit">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group permission">
                                <label for="permission">Permission</label><span class="required">*</span>
                                <input name="permission" class="form-control" id="permission">
                            </div>
                            <div class="form-group roles">
                                <label for="roles">Roles</label> (Optional)
                                <select name="roles[]" class="form-control select2" id="roles" multiple="multiple" style="width: 100%;">
                                    @foreach($roles as $role)
                                        <option name="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
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
@stop

@section('js')
    <script src="{{asset('js/errorDisplay.js')}}"></script>
    <script src="{{asset('js/errorChecker.js')}}"></script>
    <script>
        let Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(function() {


            $('#permissions').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/all-permissions',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'roles', name: 'roles'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });

        let popUp = $('.permission-modal');
        $(document).on('click','.add-permission-btn',function(){
            popUp.find('.form-submit').trigger('reset');
            popUp.find('.select2').val('').trigger('change');
            popUp.find('.modal-title').text('Add New Permission');
            popUp.find('.form-submit').removeAttr('id').attr('id','add-permission-form');
        });
        $(document).on('submit','#add-permission-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '{{route('permissions.store')}}',
                type: 'POST',
                data: data,
                beforeSend: function(){
                    popUp.find('.save').attr('disabled',true).text('Saving...');
                },success: function(response){

                    errorDisplay(response);
                    if(response.success === true)
                    {
                        popUp.trigger('reset');

                        let table = $('#permissions').DataTable();
                        table.ajax.reload(null, false);

                        $('#add-permission').modal('toggle');
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    }
                    popUp.find('.save').attr('disabled',false).text('Save');

                },error: function(xhr, status, error){
                    console.log(xhr);
                    popUp.find('.save').attr('disabled',false).text('Save');
                }
            });
            clear_errors('permission');
        });


        // edit permission //
        let permissionId;
        $(document).on('click','.edit-permission-btn', function(){
            permissionId = this.id;

            $tr = $(this).closest('tr');

            let data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();

            popUp.find('.select2').val('').trigger('change');

            popUp.find('.text-danger').remove();
            popUp.find('.modal-title').text('Edit Permission');
            popUp.find('.form-submit').removeAttr('id').attr('id','edit-permission-form');
            popUp.find('.form-submit #permission').val(data[0]);
            popUp.modal('toggle');

            $.ajax({
                'url' : '/permissions/'+permissionId,
                'type' : 'GET',
                beforeSend: function(){
                    popUp.find('.form-submit input, .form-submit select').attr('disabled',true);
                },success: function(response){
                    console.log(response);

                    popUp.find('.select2').val(response).trigger('change');

                    popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
                },error: function(xhr, status, error){
                    console.log(xhr);
                    popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
                }
            });
        });

        $(document).on('submit','#edit-permission-form',function(f){
            f.preventDefault();

            let data = $(this).serializeArray();

            $.ajax({
                'url' : '/permissions/'+permissionId,
                'type': 'PUT',
                'data' : data,
                beforeSend: function(){
                    popUp.find('.form-submit input, .form-submit select').attr('disabled',true);
                },success: function (response){
                    console.log(response);
                    if(response.success === true)
                    {
                        let table = $('#permissions').DataTable();
                        table.ajax.reload(null, false);

                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: 'Permission has been updated!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }else{
                        Toast.fire({
                            type: 'warning',
                            title: response.message
                        });
                    }

                    errorDisplay(response);

                    popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
                },error: function (xhr, status, error){
                    console.log(xhr);
                    popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
                }
            });
            clear_errors('permission')
        })
        // end edit permission //

        // delete permission //
        $(document).on('click','.delete-permission-btn',function(){
            permissionId = this.id;

            $tr = $(this).closest('tr');

            let data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();

            Swal.fire({
                title: 'Remove permission?',
                text: data[0],
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.value === true) {

                    $.ajax({
                        'url' : '/permissions/'+permissionId,
                        'type' : 'DELETE',
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        beforeSend: function(){

                        },success: function(response){

                            if(response.success === true)
                            {
                                let table = $('#permissions').DataTable();
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
        // end delete permission //
    </script>
    <script>
        $(function (){
            $('.select2').select2();
        });
    </script>
@stop
