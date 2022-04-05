<?php

namespace App\Http\Controllers\Church;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Services\ChurchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChurchController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.churches.index');
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
            'name' => 'required|unique:churches,name',
            'address' => 'required|max:1000'
        ]);

        if($validation->passes())
        {
            $church = new Church();
            $church->name = $request->name;
            $church->address = $request->address;
            if($church->save()){
                return response()->json(['success' => true, 'message' => 'New church successfully added!']);
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function allChurches(ChurchService $churchService)
    {
        return $churchService->churches();
    }
}
