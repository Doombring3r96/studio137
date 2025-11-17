<?php

namespace App\Policies;

use App\Models\PublicationCalendar;
use App\Models\User;

class PublicationCalendarPolicy
{
    public function view(User $user, PublicationCalendar $calendar): bool
    {
        return $user->isCM() && $calendar->creador_id === $user->id;
    }

    public function update(User $user, PublicationCalendar $calendar): bool
    {
        return $user->isCM() && $calendar->creador_id === $user->id && 
               in_array($calendar->estado, ['pendiente', 'en_revision']);
    }

    public function delete(User $user, PublicationCalendar $calendar): bool
    {
        return $user->isCM() && $calendar->creador_id === $user->id && 
               $calendar->estado === 'pendiente';
    }
}