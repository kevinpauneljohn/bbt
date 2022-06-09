<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Member;
use App\Models\User;
use App\Services\RolesUpdateChecker;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user')->only(['index','show','all_users']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('name','!=','super admin')->get();
        $churches = Church::all();
        return view('dashboard.users.index', compact('roles','churches'));
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'mobile_number' => 'required|unique:users,mobile_number',
            'password' => 'required|confirmed',
            'roles' => 'required',
            'church' => 'required'
        ]);

        if($validation->passes())
        {
            User::create($request->except(['roles']))->assignRole($request->roles);
            return response()->json(['success' => true, 'message' => 'User successfully added!']);
        }
        return response()->json($validation->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ]);
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
    public function update(Request $request, $id, RolesUpdateChecker $rolesUpdateChecker)
    {
        $validation = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
            'roles' => 'required',
            'church' => 'required'
        ]);

        if($validation->passes())
        {
            $userChange = false;
            $user = User::find($id);
            $user->firstname = $request->firstname;
            $user->middlename = $request->middlename;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->mobile_number = $request->mobile_number;

            if($user->isDirty())
            {
                $userChange = true;
                $user->save();
            }
            $roles = $user->getRoleNames();
            $change = $rolesUpdateChecker->rolesUpdateChecker($request->roles, $roles);
            if($userChange === true || $change > 0){
                foreach ($roles as $role){

                    $user->removeRole($role);
                }
                //assign to user the newly selected roles
                $user->assignRole($request->roles);
                return response()->json(['success' => true, 'message' => 'User successfully updated', 'change' => $change]);
            }
            return response()->json(['success' => false, 'message' => 'no changes occurred', 'change' => $change]);
        }
        return response()->json($validation->errors());
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

    /**
     * @param UserService $userService
     * @return mixed
     */
    public function all_users(UserService $userService): mixed
    {
        return $userService->users();
    }
}
