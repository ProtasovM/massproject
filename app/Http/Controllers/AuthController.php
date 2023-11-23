<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/login",
     *     description="Store a newly access token in storage.",
     *     @OA\Response(response="201", description="New token was created."),
     *     @OA\Response(response="422", description="Validation error."),
     *     @OA\Response(response="503", description="Login invalid."),
     * )
     */
    public function store(StoreAuthRequest $request)
    {
        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response('Login invalid', 503);
        }

        return AuthResource::make($user->createToken(Str::random()))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
