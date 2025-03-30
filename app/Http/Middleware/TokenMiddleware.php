<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        $http = new Client();


        $response = $http->request('POST',
        'http://localhost:8000/api/auth/token',
        [
            'headers' => [
                'Authorization' => 'Bearer', $token,
                'Accect' => 'application/json',
            ]
        ] 
            );
            switch ($response->getStatusCode()) {
                case 200:
                case 201:
                    $resposne = json_decode($response->getBody(), true);
                    break;    

                default:
                    abort(401, 'Unauthorized');    

            }
$user = new User();

collect($response)->each(function($data, $key) use ($user){
    $user->$key = $data;

});

        return $next($request);
    }
}
