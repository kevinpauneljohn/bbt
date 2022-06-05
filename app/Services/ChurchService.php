<?php

namespace App\Services;

use App\Models\Church;
use App\Models\Member;
use Yajra\DataTables\Facades\DataTables;

class ChurchService
{
    public function churches()
    {
        return DataTables::of(Church::all())
            ->editColumn('created_at', function($church){
                return $church->created_at->format('M d, Y');
            })
            ->addColumn('action', function($church){
                $action = '';
                if(auth()->user()->can('edit church'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-church-btn" id="'.$church->id.'" data-toggle="modal" data-target="#edit-church-modal" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete church'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-church-btn" id="'.$church->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function church_member($userId): mixed
    {
        return collect(Member::where('user_id',$userId)->first())->toArray();
    }
}
