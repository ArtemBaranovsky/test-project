<?php

namespace App\Http\Controllers;

use App\Contracts\PublicationServiceInterface;
use App\Http\Requests\PublicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicationController extends Controller
{

    public function __construct(private PublicationServiceInterface $publicationService)
    {
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $publications = $this->publicationService->getAllPublications();

        return response()->json($publications, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $publication = $this->publicationService->getPublicationById($id);

        if (!$publication) {
            return response()->json(['error' => 'Publication not found'], 404);
        }

        return response()->json($publication, 200);
    }

    /**
     * @throws \Exception
     */
    public function store(PublicationRequest $request): \Illuminate\Http\JsonResponse
    {
        // TODO: move to standalone middleware
        if (!Auth::user()->hasActiveSubscription()) {
            return response()->json(['message' => 'You need an active subscription to publish.'], 403);
        }

        if (Auth::user()->exceedsPublicationLimit()) {
            return response()->json(['message' => 'You have reached the publication limit.'], 403);
        }

        $data = $request->validated();

        $publication = $this->publicationService->createPublication($data);

        return response()->json($publication, 201);
    }

    public function update(PublicationRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $publication = $this->publicationService->updatePublication($id, $data);

        if (!$publication) {
            return response()->json(['error' => 'Publication not found'], 404);
        }

        return response()->json($publication, 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->publicationService->deletePublication($id);

        if (!$result) {
            return response()->json(['error' => 'Publication not found'], 404);
        }

        return response()->json(['message' => 'Publication deleted successfully'], 200);
    }

    public function filterByAuthor(Request $request): \Illuminate\Http\JsonResponse
    {
        $authorId = $request->input('author_id');

        $publications = $this->publicationService->filterByAuthor($authorId);

        return response()->json($publications);
    }
}
