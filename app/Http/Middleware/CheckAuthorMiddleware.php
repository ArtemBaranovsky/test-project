<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class CheckAuthorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $publication = $request->route('publication');
        $user = $request->user();

        if ($publication->user_id !== $user->id) {
            return response()->json(['error' => 'You are not authorized to modify this publication.'], 403);
        }

        return $next($request);
    }
}
