<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
        if ($request->is('api/*')) {
            $errors[] = ['code' => 'auth-001', 'message' => 'Unauthorized.'];
            abort(response()->json([
                'errors' => $errors
            ], 401));
        } else if (!$request->expectsJson()) {
            return route('login');
        }
    }
    
//  // Add new method 
// protected function unauthenticated($request, array $guards)
// {
//     abort(response()->json(
//         [
//             'error' => 'UnAuthenticated',
//         ], 401));
// }

}
