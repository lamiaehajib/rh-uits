<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginLog;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    
public function store(LoginRequest $request): RedirectResponse
{
    // Authenticate the user
    $request->authenticate();

    // Regenerate the session
    $request->session()->regenerate();

    // Vérifier si un enregistrement existe pour l'utilisateur et le jour courant
    $existingLog = LoginLog::where('user_id', auth()->user()->id)
        ->whereDate('logged_in_at', Carbon::today())
        ->first();

    if (!$existingLog) {
        // Si aucun log pour aujourd'hui, en créer un
        LoginLog::create([
            'user_id' => auth()->user()->id,
            'logged_in_at' => now(),
        ]);
    }

    // Redirection en fonction des rôles
    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('ADMIN1')) {
        return redirect(RouteServiceProvider::HOME);
    } else {
        return redirect()->route('dashboard');
    }
}


public function showLoginHistory(Request $request)
{
    $query = LoginLog::query();

    // Filtre par nom d'utilisateur
    if ($request->filled('username')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->username . '%');
        });
    }

    // Filtre par date
    if ($request->filled('date')) {
        $query->whereDate('logged_in_at', $request->date);
    }

    // Restreindre les logs selon le rôle
    if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('ADMIN1')) {
        $query->where('user_id', auth()->id());
    }

    // Récupérer les logs triés par date
    $logs = $query->orderBy('logged_in_at', 'desc')->paginate(10);

    return view('auth.login_history', [
        'logs' => $logs,
        'daysOfWeek' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
    ]);
}






    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
