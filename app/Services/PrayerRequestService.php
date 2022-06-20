<?php

namespace App\Services;

use App\Models\PrayerRequest;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class PrayerRequestService
{
    public function Prayer($query)
    {
        return DataTables::of($query)
            ->editColumn('recurring',function($request){
                return $request->recurring == true ? 'Yes' : 'No';
            })
            ->editColumn('created_at',function($request){
                return $request->created_at->format('M d, Y');
            })
            ->editColumn('target_completion',function($request){
                return $request->target_completion !== null ? Carbon::parse($request->target_completion)->format('M d, Y'): "";
            })
            ->editColumn('date_completed',function($request){
                return $request->date_completed !== null ? Carbon::parse($request->date_completed)->format('M d, Y'): "";
            })
            ->editColumn('request', function($request){
                return strlen($request->request) > 20 ?
                    substr($request->request,0,20).' <a href="#"><i class="read-more">Read more..</i></a>'
                    : $request->request;
            })
            ->editColumn('visibility',function($request){
                if($request->visibility === "public")
                {
                    return '<span class="right badge badge-success">'.$request->visibility.'</span>';
                }elseif ($request->visibility === "private")
                {
                    return '<span class="right badge badge-dark">'.$request->visibility.'</span>';
                }

            })
            ->editColumn('status',function($request){
                if($request->status === "waiting")
                {
                    return '<span class="right badge badge-info">'.$request->status.'</span>';
                }elseif ($request->status === "answered")
                {
                    return '<span class="right badge badge-success">'.$request->status.'</span>';
                }

            })
            ->addColumn('requester', function($request){
                return '<a href="#">'.ucwords($request->user->fullname).'</a>';
            })
            ->addColumn('action', function($request){
                $action = '';
                if(auth()->user()->can('view prayer request'))
                {
                    $action .= '<button href="#" class="btn btn-xs btn-success view-prayer-request-btn" id="'.$request->id.'" title="View"><i class="fa fa-eye"></i></button> ';
                }
                if(auth()->user()->can('edit prayer request') && $request->user_id === auth()->user()->id)
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-prayer-request-btn" id="'.$request->id.'" title="Edit"><i class="fa fa-edit"></i></button> ';
                }
                if(auth()->user()->can('delete prayer request') && $request->user_id === auth()->user()->id)
                {
                    $action .= '<button class="btn btn-xs btn-danger delete-prayer-request-btn" id="'.$request->id.'" title="Delete"><i class="fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action','status','visibility','request','requester'])
            ->make(true);
    }
}
