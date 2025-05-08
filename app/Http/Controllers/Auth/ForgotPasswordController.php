<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }


    protected function validateEmail(Request $request)
    {
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            [
                'email.required' => 'El campo correo es obligatorio.',
                'email.email' => 'El campo correo no es un correo válido.',
                'email.exists' => 'El correo no fue encontrado.'
            ]
        );
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);


        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        Log::info('Intento de envío de link de restablecimiento para: ' . $response);
        // $response = "El correo fue enviado correctamente.";
        return $response == "passwords.sent"
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }


    protected function sendResetLinkResponse(Request $request, $response)
    {
        Log::info('Respuesta del envío de link de restablecimiento1: ' . $response);
        Log::info('Respuesta del envío de link de restablecimiento2: ' . $request);
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans("El correo fue enviado correctamente.")], 200)
            : back()->with('status', trans($response));
    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
