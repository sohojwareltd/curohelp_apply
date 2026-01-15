<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {
        $user = auth()->user();
        $role = $user?->role?->slug;  

        if ($role === 'admin') {
            return redirect()->to('/admin');
        }

        if ($role === 'client') {
            return redirect()->to('/client');
        }

        if ($role === 'worker') {
            return redirect()->to('/worker');
        }

        return redirect('/');
    }
}
