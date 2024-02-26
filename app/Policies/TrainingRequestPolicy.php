<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TrainingRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_training::request');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingRequest  $trainingRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TrainingRequest $trainingRequest)
    {
        return $user->can('view_training::request');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_training::request');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingRequest  $trainingRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TrainingRequest $trainingRequest)
    {
        return $user->can('update_training::request');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingRequest  $trainingRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TrainingRequest $trainingRequest)
    {
        return $user->can('delete_training::request');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_training::request');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingRequest  $trainingRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TrainingRequest $trainingRequest)
    {
        return $user->can('force_delete_training::request');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_training::request');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingRequest  $trainingRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TrainingRequest $trainingRequest)
    {
        return $user->can('restore_training::request');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_training::request');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingRequest  $trainingRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, TrainingRequest $trainingRequest)
    {
        return $user->can('replicate_training::request');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_training::request');
    }

}
