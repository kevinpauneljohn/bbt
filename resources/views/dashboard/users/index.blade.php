@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add user')
                    <button class="btn btn-primary btn-sm add-user-btn" data-toggle="modal" data-target="#add-user">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="users" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th width="15%">Date Added</th>
                        <th>Church Name</th>
                        <th>Full name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add user')
        <div class="modal fade user-modal" id="add-user">
            <div class="modal-dialog modal-lg">
                <form id="add-user-form" class="form-submit">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add User</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group firstname">
                                        <label for="firstname">First Name</label><span class="required">*</span>
                                        <input type="text" name="firstname" class="form-control" id="firstname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group middlename">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" name="middlename" class="form-control" id="middlename">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group lastname">
                                        <label for="lastname">Last Name</label><span class="required">*</span>
                                        <input type="text" name="lastname" class="form-control" id="lastname">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group email">
                                        <label for="email">Email</label><span class="required">*</span>
                                        <input type="email" name="email" class="form-control" id="email">
                                    </div>
                                </div>
                                <div class="col-lg-6 username-section">
                                    <div class="form-group username">
                                        <label for="username">Username</label><span class="required">*</span>
                                        <input type="text" name="username" class="form-control" id="username">
                                    </div>
                                </div>
                            </div>
                            <div class="row mobile-section">
                                <div class="col-lg-6">
                                    <div class="form-group mobile_number">
                                        <label for="mobile_number">Mobile Number</label><span class="required">*</span>
                                        <input type="text" name="mobile_number" class="form-control" id="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row password-section">
                                <div class="col-lg-6">
                                    <div class="form-group password">
                                        <label for="password">Password</label><span class="required">*</span>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group password_confirmation">
                                        <label for="password_confirmation">Confirm Password</label><span class="required">*</span>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group roles">
                                        <label for="roles">Roles</label><span class="required">*</span>
                                        <select name="roles[]" class="form-control select2" id="roles" multiple="multiple" style="width: 100%;">
                                            @foreach($roles as $role)
                                                <option name="{{$role->name}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group church">
                                        <label for="church">Church</label><span class="required">*</span>
                                        <select name="church" class="form-control " id="church" style="width: 100%;">
                                            <option value=""> -- Select a church-- </option>
                                            @foreach($churches as $church)
                                                <option value="{{$church->id}}">{{$church->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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


            $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/all-users',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'church', name: 'church'},
                    { data: 'fullName', name: 'fullName'},
                    { data: 'username', name: 'username'},
                    { data: 'email', name: 'email'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });


        let popUp = $('.user-modal');

        $(document).on('click','.add-user-btn',function(){
            popUp.find('.password-section').removeAttr('style');
            popUp.find('.form-submit #username').attr('disabled',false);

            popUp.find('.form-submit').trigger('reset');
            popUp.find('.modal-title').text('Add User');
            popUp.find('.form-submit').removeAttr('id').attr('id','add-user-form');
        });

        @can('add user')
        $(document).on('submit','#add-user-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/users',
                type: 'POST',
                data: data,
                beforeSend: function(){
                    popUp.find('.save').attr('disabled',true).text('Saving...');
                },success: function(response){
                    console.log(response)
                    errorDisplay(response);
                    if(response.success === true)
                    {
                        $('#add-user-form').trigger('reset');
                        $('#add-user-form').find('.select2').val('').trigger('change');

                        let table = $('#users').DataTable();
                        table.ajax.reload(null, false);

                        $('#add-user').modal('toggle');
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
            clear_errors('firstname','lastname','email','username','password','roles','mobile_number','church');
        });
        @endcan


        @can('edit user')
        let userId;
        $(document).on('click','.edit-user-btn', function(){
            userId = this.id;

            $.ajax({
                url:'/users/'+userId,
                type: 'GET',
                beforeSend: function(){

                },success: function(response){
                    popUp.find('.password-section').css('display','none');
                    popUp.find('.form-submit #firstname').val(response.user.firstname);
                    popUp.find('.form-submit #middlename').val(response.user.middlename);
                    popUp.find('.form-submit #lastname').val(response.user.lastname);
                    popUp.find('.form-submit #email').val(response.user.email);
                    popUp.find('.form-submit #username').val(response.user.username).attr('disabled',true);
                    popUp.find('.form-submit #mobile_number').val(response.user.mobile_number);

                    popUp.find('#roles').val(response.roles).trigger('change');
                    popUp.find('#church').val(response.user.church).trigger('change');
                },error: function(xhr, status, error){

                }
            });

            popUp.find('.text-danger').remove();
            popUp.find('.modal-title').text('Edit User');
            popUp.find('.form-submit').removeAttr('id').attr('id','edit-user-form');

            popUp.modal('toggle');

        });

        $(document).on('submit','#edit-user-form',function(form){
            form.preventDefault();

            let data = $(this).serializeArray();

            $.ajax({
                url: '/users/'+userId,
                type: 'PUT',
                data: data,
                beforeSend: function(){
                    popUp.find('input, select').attr('disabled',true);
                    popUp.find('.save').attr('disabled',true).text('Saving...');
                },success: function(response){

                    errorDisplay(response);
                    if(response.success === true)
                    {
                        let table = $('#users').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        })
                    }else if(response.success === false){
                        Toast.fire({
                            type: 'warning',
                            title: response.message
                        })
                    }

                    popUp.find('input:not("#username"), select').attr('disabled',false);
                    popUp.find('.save').attr('disabled',false).text('Save');
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
            clear_errors('firstname','lastname','email','username','password','roles','mobile_number','church');
        });
        @endcan


        $(function (){
            $('.select2').select2();
        });
    </script>

@stop
