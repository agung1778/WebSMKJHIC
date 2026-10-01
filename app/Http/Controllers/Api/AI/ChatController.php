<?php

namespace App\Http\Controllers\Api\AI;

use App\AI\Core\AIEngine;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct(protected AIEngine $engine)
    {
    }

    /**
     * Endpoint chat AI lokal.
     *
     * @bodyParam message string required Pertanyaan pengguna
     * @bodyParam session_id string optional ID sesi percakapan
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        $message = (string) $validated['message'];
        $sessionId = isset($validated['session_id']) && $validated['session_id'] !== ''
            ? (string) $validated['session_id']
            : 'api_' . Str::random(32);

        $response = $this->engine->chat($message, $sessionId, [
            'source' => 'api',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($response);
    }
}