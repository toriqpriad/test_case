<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Library\JWT_Library;

class LoggedUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $read = $request->post('token');
        if ($read) {
            $jwt    = new JWT_Library();
            $decode = $jwt->decode_token($read);
            if ($decode['status'] != 1)
                throw new \Exception('invalid token');

            $userdata                  = $decode['data'];
            return $next($request);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => 'invalid token',
            ], 401);
        }
    }
}
