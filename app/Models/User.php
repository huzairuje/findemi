<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Socialite\Facades\Socialite;
use Schedula\Laravel\PassportSocialite\User\UserSocialAccount;

class User extends Authenticatable implements UserSocialAccount
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 'phone' , 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get user from social provider and from provider's user's id
     *
     * @param string $provider Provider name as requested from oauth e.g. facebook
     * @param string $id Id used by provider
     */
    public static function findForPassportSocialite($provider, $id)
    {
        // TODO: Implement findForPassportSocialite() method.
        $account = SocialAccount::where('provider', $provider)->where('provider_user_id', $id)->first();
        if($account) {
            if($account->user){
                return $account->user;
            }
        }
        return;
    }

    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

}
