<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Candidate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\HttpResponses;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    use HttpResponses;

    public function registerCompany(CompanyRequest $request)
    {
        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->successResponse('Company registered successfully', $company, 201);
    }

    public function registerCandidate(CandidateRequest $request)
    {
        $candidate = Candidate::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->successResponse('Candidate registered successfully', $candidate, 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $request->type === 'company'
            ? Company::where('email', $request->email)->first()
            : Candidate::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('The provided credentials are incorrect.', null, 401);
        }

        $tokenResult = $user->createToken('api_token', [$request->type]); // Add the user's type as scope

        return $this->successResponse('Login successful', [
            'token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
        ]);
    }
}