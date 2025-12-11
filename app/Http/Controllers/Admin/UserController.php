<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewReports'); // restrict to admins
    }

    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
            'send_reset' => 'sometimes|boolean',
        ]);

        $data['is_admin'] = !empty($data['is_admin']);

        // If admin chose to send a reset link, we don't require a supplied password.
        if (!empty($data['send_reset'])) {
            // Create a random password so DB constraints are satisfied, user will reset via link.
            $random = \Illuminate\Support\Str::random(24);
            $data['password'] = Hash::make($random);
        } else {
            $data['password'] = !empty($data['password']) ? Hash::make($data['password']) : null;
        }

        $user = User::create(array_filter($data, fn($v) => $v !== null));

        // Send a welcome notification (queued via EmailQueueService)
        try {
            $user->notify(new WelcomeNotification($user));

            // If requested, create a password reset token and send reset notification
            if (!empty($data['send_reset'])) {
                $token = \Illuminate\Support\Facades\Password::broker()->createToken($user);
                $user->notify(new \App\Notifications\ResetPasswordNotification($token));
            }
        } catch (\Throwable $e) {
            // Log but don't block creation
            \Log::warning('[admin.user] failed to send notifications', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
            'send_reset' => 'sometimes|boolean',
        ]);

        // Handle password update or send reset
        if (!empty($data['send_reset'])) {
            // generate random password and send reset link
            $random = \Illuminate\Support\Str::random(24);
            $data['password'] = Hash::make($random);
        } elseif (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_admin'] = !empty($data['is_admin']);

        $user->update($data);

        if (!empty($data['send_reset'])) {
            try {
                $token = \Illuminate\Support\Facades\Password::broker()->createToken($user);
                $user->notify(new \App\Notifications\ResetPasswordNotification($token));
            } catch (\Throwable $e) {
                \Log::warning('[admin.user] failed to send reset notification', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
