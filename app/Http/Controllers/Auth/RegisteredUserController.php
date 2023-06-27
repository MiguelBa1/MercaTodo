<?php

namespace App\Http\Controllers\Auth;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\Department;
use App\Services\User\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
            'departments' => Department::all(),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value')
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(UserRegisterRequest $request, UserService $userService): RedirectResponse
    {
        $user = $userService->registerUser($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('verification.notice'));
    }
}
