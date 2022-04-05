@extends('adminlte::page')

@section('title', 'Churches')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Churches</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Churches</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                @can('add church')
                    <button class="btn btn-primary btn-sm add-church-btn" data-toggle="modal" data-target="#add-church">Add</button>
                @endcan
            </div>
            <div class="card-body">
                <table id="churches" class="table table-hover table-bordered" role="grid">
                    <thead>
                    <tr role="row">
                        <th width="15%">Date Added</th>
                        <th>Church Name</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @can('add prayer request')
        <div class="modal fade church-modal" id="add-church">
            <div class="modal-dialog">
                <form id="add-church-form" class="form-submit">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Church</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group name">
                                <label for="name">Name</label><span class="required">*</span>
                                <input name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group address">
                                <label for="address">Address</label><span class="required">*</span>
                                <textarea name="address" class="form-control" id="address"></textarea>
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


            $('#churches').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/all-churches',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'asc']
            });
        });


        let popUp = $('.church-modal');

        $(document).on('click','.add-church-btn',function(){

            popUp.find('.form-submit').trigger('reset');
            popUp.find('.modal-title').text('Add Church');
            popUp.find('.form-submit').removeAttr('id').attr('id','add-church-form');
        });

        $(document).on('submit','#add-church-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();

            $.ajax({
                url: '/churches',
                type: 'POST',
                data: data,
                beforeSend: function(){
                    popUp.find('.save').attr('disabled',true).text('Saving...');
                },success: function(response){
                    console.log(response)
                    errorDisplay(response);
                    if(response.success === true)
                    {
                        popUp.trigger('reset');

                        let table = $('#churches').DataTable();
                        table.ajax.reload(null, false);

                        $('#add-church').modal('toggle');
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
            clear_errors('name','address');
        });

        let churchId;
        $(document).on('click','.edit-church-btn', function(){
            permissionId = this.id;

            $tr = $(this).closest('tr');

            let data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();

            popUp.find('.text-danger').remove();
            popUp.find('.modal-title').text('Edit Church');
            popUp.find('.form-submit').removeAttr('id').attr('id','edit-church-form');
            popUp.find('.form-submit #name').val(data[1]);
            popUp.find('.form-submit #address').val(data[2]);
            popUp.modal('toggle');

        });
    </script>

@stop
