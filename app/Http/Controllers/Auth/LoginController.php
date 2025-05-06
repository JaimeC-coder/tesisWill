<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function validateLogin(Request $request)
    {
       
        $request->validate(
            [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ],
            [
                 $this->username().'.required' => 'El campo :attribute es obligatorio.',
                'password.required' => 'El campo :attribute es obligatorio.',
            ]
        );

        // Buscar usuario por email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'El correo no fue encontrado.'])->withInput();
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'La contraseña no coincide.'])->withInput();
        }
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where($this->username(), $request->input($this->username()))->first();

        $message = 'Correo o contraseña incorrectos.';

        if (!$user) {

            throw ValidationException::withMessages([
                $this->username() => ['El correo no fue encontrado.'],
            ]);


        } else if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['La contraseña no coincide.'],
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }
}
