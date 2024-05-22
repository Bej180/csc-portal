<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        
        $scan = array_search('mod', $role);
        if ($scan !== false) {
            unset($role[$scan]);
            $role = array_merge($role, ['admin','staff']);
        }
        $userRole = $role[0];
        $attrs = array_slice($role, 1);
        $matched = true;
        
        if (auth()->check() && $userRole == auth()->user()->role) {
            $user = auth()->user();
            $currentRole = $user->role;
            foreach($attrs as $attr) {
                list($item, $value) = explode(':', $attr.':');
                if ($user?->$currentRole?->$item != $value) {
                    $matched = false;
                    break;
                }
    
            }

            if ($matched ) {
                return $next($request);
            }
        }

        // dd($matched);
        
        // if (auth()->check() && in_array(auth()->user()->role, $role)) {
        //     return $next($request);
        // }

        session()->flash('error', 'You are forbidden to access the page you are trying to access');
            // return redirect()->back();
        return redirect('/home');
        
        abort(403, 'Unauthorized');
    }
}
