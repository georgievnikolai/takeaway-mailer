<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResponseFormat
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
        $response = [
            'success' => true, 
            'errors' => [], 
            'data' => [],
        ];

        try
        {
            $result = $next($request);
            if(!empty($result->exception))
            {
                throw $result->exception;
            }
            $response['data'] = $result->getOriginalContent();
        }
        catch(\Exception $e)
        {
            $response['success'] = false;
            $response['errors'][] = $e->getMessage();
        }

        $response = json_encode($response);
        
        $result->setContent($response);
        
        return $result;
    }
}
