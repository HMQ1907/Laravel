<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    
        Auth::viaRemember();
    
        Auth::viaRequest('session', function ($request, $callback) {
            if ($request->session()->has('user_id')) {
                return User::find($request->session()->get('user_id'));
            }
        });
    }
    
    
}
