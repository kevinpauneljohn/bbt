<?php

namespace App\Services;

use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserService
{

    public function users()
    {
        return DataTables::of(User::whereHas("roles", function($q){ $q->where("name","!=", "super admin"); })->get())
            ->editColumn('created_at',function($user){
                return $user->created_at->format('M d, Y');
            })
            ->addColumn('church',function($user){
                return $user->churches !== null ? $user->churches->name : '';
            })
            ->addColumn('fullName',function($user){
                return $user->full_name;
            })
            ->addColumn('action', function($user){
                $action = '';
                if(auth()->user()->can('edit user'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-user-btn" id="'.$user->id.'" data-toggle="modal" data-target="#edit-user-modal" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete user'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-user-btn" id="'.$user->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
