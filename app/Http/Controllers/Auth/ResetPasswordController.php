<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Log the reset attempt
        Log::info('Intento de restablecimiento de contraseña para: ' . $request->email);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        Log::info('Respuesta del restablecimiento de contraseña: ' . $response);

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == "passwords.reset"
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }


    protected function sendResetResponse(Request $request, $response)
    {
        Log::info('Contraseña restablecida con éxito para: ' . $request->email);

        if ($request->wantsJson()) {
            return new \Illuminate\Http\JsonResponse(['message' => trans($response)], 200);
        }

        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        Log::error('Error al restablecer contraseña para: ' . $request->email);

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

}
