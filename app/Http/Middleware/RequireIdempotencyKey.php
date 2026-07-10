<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequireIdempotencyKey
{
    public function handle(Request $request, Closure $next)
    {
        $isWrite = in_array($request->getMethod(), ['POST','PUT','PATCH','DELETE']);
        if ($isWrite && !$request->headers->has('Idempotency-Key')) {
            return response()->json([
                'error' => [
                    'code' => 'missing_idempotency_key',
                    'message' => 'Idempotency-Key header is required for write operations.'
                ]
            ], 400);
        }

        if ($isWrite) {
            $key = (string) $request->header('Idempotency-Key');
            $endpoint = $request->getMethod() . ' ' . $request->path();
            $existing = DB::table('idempotency_keys')
                ->where('endpoint', $endpoint)
                ->where('idempotency_key', $key)
                ->first();
            if ($existing) {
                return response($existing->response_body, (int) $existing->status_code)
                    ->header('Content-Type', 'application/json');
            }
        }

        $response = $next($request);

        if ($isWrite) {
            try {
                DB::table('idempotency_keys')->insert([
                    'endpoint' => $request->getMethod() . ' ' . $request->path(),
                    'idempotency_key' => (string) $request->header('Idempotency-Key'),
                    'status_code' => $response->getStatusCode(),
                    'response_body' => $response->getContent(),
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // ignore duplicate insertions or storage errors
            }
        }

        return $response;
    }
}
