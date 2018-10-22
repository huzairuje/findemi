<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use App\Notifications\SignUpActivate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $apiLib;
    protected $model;
    protected $notificationUser;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->model = new User();
        $this->notificationUser = new SignUpActivate;
        $this->middleware('auth', ['only' => ['logout', 'issueToken']]);
        $this->middleware('auth', ['except' => ['signUp', 'signupActivate', 'login', 'loginFacebook']]);

    }

    /**
     * Create user(Register User)
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
    public function signUp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'unique:users|string',
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
//            $data->activation_token = str_random(60);

            $data->save();

//            $data->notify($this->notificationUser($data));

            $return = $this->apiLib->singleData($data, []);
            return response($return);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response);
        }
    }

    /**
     * Method For Activate USer Trough Email
     * @param Request $request
     *
     * return [string] message
     */
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

    /**
     * Login For User
     * @param Request $request
     *
     * return [string] message
     */
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

    /**
     * Login For User Using Social Account (Facebook)
     * @param Request $request
     *
     * return [string] message
     */
    public function loginFacebook(Request $request) {
        try {

            $facebook = Socialite::driver('facebook')->userFromToken($request->accessToken);
            if(!$exist = SocialAccount::where('provider',  SocialAccount::SERVICE_FACEBOOK)->where('provider_user_id', $facebook->getId())->first()){

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

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * method for realtime checking email trough form on android
     * @param Request $request
     *
     * return
     */
    public function checkEmail(Request $request)
    {
        //TODO
    }

    /**
     * method for realtime checking username trough form on android
     * @param Request $request
     *
     * return
     */
    public function checkUsername(Request $request)
    {
        //TODO
    }


}
