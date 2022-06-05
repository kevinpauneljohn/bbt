<?php

namespace App\Services;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserService
{

    public function users()
    {
        return DataTables::of(User::all())
            ->editColumn('created_at',function($user){
                return $user->created_at->format('M d, Y');
            })
            ->addColumn('church',function($user){
                return '';
            })
            ->addColumn('fullName',function($user){
                return $user->full_name;
            })
            ->addColumn('action', function($user){
                $action = '';
                if(auth()->user()->can('edit user'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-permission-btn" id="'.$user->id.'" data-toggle="modal" data-target="#edit-permission-modal" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete user'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-permission-btn" id="'.$user->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
