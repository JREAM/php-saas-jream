<?php
declare(strict_types=1);

use \Phalcon\Mvc\Model\Behavior\SoftDelete;
use \Phalcon\Mvc\Model\Validator;

class User extends BaseModel
{

    /** @const SOURCE the table name */
    const SOURCE = 'user';

    /** @var array Saves on Memcached Queries */
    public static $_cache;

    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'is_deleted',
            'value' => 1,
        ]));

        $this->setSource(self::SOURCE);
        $this->skipAttributesOnCreate(['reset_key']);
        $this->hasMany('id', 'Project', 'user_id');
        $this->hasMany('id', 'UserAction', 'user_id');
        $this->hasMany('id', 'UserPurchase', 'user_id');
        $this->hasMany('id', 'UserSupport', 'user_id');
        $this->hasOne('id', 'UserReferrer', 'user_id');
        $this->hasOne('id', 'ForumThread', 'user_id');
        $this->hasOne('id', 'Newsletter', 'user_id');
    }

    // --------------------------------------------------------------

    /**
     * This fixes an odd bug.
     *
     * @return string Class Name in lowercase
     */
    public function getSource()
    {
        return self::SOURCE;
    }

    // --------------------------------------------------------------

    public function afterCreate()
    {
        if ($this->save() != false) {
            $this->created_at = getDateTime();
            $this->save();
        }
    }

    // --------------------------------------------------------------

    public function afterUpdate()
    {
        if ($this->save() != false) {
            $this->created_at = getDateTime();
            $this->save();
        }
    }

    // --------------------------------------------------------------

    /**
     * Get the Exact Timestamp with the Applied Timezone
     *
     * @param string Timezone
     * @return string Timestamp
     */
    public static function getLocaleTimestamp($timezone = 'America/New_York')
    {
        $date = new DateTime(time(), new DateTimeZone($timezone));

        return $date->getTimestamp();
    }

    /**
     * Gets a users Email since there are multiple clients
     *
     * @param  integer $id (Optional) Will uses current session by default
     *
     * @return string
     */
    public function getEmail($id = false)
    {
        if (!$id) {
            $id = $this->session->get('id');
        }

        // Only do this locally!
        if (\APPLICATION_ENV === \APP_DEVELOPMENT) {
            if (!$this->email && !$this->facebook_email && !$this->google_email) {
                return '&lt;&lt;no email&gt;&gt;';
            }
        }

        if ($this->email) {
            return $this->email;
        } elseif ($this->facebook_email) {
            return $this->facebook_email;
        } elseif ($this->google_email) {
            $this->google_email;
        }

        return false;
    }

    // --------------------------------------------------------------

    /**
     * Gets a users Alias since there are multiple clients
     *
     * @param  integer $id (Optional) Will uses current session by default
     *
     * @return string
     */
    public function getAlias($id = false)
    {
        if (!$id) {
            $id = $this->session->get('id');
        }

        if ($this->alias) {
            return $this->alias;
        } elseif ($this->facebook_alias) {
            return $this->facebook_alias;
        } elseif ($this->google_alias) {
            return $this->google_alias;
        }

        return false;
    }

    // --------------------------------------------------------------

    /**
     * Gets a users Icon from a service
     *
     * @param  integer $id   (Optional) Will uses current session by default
     * @param  size    $size (Optional) Will change the HTML width
     *
     * @return string
     */
    public function getIcon($id = false, $size = false)
    {
        if (!$id) {
            $id = $this->session->get('id');
        }

        if ($this->facebook_id) {
            $size = ($size) ? "width=$size" : false;

            return sprintf(
                "<img $size src='https://graph.facebook.com/%s/picture?type=small' alt='facebook' />",
                $this->facebook_id
            );
        }

        $email = ($this) ? $this->email : 'none@none.com';
        $default = "";
        $size = ($size) ? $size : 40;
        $url = sprintf(
            'https://www.gravatar.com/avatar/%s?d=%s&s=%s',
            md5(strtolower(trim($email))),
            urlencode($default),
            $size
        );

        return "<img src='$url' alt='Gravatar' />";
    }

    // --------------------------------------------------------------

    /**
     * Is a user banned?
     *
     * @param  object $user
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

    // --------------------------------------------------------------

    /**
     * Captures where the user signed up from
     *
     * @return [type] [description]
     */
    public function saveReferrer($userId, $request)
    {
        $referrer = new \UserReferrer();
        $referrer->user_id = $userId;
        $referrer->referrer = $request->getHTTPReferer();
        $referrer->data = json_encode([
            'page'           => basename($_SERVER['PHP_SELF']),
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

    // --------------------------------------------------------------

    public function doLogin($email, $password)
    {
        if (!$email || !$password) {
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
                $user->login_attempt = null;
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
            $user->login_attempt = $user->login_attempt + 1;
            $user->login_attempt_at = date('Y-m-d H:i:s', strtotime('now'));
            $user->save();
        }

        $this->flash->error('Incorrect Credentials');

        return false;
    }

    // --------------------------------------------------------------
}
