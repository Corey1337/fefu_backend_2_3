<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings;

class SuggestApp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $max = Settings::first()->max;
        $periodicity = Settings::first()->periodicity;
        if(!$request->session()->get('is_appeal_send'))
        {
            if (!$request->session()->exists('show_count'))
            {
                $request->session()->put('show_count', 0);
                $request->session()->put('page_changed_count', 0);
            }
            if($request->session()->get('show_count') < $max)
            {
                if($request->session()->get('page_changed_count') >= $periodicity)
                {
                    $request->session()->now('modal_show', true);
                    $request->session()->put('page_changed_count', 0);
                    $request->session()->increment('show_count');
                    $request->session()->put('grat_message', true);
                }
                else
                {
                    $request->session()->increment('page_changed_count');
                }
            }
        }
        return $next($request);
    }
}
