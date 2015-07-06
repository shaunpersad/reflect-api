<?php


namespace App\Services\Api\V1\Resources\Organizations;


use App\Events\OrganizationRegistrationBegan;
use App\Events\OrganizationRegistrationCompleted;
use App\Exceptions\BadRequestException;
use App\Models\EmailDomain;
use App\Models\Organization;
use App\Models\Role;
use App\Services\Api\V1\Resources\AbstractEntityResource;
use Illuminate\Database\QueryException;

class OrganizationsResource extends AbstractEntityResource {

    /**
     * @return mixed
     */
    public function query() {

        return Organization::query();
    }

    public function all($params = array()) {
        // TODO: Implement all() method.
    }

    public function beginRegistration($params = array()) {

        $defaults = array(
            'registration_email' => $registration_email = null,
            'name' => $name = null
        );

        $rules = array(
            'registration_email' => array('required', 'email'),
            'name' => array('required')
        );

        $params = $this->validateParams($defaults, $params, $rules);

        extract($params);

        $organization = new Organization();
        $organization->registration_email = $registration_email;
        $organization->name = $name;
        $organization->save();

        $this->entity = $organization;
        $this->api->event->fire(new OrganizationRegistrationBegan($organization));

        return $this->entity();
    }

    public function completeRegistration($params = array()) {

        $user = $this->api->user();

        $defaults = array(
            'verification_code' => $verification_code = null,
            'app_subdomain' => $app_subdomain = null,
            'email_domains' => $email_domains = null,
            'name' => $name = null
        );


        $this->api->validation_factory->extend('email_domains', function($attribute, $value, $parameters) {
            $email_domains = $this->toArray($value);

            foreach ($email_domains as $domain) {

                if (!filter_var('fakeemail@'.$domain, FILTER_VALIDATE_EMAIL)) {

                    return false;
                }
            }
            return true;
        });

        $rules = array(
            'verification_code' => array('required', 'exists:organizations,verification_code,registration_complete,0'),
            'app_subdomain' => array('required', 'alpha_dash', 'unique:organizations'),
            'email_domains' => array('sometimes', 'email_domains')
        );

        $messages = array(
            'app_subdomain.alpha_dash' => 'Please enter a valid subdomain',
            'app_subdomain.unique' => 'The requested subdomain is not available',
            'email_domains.email_domains' => 'Please enter valid email domains'
        );

        $params = $this->validateParams($defaults, $params, $rules, $messages);

        extract($params);

        /**
         * @var Organization
         */
        $organization = $this->query()
            ->where('verification_code', $verification_code)
            ->where('registration_complete', false)
            ->first();

        $organization->app_subdomain = $app_subdomain;

        if (!empty($name)) {
            $organization->name = $name;
        }

        /**
         * It's possible that during the time when the above unique subdomain validation
         * is run and the time the organization is saved, that someone else claimed the subdomain.
         * In that case, the save() query will fail, so we wrap it in a try/catch.
         */
        try {

            $organization->save();

            $email_domains = $this->toArray($email_domains);

            if (!empty($email_domains)) {

                $saved_email_domains = array();

                foreach ($email_domains as $email_domain) {

                    $saved_email_domains[] = new EmailDomain(array('domain' => trim($email_domain)));
                }

                $organization->emailDomains()->saveMany($saved_email_domains);
            }

            $admin = new Role();
            $admin->name = $organization->roleAdminName();
            $admin->display_name = $organization->roleAdminDisplayName();
            $admin->save();

            $user->attachRole( $admin );

            $organization->registration_complete = true;
            $organization->save();

            $this->entity = $organization;

            $this->api->event->fire(new OrganizationRegistrationCompleted($organization));

            return $this->entity();

        } catch(QueryException $e) {

            throw new BadRequestException('The requested subdomain is not available');
        }

    }
}