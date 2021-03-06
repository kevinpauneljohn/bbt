<?php

namespace App\Http\Controllers\Prayer;

use App\Http\Controllers\Controller;
use App\Models\PrayerList;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Services\PrayerRequestService;
use Carbon\Carbon;
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

    /**
     * get all church member prayer request
     * @param PrayerRequestService $prayerRequestService
     * @return mixed
     */
    public function allPrayerRequest(PrayerRequestService $prayerRequestService): mixed
    {

        $users = collect(User::where('church',auth()->user()->church)->get());
        $prayerRequests = PrayerRequest::whereIn('user_id',$users->pluck('id'))->where('visibility','public')->get();
        return $prayerRequestService->Prayer($prayerRequests);
    }

    /**
     * get the specific prayer request
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function prayer_request_details($id): \Illuminate\Support\Collection
    {
        $prayer = PrayerRequest::find($id);
        return collect($prayer)
            ->merge([
                'fullname' => $prayer->user->fullname,
                'date_requested' => Carbon::parse($prayer->cretaed_at)->format('M d, Y'),
                'expected_date' => Carbon::parse($prayer->target_completion)->format('M d, Y'),
                'recurring_status' => $prayer->recurring == 1 ? 'yes' : 'no',
                'add_to_list' => !($prayer->user_id === auth()->user()->id),
                'existing_from_list' => PrayerList::where('user_id',auth()->user()->id)->where('prayer_request_id',$id)->count()
                ]);
    }

    /**
     * display all the prayer request of a specific user
     * @param $user_id
     * @param PrayerRequestService $prayerRequestService
     * @return mixed
     */
    public function user_prayer_request($user_id, PrayerRequestService $prayerRequestService): mixed
    {
        if(auth()->user()->id === $user_id)
        {
            return $prayerRequestService->Prayer(PrayerRequest::where('user_id',$user_id)->get());
        }else{
            return $prayerRequestService->Prayer(PrayerRequest::where('user_id',$user_id)->where('visibility','public')->get());
        }

    }

    /**
     * mark the prayer request as answered only by the owner of the prayer
     * @param $prayer_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function answered_prayer($prayer_id): \Illuminate\Http\JsonResponse
    {
        $prayer = PrayerRequest::find($prayer_id);
        if(auth()->user()->id === $prayer->user_id){
            $prayer->date_completed = now();
            if($prayer->save())
            {
                return response()->json(['success' => true, 'message' => 'Prayer marked answered!']);
            }
        }
        return response()->json(['success' => false, 'message' => 'An error occurred']);
    }
}
