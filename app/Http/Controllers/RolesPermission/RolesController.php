<?php

namespace App\Http\Controllers\RolesPermission;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view role')->only(['role_lists']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.Roles.index');
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
            'name' => 'required|max:300|unique:roles,name',
        ]);

        if($validation->passes())
        {
           Role::create(['name' => $request->name]);
           return response()->json(['success' => true, 'message' => 'Role successfully added!']);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validation->passes()){
            $role = Role::findById($id);
            $role->name = $request->name;
            if($role->isDirty())
            {
                $role->save();
                return response()->json(['success' => true, 'message' => 'Role successfully updated!']);
            }
            return response()->json(['success' => false, 'message' => 'No changes occurred!']);
        }
        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(Role::destroy($id))
            return response()->json(['success' => true, 'message' => 'Role successfully removed']);
    }

    /**
     * fetch all roles
     * @param RoleService $roleService
     * @return mixed
     */
    public function role_lists(RoleService $roleService)
    {
        return $roleService->role_table(Role::where('name','!=','super admin')->get());
    }
}
