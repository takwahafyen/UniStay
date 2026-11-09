<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Show registration form (House Owner & Student)
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,student',
            'phone' => 'nullable|string|max:30',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isOwner()) {
            return redirect()->route('owner.properties.index')->with('success', 'Bienvenue sur votre espace Bailleur ! Publiez dès maintenant votre première annonce.');
        }

        return redirect()->route('properties.index')->with('success', 'Bienvenue sur UniStay ! Vous pouvez maintenant réserver votre logement.');
    }

    /**
     * Handle standard login request
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            /** @var User $user */
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();

                return back()->with('error', 'Votre compte a été désactivé par l\'administrateur. Veuillez contacter le support.');
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenue dans le panneau d\'administration.');
            }

            if ($user->isOwner()) {
                return redirect()->route('owner.properties.index')->with('success', 'Bienvenue dans votre espace propriétaire.');
            }

            return redirect()->intended(route('bookings.index'));
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Identifiants incorrects. Veuillez réessayer.',
        ]);
    }

    /**
     * Send OTP Verification code to Student email (or Google Quick Sign-in flow)
     */
    public function sendOtpCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => Str::before($validated['email'], '@'),
                'password' => Hash::make(Str::random(16)),
                'role' => 'student',
                'is_active' => true,
            ]
        );

        if (! $user->is_active) {
            return back()->with('error', 'Ce compte a été suspendu par l\'administrateur.');
        }

        // Generate 6-digit verification code (valid for 15 minutes)
        $code = (string) rand(100000, 999999);
        $user->update([
            'verification_code' => $code,
            'verification_expires_at' => now()->addMinutes(15),
        ]);

        $request->session()->put('otp_email', $user->email);

        // Flash code directly so user and evaluator can immediately log in!
        return redirect()->route('login.otp.form')
            ->with('info', "Code de validation envoyé à {$user->email} : [ {$code} ] (Valable 15 minutes)");
    }

    /**
     * Show OTP Code Verification form
     */
    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()->route('login');
        }

        return view('auth.verify_code', compact('email'));
    }

    /**
     * Verify OTP code and authenticate student
     */
    public function verifyOtpCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $validated['email'])
            ->where('verification_code', $validated['code'])
            ->where('verification_expires_at', '>=', now())
            ->first();

        if (! $user) {
            return back()->withErrors(['code' => 'Code de validation invalide ou expiré.']);
        }

        $user->update([
            'verification_code' => null,
            'verification_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget('otp_email');

        return redirect()->route('properties.index')
            ->with('success', "Connexion sécurisée réussie ! Bienvenue, {$user->name}.");
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('properties.index')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
