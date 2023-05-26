<?php

namespace App\Repositories;

use App\Models\Publication;
use App\Repositories\Contracts\PublicationRepositoryInterface;

class PublicationRepository extends BaseRepository implements PublicationRepositoryInterface
{
    public function save(Publication $publication): void
    {
        $publication->save();
    }
}
