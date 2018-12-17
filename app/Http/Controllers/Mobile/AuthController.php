<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Models\User;
use App\Services\User\LoginUserService;
use App\Services\User\SignUpActivateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use App\Library\UsersResponseLibrary;
use App\Notifications\SignUpActivate;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Services\User\CreateUserService;
use App\Validators\UserValidator;

class AuthController extends Controller
{
    protected $apiLib;
    protected $userApiLib;
    protected $model;
    protected $notificationUser;
    protected $username = 'username';
    protected $createUserService;
    protected $loginUserService;
    protected $userValidator;
    protected $userActivateSignUp;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->userApiLib = new UsersResponseLibrary;
        $this->model = new User();
        $this->notificationUser = new SignUpActivate;
        $this->createUserService = new CreateUserService;
        $this->loginUserService = new LoginUserService;
        $this->userValidator = new UserValidator();
        $this->userActivateSignUp = new SignUpActivateService();
    }

    /**
     * method for realtime checking email and username trough form on android
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkEmailRegister(Request $request)
    {
        try {
            $validator = $this->userValidator->validateEmailRegistration($request);
            if ($validator->fails()) {
                $response = $this->userApiLib->emailRegistered();
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $response = $this->userApiLib->emailIsAvailable();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * method for realtime checking username trough form on android
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkUserNameRegister(Request $request)
    {
        try {
            $validator = $this->userValidator->validateUsernameRegistration($request);
            if ($validator->fails()) {
                $response = $this->userApiLib->usernameRegistered();
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $response = $this->userApiLib->usernameIsAvailable();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Validate full name from mobile(Register User)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkFullNameRegister(Request $request)
    {
        try{
            $validator = $this->userValidator->validateFullNameRegistration($request);
            if ($validator->fails()) {
                $response = $this->userApiLib->fullNameIsWrongFormat();
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $response = $this->userApiLib->fullNameIsOk();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Validate phone number from mobile(Register User)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkPhoneNumberRegister(Request $request)
    {
        try{
            $validator = $this->userValidator->validatePhoneRegistration($request);
            if ($validator->fails()) {
                $response = $this->userApiLib->phoneIsRegistered();
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $response = $this->userApiLib->phoneIsOk();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Create user(Register User)
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function signUp(CreateUserRequest $request)
    {
        try {
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
     * @param $token
     *
     * @return
     */
    public function signUpActivate($token)
    {
        $user = $this->userActivateSignUp->activateUser($token);
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
     * @param LoginUserRequest $request
     * don't forget while registering user with email, user get notification to email,
     * and activate user on function signUpActivate with params $token.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $credentials = request(['email', 'password', 'username']);
            if(!Auth::attempt($credentials)){
                $response = $this->userApiLib->unauthorizedEmailAndPassword();
                return response($response, Response::HTTP_UNAUTHORIZED);
            }
            $credentials['active']=false;
            if(!Auth::attempt($credentials)){
                $response = $this->userApiLib->userIsNotActive();
                return response($response, Response::HTTP_UNAUTHORIZED);
            }
            $credentials['is_blocked']=false;
            if(!Auth::attempt($credentials)){
                $response = $this->userApiLib->userIsBlocked();
                return response($response, Response::HTTP_UNAUTHORIZED);
            }
            $data = $this->loginUserService->loginUser($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * Login For User Using Social Account (Facebook)
     * @param Request $request
     *
     * @return response
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response($this->userApiLib->successLogout(), Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

}
