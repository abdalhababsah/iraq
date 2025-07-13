<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Constituency;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $constituencies = Constituency::all();
        return view('auth.register', compact('constituencies'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,candidate'],
            
            // Required candidate fields if role is candidate
            'full_name' => ['required_if:role,candidate', 'string', 'max:255'],
            'constituency_id' => ['required_if:role,candidate', 'exists:constituencies,id'],
            'party_bloc_name' => ['required_if:role,candidate', 'string', 'max:255'],
            'phone' => ['required_if:role,candidate', 'string', 'max:20'],
            'biography' => ['required_if:role,candidate', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'candidate') {
            Candidate::create([
                'user_id' => $user->id,
                'constituency_id' => $request->constituency_id,
                'full_name' => $request->full_name,
                'party_bloc_name' => $request->party_bloc_name,
                'phone' => $request->phone,
                'biography' => $request->biography,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect(route('admin.dashboard', absolute: false));
        } else {
            return redirect(route('candidate.dashboard', absolute: false));
        }
    }
}
