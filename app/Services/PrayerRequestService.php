<?php

namespace App\Services;

use App\Models\PrayerRequest;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class PrayerRequestService
{
    public function personalPrayer($userId)
    {
        return DataTables::of(PrayerRequest::where('user_id','=',$userId)->get())
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
            ->addColumn('action', function($request){
                $action = '';
                if(auth()->user()->can('edit prayer request'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-prayer-request-btn" id="'.$request->id.'" title="Edit"><i class="fa fa-edit"></i></a> ';
                }
                if(auth()->user()->can('delete prayer request'))
                {
                    $action .= '<a class="btn btn-xs btn-danger delete-prayer-request-btn" id="'.$request->id.'" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['action','status','visibility'])
            ->make(true);
    }
}
