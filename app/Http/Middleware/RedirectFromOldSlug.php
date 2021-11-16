<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;

class RedirectFromOldSlug
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
        $url = substr(parse_url($request->url(), PHP_URL_PATH), 6);
        $redirect = Redirect::query()->where('old_slug', $url)->orderByDesc('created_at')->orderByDesc('id')->first();
        // if($redirect === null)
        // {
        //     print("\nnull");
        // }
        //print($redirect);
        $redirTo = null;

        while($redirect !== null)
        {
            $redirTo = $redirect->new_slug;
            $redirect = Redirect::query()
                ->where('old_slug', $redirTo)
                ->where('created_at', '>', $redirect->created_at)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->first();
        }
        if ($redirTo !== null)
        {
            return redirect('news/' . $redirTo);
        }
        
        return $next($request);
    }
}
