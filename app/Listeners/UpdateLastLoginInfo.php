<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Don't forget to import Auth
use Illuminate\Support\Facades\Log;  // Don't forget to import Log
use Illuminate\Support\Facades\Session; // Needed for flashing messages

class UpdateLastLoginInfo
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Check if the user is active
        if (!$user->is_active) {
            // Log this security event
            Log::warning('Login attempt by an inactive user.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => request()->ip(), // Get the IP address
            ]);

            // Logout the user immediately. Since the Login event fires *after* authentication,
            // we must explicitly log them out.
            Auth::logout();

            // Invalidate the session and regenerate the CSRF token for security
            Session::invalidate();
            Session::regenerateToken();

            // Flash a message to the session so the login page can display it.
            // This message will be available once on the next request.
            Session::flash('error', 'Your account is currently inactive. Please contact the administrator.');

            return; // Stop further execution of this listener for inactive users
        }

        // If the user is active, proceed with updating login info (your existing logic)
        $user->last_login_at = Carbon::now();
        $user->login_count = ($user->login_count ?? 0) + 1;
        $user->save();

        Log::info('User successfully logged in and login info updated.', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }
}