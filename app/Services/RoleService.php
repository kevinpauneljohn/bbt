<?php

namespace App\Services;

use Yajra\DataTables\DataTables;

class RoleService
{
    public function role_table($query)
    {
        return DataTables::of($query)
            ->rawColumns(['action'])
            ->addColumn('action', function($role){
                $action = '';
                if(auth()->user()->can('edit role'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-role-btn" id="'.$role->id.'" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete role'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-role-btn" id="'.$role->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->make(true);
    }
}
