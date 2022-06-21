<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class MembersOnly
{
    /**
     * viewable for members only
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\never
     */
    public function handle(Request $request, Closure $next)
    {
        $member = User::find($request->member);

        if(auth()->user()->church !== $member->church)
        {
            return abort(404);
        }
        return $next($request);
    }
}
