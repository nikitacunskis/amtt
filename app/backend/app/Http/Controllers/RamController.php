<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Services\RickAndMortyClient;

class RamController extends Controller
{
    protected RickAndMortyClient $client;

    public function __construct(RickAndMortyClient $client)
    {
        $this->client = $client;
    }

    public function list(Request $request, int $page = 1): JsonResponse
    {
        try {
            if ($page < 1) {
                return response()->json(['error' => 'Page must be 1 or greater.'], 422);
            }

            $data = $this->client->list($page);

            if (empty($data['results'] ?? [])) {
                return response()->json(['error' => 'Page not found or no results.'], 404);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data.'], 502);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $character = $this->client->show($id);

            if (empty($character)) {
                return response()->json(['error' => 'Character not found.'], 404);
            }

            return response()->json($character);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch character.'], 502);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string',
                'status' => 'nullable|string|in:Alive,Dead,unknown',
            ]);

            \Log::info('Validated search input:', $validated);

            if (empty($validated['name']) && empty($validated['status'])) {
                return response()->json(['error' => 'At least one of name or status is required.'], 422);
            }

            $results = $this->client->search($validated);
            $characterList = [];
            
            foreach ($results as $characterData) {
                $characterList[] = new \App\Models\Character($this->client, $characterData);
            }

            if (empty($results)) {
                return response()->json(['error' => 'No characters found.'], 404);
            }

            return response()->json($results);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to search characters.'], 502);
        }
    }
}
