<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Menu;
use App\Models\Core\ResultObject;
use App\Models\Core\Session;
use App\Models\Core\User;
use App\Models\Country;
use App\Models\PasswordResets;
use App\Notifications\RecoveryPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $user = User::find(auth()->id());
        if($user->active == false) {
            return response()->json(['error' => 'Cuenta desactivada'], 401);
        }

        $expiresAt = new DateTime();
        $segundos = auth()->factory()->getTTL() * 60;
        $expiresAt->modify("+$segundos seconds");

        //Cerrar sesiones anteriores del usuario
        $sesiones = Session::where('users_id',$user->id)->where('is_alive',1)->get();
        foreach($sesiones as $s) {
            $s2 = Session::find($s['id']);
            $s2->is_alive = false;
            $s2->save();
        }

        //Crear nueva sesion
        $sesion = Session::create([
            'users_id' => $user->id,
            'validation_token' => md5($token),
            'ip' => $this->getIp(),
            'is_alive' => true,
            'client' => $request->header('User-Agent'),
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);

        $user->save();

            return $this->respondWithToken($token, $sesion);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $sesion)
    {
        $user = $this->getUser();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
            'sesion' => $sesion
        ]);
    }

    public function register(Request $request) {

        //Implementar en Modulo
    }

    public function recoveryAsk(Request $request) {

        //Implementar en Modulo
    }

    public function recoveryUpdate(Request $request) {
        if(!isset($request->token) && !isset($request->password)) {
            return response(json_encode(new ResultObject('error',null,'You must send a token and a passowrd')), 200);
        }

        $existe = PasswordResets::where('token',$request->token)->first();
        if(!is_null($existe)) {

            $user = User::where('email',$existe->email)->first();
            if(!is_null($user)) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
            DB::select("delete from password_reset_tokens where token = '".$request->token."'");
        }else{
            return response(json_encode(new ResultObject('error',null,'Token has expired')),200);
        }

        return response(json_encode(new ResultObject('success',null,'Password has been changed')),200);

    }

    public function disableAccount() {
        $user = User::find(Auth::user()->id);
        $user->active = 0;
        $user->save();
        Auth::logout();
        return response(json_encode(new ResultObject('success',null,'User has been marked to disable in next days')),200);
    }

    private function getIp(){
        $resultado = null;
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        $resultado = $ip;
                        break;
                    }
                }
            }
        }
        if($resultado == null) $resultado = 'localhost';
        return $resultado;
    }

}
