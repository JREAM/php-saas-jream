<?php

declare(strict_types=1);

use \Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class User extends BaseModel
{

    /**
     * @var Table Rows
     */
    public $id;
    public $role;
    public $account_type;
    public $alias;
    public $email;
    public $password;
    public $password_salt;
    public $use_email;
    public $use_icon;
    public $facebook_id;
    public $facebook_alias;
    public $facebook_email;
    public $google_id;
    public $google_alias;
    public $google_email;
    public $github_id;
    public $github_alias;
    public $github_email;
    public $session_id;
    public $timezone;
    public $email_notifications;
    public $system_notifications;
    public $email_change;
    public $email_change_key;
    public $email_change_key_expires_at;
    public $password_reset_key;
    public $password_reset_expires_at;
    public $login_attempt;
    public $login_attempt_at;
    public $is_banned;
    public $is_deleted;
    public $is_deleted_at;
    public $is_created_at;
    public $is_updated_at;

    /**
     * Constants for account types, accessible anywhere
     */
    const ACCOUNT_TYPE_JREAM           = 'jream';
    const ACCOUNT_TYPE_SOCIAL_GITHUB   = 'github';
    const ACCOUNT_TYPE_SOCIAL_GOOGLE   = 'google';
    const ACCOUNT_TYPE_SOCIAL_FACEBOOK = 'facebook';

    /**
     * @var array $accountTypes
     */
    protected $accountTypes = [
        'local'  => [
            self::ACCOUNT_TYPE_JREAM => ['exists' => 0],
        ],
        'social' => [
            self::ACCOUNT_TYPE_SOCIAL_GITHUB   => ['exists' => 0],
            self::ACCOUNT_TYPE_SOCIAL_GOOGLE   => ['exists' => 0],
            self::ACCOUNT_TYPE_SOCIAL_FACEBOOK => ['exists' => 0],
        ],
    ];


    // -----------------------------------------------------------------------------

    /**
     * Phalcons std initializer when model is ready
     *
     * @return void
     */
    public function initialize(): void
    {
        /** DB Table Name */
        $this->setSource('user');

        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->skipAttributesOnCreate(['reset_key']);
        $this->hasMany('id', 'Project', 'user_id');
        $this->hasMany('id', 'UserAction', 'user_id');
        $this->hasMany('id', 'UserPurchase', 'user_id');
        $this->hasMany('id', 'UserSupport', 'user_id');
        $this->hasOne('id', 'UserReferrer', 'user_id');
        $this->hasOne('id', 'ForumThread', 'user_id');
        $this->hasOne('id', 'Newsletter', 'user_id');
    }

    // -----------------------------------------------------------------------------

    /**
     * Get the Exact Timestamp with the Applied Timezone
     *
     * @param string Timezone
     *
     * @return string Timestamp
     */
    public static function getLocaleTimestamp($timezone = 'America/New_York')
    {
        $date = new DateTime(time(), new DateTimeZone($timezone));

        return $date->getTimestamp();
    }

    // -----------------------------------------------------------------------------

    /**
     * Gets a users Email since there are multiple clients
     *
     * @param  mixed $id (Optional) Will uses current session by default
     *
     * @return string
     */
    public function getEmail($id = false)
    {
        if ( ! $id) {
            $id = $this->session->get('id');
        }

        // Only do this locally!
        if (\APPLICATION_ENV === \APP_DEVELOPMENT) {
            if ( ! $this->email && ! $this->facebook_email && ! $this->google_email) {
                return '&lt;&lt;no email&gt;&gt;';
            }
        }

        if ($this->email) {
            return $this->email;
        } else if ($this->facebook_email) {
            return $this->facebook_email;
        } else if ($this->google_email) {
            $this->google_email;
        }

        return false;
    }

    // -----------------------------------------------------------------------------

    /**
     * Gets a users Alias since there are multiple clients
     *
     * @param  mixed $id (Optional) Will uses current session by default
     *
     * @return string
     */
    public function getAlias($id = false)
    {
        if ( ! $id) {
            $id = $this->session->get('id');
        }

        if ($this->alias) {
            return $this->alias;
        } else if ($this->facebook_alias) {
            return $this->facebook_alias;
        } else if ($this->google_alias) {
            return $this->google_alias;
        } else if ($this->github_alias) {
            return $this->github_alias;
        }

        return false;
    }

    // -----------------------------------------------------------------------------

    /*
     * After a user is fetched, set some class variables
     */
    public function afterFetch()
    {
        if ($this->email) {
            $this->accountTypes[ 'local' ][ self::ACCOUNT_TYPE_JREAM ] = ['exists' => 1];
        }
        if ($this->github_id) {
            $this->accountTypes[ 'social' ][ self::ACCOUNT_TYPE_SOCIAL_GITHUB ] = ['exists' => 1];
        }
        if ($this->google_id) {
            $this->accountTypes[ 'social' ][ self::ACCOUNT_TYPE_SOCIAL_GOOGLE ] = ['exists' => 1];
        }
        if ($this->facebook_id) {
            $this->accountTypes[ 'social' ][ self::ACCOUNT_TYPE_SOCIAL_FACEBOOK ] = ['exists' => 1];
        }
    }

    // -----------------------------------------------------------------------------

    public function getActiveAccounts(): array
    {
        $output = [
            'github'   => 0,
            'google'   => 0,
            'facebook' => 0,
        ];

        if ($this->github_id) {
            $output[ 'github' ] = 1;
        }
        if ($this->google_id) {
            $output[ 'google' ] = 1;
        }
        if ($this->facebook_id) {
            $output[ 'facebook' ] = 1;
        }

        return $output;
    }

    // -----------------------------------------------------------------------------

    /**
     * Gets a users Icon from a service
     *
     * @param  mixed $id   (Optional) Will uses current session by default
     * @param  mixed $size (Optional) Will change the HTML width
     *
     * @return string
     */
    public function getIcon($id = false, $size = false)
    {
        if ( ! $id) {
            $id = $this->session->get('id');
        }

        if ($this->github_id) {
            return sprintf("<img src='https://avatars0.githubusercontent.com/u/%s?v=4'>", $this->github_id);
        }

        if ($this->google_id) {
            return sprintf("<img src='https://avatars0.githubusercontent.com/u/%s?v=4'>", $this->google_id);
        }

        if ($this->facebook_id) {
            $size = ($size) ? "width=$size" : false;

            return sprintf("<img $size src='https://graph.facebook.com/%s/picture?type=small' alt='facebook' />", $this->facebook_id);
        }

        $email   = ($this) ? $this->email : 'none@none.com';
        $default = "";
        $size    = $size ?: 40;
        $url     = sprintf('https://www.gravatar.com/avatar/%s?d=%s&s=%s', md5(strtolower(trim($email))), urlencode($default), $size);

        return "<img src='$url' alt='Gravatar' />";
    }

    // -----------------------------------------------------------------------------

    /**
     * Is a user banned?
     *
     * @return boolean
     */
    public function isBanned()
    {
        if (property_exists($this, 'is_banned') && $this->is_banned == 1) {
            return true;
        }

        return false;
    }

    // -----------------------------------------------------------------------------

    /**
     * Captures where the user signed up from
     *
     * @return int $user_id
     * @return Phalcon\Http\Request $request
     */
    public function saveReferrer($user_id, $request)
    {
        $referrer           = new \UserReferrer();
        $referrer->user_id  = $user_id;
        $referrer->referrer = $request->getHTTPReferer();
        $referrer->data     = json_encode([
            'page'           => basename($_SERVER[ 'PHP_SELF' ]),
            'query_string'   => $request->getQuery(),
            'is_ajax'        => $request->isAjax(),
            'is_ssl'         => $request->isSecure(),
            'server_address' => $request->getServerAddress(),
            'server_name'    => $request->getServerName(),
            'http_host'      => $request->getHttpHost(),
            'client_address' => $request->getClientAddress(),
        ]);

        return $referrer->save();
    }

    // -----------------------------------------------------------------------------

    /**
     *
     * Do Login
     *
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    public function doLogin($email, $password)
    {
        if ( ! $email || ! $password) {
            $this->flash->error('email and password field(s) are required.');

            return false;
        }

        $user = self::findFirstByEmail($email);
        if ($user) {
            if ($user->is_deleted == 1) {
                $this->flash->error('This user has been permanently removed.');

                return false;
            }
            // Prevent Spam logins
            if ($user->login_attempt >= 5) {
                if (strtotime('now') < strtotime($user->login_attempt_at) + 600) {
                    $this->flash->error('Too many login attempts. Timed out for 10 minutes.');

                    return false;
                }
                // Clear the login attempts if time has expired
                $user->login_attempt    = null;
                $user->login_attempt_at = null;
                $user->save();
            }

            if ($this->security->checkHash($password, $user->password)) {
                if ($user->isBanned()) {
                    $this->flash->error('Sorry, your account has been locked due to suspicious activity.
                                For support, contact <strong>hello@jream.com</strong>.');

                    return false;
                }

                // $this->createSession($user, [], $remember_me);
                $this->createSession($user);

                return true;
            }

            // Track the login attempts
            ++$user->login_attempt;
            $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
            $user->save();
        }

        $this->flash->error('Incorrect Credentials');

        return false;
    }

    // -----------------------------------------------------------------------------

}
