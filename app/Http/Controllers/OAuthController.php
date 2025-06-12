<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirect(string $provider) {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider) {
        $provider_user = Socialite::driver($provider)->user();
        DB::transaction(function() use ($provider_user) {
            $user = User::updateOrCreate([
                "email" => $provider_user->email
            ], [
                "name" => $provider_user->name,
                "avatar" => $provider_user->getAvatar()
            ]);

            $user->social()->create([
                "provider_id" => $provider_user->id,
                "provider_token" => $provider_user->token,
                "provider_refresh_token" => $provider_user->refreshToken
            ]);
            
            Auth::login($user);
        });


        return redirect(route("users.list"));
    }
}
