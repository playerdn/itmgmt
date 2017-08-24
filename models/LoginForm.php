<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\rbac\RbacHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            
            // Revole old roles and re-assing them again
            $this->RevokeRoles($user);
            // Assign roles corresponding with user groups in AD
            $this->AssignRoles($user);
           
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = \Edvlerblog\Adldap2\model\UserDbLdap::findByUsername($this->username);
        }

        return $this->_user;
    }

    private function AssignRoles($user) {
        $roles = RbacHelper::GetRolesForGroups($this->GetLDAPGroups());
        $auth = \Yii::$app->authManager;
        
        foreach ($roles as $role) {
            $auth->assign($auth->getRole($role), $user->getID());
        }
    }
    private function GetLDAPGroups() {
        $o = \Yii::$app->ad->search()->findBy('sAMAccountname', $this->username);
        
        $groups = array();
        foreach($o->getGroups() as $g) {
            array_push($groups, $g->getCommonName());
        }
        
        return $groups;
    }
    private function RevokeRoles($user) {
        $auth = \Yii::$app->authManager;
        
        $auth->revokeAll($user->getId());
    }
}
