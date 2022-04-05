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
                @can('add role')
                    <button class="btn btn-primary btn-sm add-role-btn" data-toggle="modal" data-target="#add-role">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="roles" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Role</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade role-modal" id="add-role">
        <div class="modal-dialog">
            <form id="add-role-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group name">
                            <label for="name">Name</label>
                            <input name="name" class="form-control" id="name">
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
            $('#roles').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/all-roles',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });

        let addRoleForm = $('#add-role-form');
        $(document).on('click','.add-role-btn',function(){
            $('.role-modal').find('.modal-title').text('Add Role');
            $('.role-modal').find('form').attr('id','add-role-form');
            addRoleForm.trigger('reset');
        });
        $(document).on('submit','#add-role-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '{{route('roles.store')}}',
                type: 'POST',
                data: data,
                beforeSend: function(){
                    addRoleForm.find('.save').attr('disabled',true).text('Saving...');
                },success: function(response){
                    console.log(response);

                    errorDisplay(response);
                    if(response.success === true)
                    {
                        addRoleForm.trigger('reset');

                        let table = $('#roles').DataTable();
                        table.ajax.reload(null, false);

                        $('#add-role').modal('toggle');
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    }
                    addRoleForm.find('.save').attr('disabled',false).text('Save');

                },error: function(xhr, status, error){
                    console.log(xhr);
                    addRoleForm.find('.save').attr('disabled',false).text('Save');
                }
            });
            clear_errors('name');
        });


        let id;
        $(document).on('click','.edit-role-btn',function(){
            $('.role-modal').modal('toggle');
            $('.role-modal').find('.modal-title').text('Edit Role');
            $('.role-modal').find('form').attr('id','edit-role-form');
            $('.text-danger').remove();

            id = this.id;

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();

            $('.role-modal').find('#name').val(data[0]);
            console.log(data);
        });

        $(document).on('submit','#edit-role-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/roles/'+id,
                type: 'PUT',
                data: data,
                beforeSend: function(){
                    $('#edit-role-form').find('.save').attr('disabled',true).text('Saving...');
                },success: function(response){
                    console.log(response);

                    errorDisplay(response);
                    if(response.success === true)
                    {
                        addRoleForm.trigger('reset');
                        let table = $('#roles').DataTable();
                        table.ajax.reload(null, false);

                        $('#add-role').modal('toggle');
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
                    $('#edit-role-form').find('.save').attr('disabled',false).text('Save');

                },error: function(xhr, status, error){
                    console.log(xhr);
                    $('#edit-role-form').find('.save').attr('disabled',false).text('Save');
                }
            });
            clear_errors('name');
        });

        $(document).on('click','.delete-role-btn',function(){
            let id = this.id;

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
                        url: '/roles/'+id,
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response){

                            if(response.success === true)
                            {
                                let table = $('#roles').DataTable();
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
    </script>
@stop
