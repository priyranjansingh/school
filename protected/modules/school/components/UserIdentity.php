<?php
ob_start();
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;
    
    public $role;
    
    const ERROR_EMAIL_INVALID = 3;
    const ERROR_STATUS_NOTACTIV = 4;
    const ERROR_STATUS_BAN = 5;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        
        if (strpos($this->username, "@")) {
            $user = Users::model()->find(array("condition" => "email = '".$this->username."' AND school_id IS NOT NULL"));
        } else {
            $user = Users::model()->find(array("condition" => "username = '".$this->username."' AND school_id IS NOT NULL"));
        }
        // pre($user,true);
        if ($user === null)
            if (strpos($this->username, "@")) {
                $this->errorCode = self::ERROR_EMAIL_INVALID;
            } else {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            } else if (md5($this->password) !== $user->password)
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($user->deleted == 1)
            $this->errorCode = self::ERROR_STATUS_BAN;
        else {
            $this->_id = $user->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
            $role = $user->role;
            $role_model = Roles::model()->findByPk($role);
            $school = Schools::model()->findByPk($user->school_id);
            $user_school = array("school" => $school->id,"school_name" => $school->name);
            $this->setState('role', $role_model->role);
            $this->setState('school', $school->name);
            $this->setState('school_id', $school->id);
        }
        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }

}