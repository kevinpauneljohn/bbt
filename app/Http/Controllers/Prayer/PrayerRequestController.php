<?php

namespace App\Http\Controllers\Prayer;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Services\PrayerRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrayerRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:add prayer request')->only(['store']);
        $this->middleware('permission:view prayer request')->only(['index','show','personalPrayer','getPersonalPrayer','allPrayerRequest']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.prayer.index');
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
        $validation = Validator::make($request->all(),[
            'request' => 'required|max:1000',
            'visibility' => 'required',
        ]);

        if($validation->passes())
        {
            $prayerRequest = collect(collect($request->all())
                ->merge([
                    'user_id' => auth()->user()->id,
                    'status' => 'waiting',
                    'recurring' => isset($request->recurring),
                    ]))->toArray();
            if(PrayerRequest::create($prayerRequest)){
                return response()->json(['message' => 'Prayer Request Successfully added!', 'success' => true]);
            }
        }
        return response()->json($validation->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return PrayerRequest::find($id);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'request' => 'required|max:1000',
            'visibility' => 'required',
        ]);

        if($validation->passes())
        {
            $prayerRequest = PrayerRequest::find($id);
            $prayerRequest->request = $request->input('request');
            $prayerRequest->visibility = $request->visibility;
            $prayerRequest->target_completion = $request->target_completion;
            $prayerRequest->recurring =  isset($request->recurring);
            if($prayerRequest->isDirty())
            {
                $prayerRequest->save();
                return response()->json(['success' => true, 'message' => 'Prayer request successfully updated!']);
            }
            return response()->json(['success' => false, 'message' => 'No changes occurred']);
        }
        return response()->json($validation->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(PrayerRequest::destroy($id))
        {
            return response()->json(['success' => true, 'message' => 'Prayer request successfully removed!']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred!']);
    }

    public function personalPrayer(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('dashboard.prayer.personalprayer');
    }

    /**
     * get all the prayer request of the specfici user
     * @param PrayerRequestService $prayerRequestService
     * @return mixed
     */
    public function getPersonalPrayer(PrayerRequestService $prayerRequestService): mixed
    {
        return $prayerRequestService->Prayer(PrayerRequest::where('user_id','=',auth()->user()->id)->get());
    }

    public function allPrayerRequest(PrayerRequestService $prayerRequestService)
    {

        $users = collect(User::where('church',auth()->user()->church)->get());
        $prayerRequests = PrayerRequest::whereIn('user_id',$users->pluck('id'))->get();
        return $prayerRequestService->Prayer($prayerRequests);
    }
}
