<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('members_only')->only(['member_profile']);
    }
    public function member_profile($member)
    {
        $user = User::find($member);
        return view('dashboard.members.profile',compact('user'));
    }

    public function my_profile()
    {
        return $this->member_profile(auth()->user()->id);
    }
}
