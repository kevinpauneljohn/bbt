<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $username;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect(route('dashboard'));
    }

    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'login' => 'required',
            'password' => 'required'
        ]);

        if($validation->passes())
        {
            if($this->findUsername() === 'email')
            {
                if(Auth::attempt(['email' => $request->login,'password' => $request->password])){
                    $request->session()->regenerate();
                    return redirect(route('home'));
                }
                return back()->withErrors(['success' => 'test']);
            }else if($this->findUsername() === 'username'){
                if(Auth::attempt(['username' => $request->login,'password' => $request->password])){
                    $request->session()->regenerate();
                    return redirect(route('home'));
                }
                return back()->withErrors(['success' => 'test']);
            }
        }


        return back()->withErrors($validation->errors());
    }

    /**
     * @return string
     */
    public function findUsername(): string
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

}
