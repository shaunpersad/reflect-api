<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends AbstractAppModel implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, EntrustUserTrait;

    const LOGIN_TYPE_PASSWORD = 'password';
    const LOGIN_TYPE_GPLUS = 'gplus';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function attemptToJoin(Organization $organization) {

        /**
         * If the user is the admin of this organization.
         */
        if ($this->hasRole($organization->roleAdminName())) {
            $this->organizations()->attach($this->id);
            return true;
        }

        /**
         * If the user's email is in one of the organization's allowed email domains.
         */
        $user_email_domain = array_pop(explode('@', $this->email));

        if ($organization->emailDomains()->where('domain', $user_email_domain)->exists()) {

            $this->organizations()->attach($this->id);
            return true;
        }
        // TODO: check if user is one of the invited

        return false;
    }

    /**
     * @param Organization $organization
     * @return mixed
     */
    public function isIn(Organization $organization) {

        return $this->organizations()->where('organization_id', $organization->id)->exists();
    }

    public function organizations() {

        return $this->belongsToMany(
            'App\Models\Organization',
            'users_organizations',
            'user_id',
            'organization_id'
        );
    }

    public function discussions() {

        return $this->hasMany('App\Models\Discussion');
    }

    public function reflections() {

        return $this->hasManyThrough(
            'App\Models\Reflection',
            'App\Models\Discussion',
            'user_id',
            'discussion_id'
        );
    }

    public function comments() {

        return $this->hasMany('App\Models\Comment');
    }

    public function tags() {

        return $this->belongsToMany(
            'App\Models\Tags',
            'users_tags',
            'user_id',
            'tag_id'
        );

    }

}
