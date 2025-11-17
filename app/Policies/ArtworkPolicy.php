<?php

namespace App\Policies;

use App\Models\Artwork;
use App\Models\User;

class ArtworkPolicy
{
    public function view(User $user, Artwork $artwork): bool
    {
        return $user->isCM() && $artwork->autor_id === $user->id;
    }

    public function update(User $user, Artwork $artwork): bool
    {
        return $user->isCM() && $artwork->autor_id === $user->id;
    }

    public function delete(User $user, Artwork $artwork): bool
    {
        return $user->isCM() && $artwork->autor_id === $user->id;
    }
}