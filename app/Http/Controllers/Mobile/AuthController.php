<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use App\Notifications\SignUpActivate;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite;

class AuthController extends Controller
{
    protected $apiLib;
    protected $notificationUser;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->notificationUser = new SignUpActivate;

    }

    /**
     * Create user
     *
     * @param [string] username
     * @param [string] first_name
     * @param [string] last_name
     * @param [boolean] gender
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return Response message
     */
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'gender' => 'required|boolean',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed'
            ]);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response);
            }

            $data = $this->model;
            $data->username = $request->username;
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->gender = $request->gender;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->password = bcrypt($request->password);
            $data->activation_token = str_random(60);

            $data->save();

            $data->notify($this->notificationUser($data));

            $return = $this->apiLib->singleData($data, []);
            return response($return);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response);
        }
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            $response = $this->apiLib->invalidToken($user);
            return $response($response);
        }

        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return $user;
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response);
            }

            $credentials = request(['email', 'password']);

            if(!Auth::attempt($credentials))
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);

            $user = $request->user();

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);

            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response);
        }

    }

    public function loginFacebook(Request $request) {
        try {

            $facebook = Socialite::driver('facebook')->userFromToken($request->accessToken);
            if(!$exist = SocialAccount::where('provider',  SocialAccount::SERVICE_FACEBOOK)->where('provider_user_id', $facebook->getId())->first()){

                // create user account
            }
            return response()->json($this->issueToken($request, 'facebook', $request->accessToken));
        }
        catch(\Exception $e) {
            return response()->json([ "error" => $e->getMessage() ]);
        }

    }

    public function issueToken($request, $provider, $accessToken) {

        /**
         * Here we will request our app to generate access token
         * and refresh token for the user using its social identity by providing access token
         * and provider name of the provider. (I hope its not confusing)
         * and then it goes through social grant and which fetches providers user id then calls
         * findForPassportSocialite from your user model if it returns User object then it generates
         * oauth tokens or else will throw error message normally like other oauth requests.
         */
        $params = [
            'grant_type' => 'social',
            'client_id' => 'your-client-id', // it should be password grant client
            'client_secret' => 'client-secret',
            'accessToken' => $accessToken, // access token from provider
            'provider' => $provider, // i.e. facebook
        ];
        $request->request->add($params);

        $requestToken = Request::create("oauth/token", "POST");
        $response = Route::dispatch($requestToken);

        return json_decode((string) $response->getBody(), true);
    }


}
