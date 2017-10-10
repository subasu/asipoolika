<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //if we have a user , if a user wants to access to the route when he's not logged in if stops him/her
        //if you are not logged in
        if ($request->user() === null) {
            //UnAuthorized
//            return response("Insufficient permissions", 401);
            return redirect('401');
        }
        //route() is the current route that we are trying to access in
        //it gets the action of the route and the actions is the [] array in web.php
        $actions = $request->route()->getAction();
        //if action do'es have these role key because we have routes that does'nt need any protection
        //it means that we have some routes that does'nt have any roles key so they are accessable for everyone
                                            //but if it has we any roles we store them in $roles otherwise the varibale is null
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        //access the user in the request  and that's the reason that we checked if the user is null upper codes
        //use hasAnyRole method that we defined in User.php and pass the roles that we stored in $roles
        //!roles : or if roles is not set allow the user to proceed and we do this by calling return $next ...
        if ($request->user()->hasAnyRole($roles) || !$roles) {
            //it means every things is fine you may go on
            return $next($request);
        }
//        return response("Insufficient permissions", 401);
        return redirect('401');
    }
}
