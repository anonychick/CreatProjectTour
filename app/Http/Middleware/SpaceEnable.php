<?php


namespace App\Http\Middleware;

use Closure;
use Redirect;

class SpaceEnable
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
        $enable = get_option('space_enable', 'on');

        if($enable !== 'on'){
            $partInfo = $request->getPathInfo();
            if(strpos($partInfo, 'dashboard')){
                return Redirect::to('/dashboard');
            }else{
                return Redirect::to('/');
            }
        }

        return $next($request);
    }
}