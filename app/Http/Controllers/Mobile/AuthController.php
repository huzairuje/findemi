<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\User\CheckEmailUserRequest;
use App\Http\Requests\User\CheckFullNameUserRequest;
use App\Http\Requests\User\CheckPhoneNumberUserRequest;
use App\Http\Requests\User\CheckUsernameUserRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Models\User;
use App\Services\User\LoginUserService;
use App\Services\User\SignUpActivateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\userResponseLibrary;
use App\Notifications\SignUpActivate;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Services\User\CreateUserService;
use Exception;

class AuthController extends Controller
{
    protected $userResponseLibrary;
    protected $users;
    protected $notificationUser;
    protected $username = 'username';
    protected $createUserService;
    protected $loginUserService;
    protected $userValidator;
    protected $userActivateSignUp;

    public function __construct(UserResponseLibrary $userResponseLibrary,
                                User $users,
                                SignUpActivate $signUpActivate,
                                CreateUserService $createUserService,
                                LoginUserService $loginUserService,
                                SignUpActivateService $signUpActivateService)
    {
        $this->userResponseLibrary = $userResponseLibrary;
        $this->users = $users;
        $this->notificationUser = $signUpActivate;
        $this->createUserService = $createUserService;
        $this->loginUserService = $loginUserService;
        $this->userActivateSignUp = $signUpActivateService;
    }

    /**
     * method for real time checking email and username trough form on android
     * @param CheckEmailUserRequest $request
     *
     * @return response
     */
    public function checkEmailRegister(CheckEmailUserRequest $request)
    {
        try {
            $data = $request->input('email');
            $response = $this->userResponseLibrary->emailIsAvailable($data);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * method for real time checking username trough form on android
     * @param CheckUsernameUserRequest $request
     *
     * @return response
     */
    public function checkUserNameRegister(CheckUsernameUserRequest $request)
    {
        try {
            $data = $request->input('username');
            $response = $this->userResponseLibrary->usernameIsAvailable($data);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Validate full name from mobile(Register User)
     *
     * @param CheckFullNameUserRequest $request
     * @return response
     */
    public function checkFullNameRegister(CheckFullNameUserRequest $request)
    {
        try{
            $data = $request->input('full_name');
            $response = $this->userResponseLibrary->fullNameIsOk($data);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Validate phone number from mobile(Register User)
     *
     * @param CheckPhoneNumberUserRequest $request
     * @return response
     */
    public function checkPhoneNumberRegister(CheckPhoneNumberUserRequest $request)
    {
        try{
            $data = $request->input('phone');
            $response = $this->userResponseLibrary->phoneIsOk($data);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create user(Register User)
     *
     * @param CreateUserRequest $request
     * @return response
     */
    public function signUp(CreateUserRequest $request)
    {
        try {
            $data = $this->createUserService->create($request);
            $return = $this->userResponseLibrary->successRegister($data);
            return response($return, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Method For Activate User Trough Email
     * @param $token
     *
     * @return $user
     */
    public function signUpActivate($token)
    {
        $user = $this->userActivateSignUp->activateUser($token);
        if (!$user) {
            $response = $this->userResponseLibrary->invalidToken($user);
            return $response($response);
        }
        $user->activation_token = '';
        $user->save();
        return $user;
    }

    /**
     * Login For User
     * @param LoginUserRequest $request
     * don't forget while registering user with email, user get notification to email,
     * and activate user on function signUpActivate with params $token.
     * @return response
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $credentials = request(['email', 'password', 'username']);
            if(!Auth::attempt($credentials)){
                $response = $this->userResponseLibrary->unauthorizedEmailAndPassword();
                return response($response, Response::HTTP_UNAUTHORIZED);
            }
            $credentials['active']=false;
            if(!Auth::attempt($credentials)){
                $response = $this->userResponseLibrary->userIsNotActive();
                return response($response, Response::HTTP_UNAUTHORIZED);
            }
            $credentials['is_blocked']=false;
            if(!Auth::attempt($credentials)){
                $response = $this->userResponseLibrary->userIsBlocked();
                return response($response, Response::HTTP_UNAUTHORIZED);
            }
            $data = $this->loginUserService->loginUser($request);
            $return = $this->userResponseLibrary->successLogin($data);
            return response($return, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
     * @return response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response($this->userResponseLibrary->successLogout(), Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
