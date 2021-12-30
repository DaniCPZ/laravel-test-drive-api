<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return response(
            content: $user,
            status: Response::HTTP_CREATED
        );
    }
}
