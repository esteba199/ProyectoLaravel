<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class CitaPolicy
{
    /**
     * Determinar si el usuario puede ver cualquier modelo.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determinar si el usuario puede ver el modelo.
     */
    public function view(User $user, Cita $cita): bool
    {
        return $cita->user_id === $user->id;
    }

    /**
     * Determinar si el usuario puede crear modelos.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determinar si el usuario puede actualizar el modelo.
     */
    public function update(User $user, Cita $cita): bool
    {
        return $cita->user_id === $user->id;
    }

    /**
     * Determinar si el usuario puede eliminar el modelo.
     */
    public function delete(User $user, Cita $cita): bool
    {
        return $cita->user_id === $user->id;
    }

    /**
     * Determinar si el usuario puede restaurar el modelo.
     */
    public function restore(User $user, Cita $cita): bool
    {
        return $cita->user_id === $user->id;
    }

    /**
     * Determinar si el usuario puede eliminar permanentemente el modelo.
     */
    public function forceDelete(User $user, Cita $cita): bool
    {
        return $cita->user_id === $user->id;
    }
}