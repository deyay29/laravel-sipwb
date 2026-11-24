<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TehAiChatService;
use App\Services\TehAiContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TehAiController extends Controller
{
    /**
     * Endpoint konteks real-time untuk asisten eksternal maupun widget lokal.
     */
    public function context(Request $request, TehAiContextService $service): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['sometimes', 'nullable', 'string', 'max:255'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:15'],
        ]);

        $context = $service->contextFor(
            $validated['q'] ?? null,
            isset($validated['limit']) ? (int) $validated['limit'] : null
        );

        return response()->json(array_merge(['status' => 'success'], $context));
    }

    /**
     * Topik yang tersedia, dipakai widget untuk saran awal.
     */
    public function topics(TehAiContextService $service): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'kategori' => $service->categories(),
            'topics' => $service->suggestions(),
        ]);
    }

    /**
     * Endpoint chat Teh AI: konteks database -> prompt sistem -> jawaban.
     */
    public function chat(Request $request, TehAiChatService $service): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        return response()->json(array_merge(
            ['status' => 'success'],
            $service->answer($validated['message'])
        ));
    }
}
