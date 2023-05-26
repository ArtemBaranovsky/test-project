<?php

namespace App\Services;

use App\Contracts\PublicationServiceInterface;
use App\Exceptions\NoActiveSubscriptionException;
use App\Exceptions\PublicationLimitExceededException;
use App\Models\Publication;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;

class PublicationService implements PublicationServiceInterface
{

    public function __construct(private PublicationRepositoryInterface $publicationRepository)
    {
    }

    /**
     * @param $data
     * @return Publication
     * @throws Exception
     */
    public function createPublication(array $data): Publication
    {
        $user = Auth::user();
        $subscription = $user->subscription;

        if ($subscription->isPlanAvailable()) {
            $availablePublications = $subscription->subscriptionPlan->publications_available;
            $publicationsCount = $user->publications()->count();

            if ($publicationsCount < $availablePublications) {
                $publication = new Publication();
                $publication->title = $data['title'] ?? '';
                $publication->content = $data['content'] ?? '';
                $publication->user_id = $data['user_id'] ?? '';

//dd($publication->toArray());
                $this->publicationRepository->save($publication);

                return $publication;
            }
            throw new PublicationLimitExceededException();
        }

        throw new NoActiveSubscriptionException();
    }

    public function getAllPublications(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->publicationRepository->getAll();
    }

    public function getPublicationById(Publication $id)
    {
        return $this->publicationRepository->find($id);
    }

    public function updatePublication(Publication $id, array $data)
    {
        $publication = $this->publicationRepository->find($id);

        if (!$publication) {
            return null;
        }

        $publication->title = $data['title'];
        $publication->content = $data['content'];

        $this->publicationRepository->create($publication);

        return $publication;
    }

    public function deletePublication(Publication $id): bool
    {
        $publication = $this->publicationRepository->find($id);

        if (!$publication) {
            return false;
        }

        $this->publicationRepository->delete($publication);

        return true;
    }

    public function filterByAuthor(string $authorId)
    {
        $publications = Publication::where('user_id', $authorId)->get();

        return $publications;
    }
}
