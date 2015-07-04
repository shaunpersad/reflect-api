<?php namespace App\Models;


use App\Exceptions\MultiTenantException;
use App;
use Illuminate\Database\QueryException;

class Organization extends AbstractAppModel {

    const CURRENT_ORGANIZATION_STRING = 'current_organization';

    protected $appends = ['url'];

    public static function observers() {

        parent::observers();

        self::creating(function(Organization $organization) {

            $settings = new OrganizationSettings();
            $settings->save();
            $organization->settings()->associate($settings);
        });

        self::created(function(Organization $organization) {

            $successful = false;
            while (!$successful) {

                try {

                    $organization->generateVerificationCode();

                    $successful = $organization->save();

                } catch (QueryException $e) {
                    $successful = false;
                }
            }
        });
    }

    /**
     * Gets the current organization from the session.
     *
     * The current organization should only be set after successful auth.
     *
     * @return Organization
     * @throws \App\Exceptions\MultiTenantException
     */
    public static function current() {

        if ($organization = app(self::CURRENT_ORGANIZATION_STRING)) {

            return $organization;
        }
        throw new MultiTenantException();
    }

    /**
     * @return Organization|null
     */
    public static function hasCurrent() {

        return app(self::CURRENT_ORGANIZATION_STRING);
    }

    /**
     * Sets the current organization in the container.
     *
     * The current organization should only be set after successful auth.
     *
     * @param Organization $organization
     */
    public static function setCurrent(Organization $organization) {

        app()->instance(self::CURRENT_ORGANIZATION_STRING, $organization);
    }

    public static function removeCurrent() {

        app()->forgetInstance(self::CURRENT_ORGANIZATION_STRING);
    }

    /**
     * @param $verification_code
     * @return Organization
     */
    public static function findByVerificationCode($verification_code) {

        return Organization::where('verification_code', $verification_code)->first();
    }

    /**
     * @param $verification_code
     * @return Organization
     */
    public static function findByVerificationCodeOrFail($verification_code) {

        return Organization::where('verification_code', $verification_code)->firstOrFail();
    }

    public function generateVerificationCode() {

        $this->verification_code = str_random(100);
    }

    /**
     * @return null|string
     */
    public function roleAdminName() {

        if (!empty($this->app_subdomain)) {

            return $this->app_subdomain.'_'.Role::ROLE_ADMIN;
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function roleAdminDisplayName() {

        if (!empty($this->name)) {

            return $this->name.' Admin';
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getUrlAttribute() {

        $url = null;

        if (!empty($this->app_subdomain)) {

            $url = 'http://'.$this->app_subdomain.'.'.env('APP_DOMAIN');
        }

        $this->attributes['url'] = $url;

        return $url;
    }

    public function categories() {

        return $this->belongsToMany(
            'App\Models\Category',
            'organizations_categories',
            'organization_id',
            'category_id'
        );
    }

    public function discussions() {

        return $this->hasMany('App\Models\Discussion');
    }

    public function emailDomains() {

        return $this->hasMany('App\Models\EmailDomain');
    }

    public function reflections() {

        return $this->hasMany('App\Models\Reflection');
    }


    public function settings() {

        return $this->belongsTo('App\Models\OrganizationSettings', 'organization_settings_id');
    }

    public function users() {

        return $this->belongsToMany(
            'App\Models\Users',
            'users_organizations',
            'organization_id',
            'user_id'
        );
    }

}
