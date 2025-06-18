<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class RouteTestController extends Controller
{
    public function checkRoutes(Request $request)
    {
        $searchRoute = $request->input('route', 'admin.contact');
        $routes = collect(Route::getRoutes())->map(function($route) use ($searchRoute) {
            $uri = $route->uri();
            $name = $route->getName();

            // Only return routes that match our search term
            if ($name && strpos($name, $searchRoute) !== false) {
                return [
                    'uri' => $uri,
                    'name' => $name,
                    'methods' => implode('|', $route->methods()),
                    'action' => $route->getActionName(),
                ];
            }
            return null;
        })->filter()->values()->toArray();

        return response()->json([
            'total' => count($routes),
            'routes' => $routes
        ]);
    }
}
