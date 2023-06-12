<?php

namespace App\Http\Controllers\Auth;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'departments' => \App\Models\Department::all(),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'document' => 'required|integer|digits_between:6,12|unique:'.User::class,
            'document_type' => 'required|string|max:255',
            'phone' => 'required|integer|digits_between:6,12',
            'address' => 'required|string|max:255',
            'city_id' => 'required|integer',
        ]);

        $user = User::query()->create([
            'name' => $request['name'],
            'surname' => $request['surname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'document' => $request['document'],
            'document_type' => $request['document_type'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'city_id' => $request['city_id'],
        ]);

        $user->assignRole('customer');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('verification.notice'));
    }
}
