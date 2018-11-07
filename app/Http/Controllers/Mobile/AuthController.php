<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use App\Notifications\SignUpActivate;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Services\User\CreateUserService;
use App\Validators\UserValidator;

class AuthController extends Controller
{
    protected $apiLib;
    protected $model;
    protected $notificationUser;
    protected $username = 'username';
    protected $createUserService;
    protected $userValidator;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->model = new User();
        $this->notificationUser = new SignUpActivate;
        $this->createUserService = new CreateUserService();
        $this->userValidator = new UserValidator();
    }

    /**
     * method for realtime checking email and username trough form on android
     * @param Request $request
     *
     * return
     */
    public function checkEmailRegister(Request $request)
    {
        try {
            $validator = $this->userValidator->validateEmailRegistration($request);

            if ($validator->fails()) {
                $response = $this->apiLib->emailRegistered();
                return response($response, Response::HTTP_BAD_REQUEST);
            } else {
                $response = $this->apiLib->emailIsAvailable();
                return response($response, Response::HTTP_OK);
            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * method for realtime checking username trough form on android
     * @param Request $request
     *
     * return
     */
    public function checkUserNameRegister(Request $request)
    {
        try {
            $validator = $this->userValidator->validateUsernameRegistration($request);

            if ($validator->fails()) {
                $response = $this->apiLib->usernameRegistered();
                return response($response, Response::HTTP_BAD_REQUEST);
            } else {
                $response = $this->apiLib->usernameIsAvailable();
                return response($response, Response::HTTP_OK);
            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
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
            $validator = $this->userValidator->validateRegistration($request);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }

            $data = $this->createUserService->create($request);

            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Method For Activate User Trough Email
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
            $validator = $this->userValidator->validateLogin($request);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }

            $credentials = request(['email', 'password', 'username']);

            if(!Auth::attempt($credentials)){
                $response = $this->apiLib->unauthorizedEmailAndPassword($credentials);
                return response($response, Response::HTTP_UNAUTHORIZED);
            }

            $user = $request->user();

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);

            $token->save();

            $response = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ];
            return response($this->apiLib->singleData($response, []), Response::HTTP_OK);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
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
        try {
            $request->user()->token()->revoke();
            return response($this->apiLib->successLogout(), Response::HTTP_OK);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

}
