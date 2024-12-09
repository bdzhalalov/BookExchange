<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;

class BookRequestPolicy
{
    public function update(User $user, Request $request)
    {
        return $user->id === $request->user_id;
    }
}
