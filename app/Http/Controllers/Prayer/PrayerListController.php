<?php

namespace App\Http\Controllers\Prayer;

use App\Http\Controllers\Controller;
use App\Models\PrayerList;
use App\Models\PrayerRequest;
use App\Services\PrayerRequestService;
use Illuminate\Http\Request;

class PrayerListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.prayer.prayerlist');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if(PrayerList::create([
            'user_id' => auth()->user()->id,
            'prayer_request_id' => $request->id
        ]))
        {
            return response()->json(['success' => true, 'message' => 'Prayer request successfully added to list']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(PrayerList::where('prayer_request_id',$id)->delete()){
            return response()->json(['success' => true, 'message' => 'Prayer successfully removed']);
        }else{
            return response()->json(['success' => false, 'message' => 'An error occurred']);
        }

    }

    public function my_prayer_lists(PrayerRequestService $prayerRequestService)
    {
        $lists = collect(auth()->user()->prayer_lists)->pluck('prayer_request_id');
        return $prayerRequestService->Prayer(PrayerRequest::whereIn('id',$lists)->where('visibility','public')->get());
    }
}
