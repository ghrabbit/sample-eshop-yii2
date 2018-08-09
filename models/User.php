<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /*
    public $id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $address;
    public $salt;
    public $newpassword;
    public $_roles;
    public $auth_key;
    public $access_token;
    */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return $this->password === $password;
        return crypt($password, $this->salt) == $this->password;
    }
    
    static function isLoggedIn()
    {
		
		return !(Yii::$app->user->identity === null);
	}
    
    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t('app','Username'),
			'password' => Yii::t('app','Password'),
			'role' => Yii::t('app','Role'),
			'roles' => Yii::t('app','Roles'),
			'firstname' => Yii::t('app','Firstname'),
			'lastname' => Yii::t('app','Lastname'),
			'email' => Yii::t('app','Email'),
			'phone' => Yii::t('app','Phone'),
			'address' => Yii::t('app','Address'),
			'id' => 'ID',
		);
	}
    
    function getRoles()
	{
		//return explode(",", $this->_roles);
		return preg_split("/[\s,]+/", $this->_roles);
	}
    
    /*
 * 
 * get current user
 * @param
 * @return user that was found in database or null
 * 
 */
	static function current() {
        if(Yii::$app->user->identity)
            return static::findOne(['username' => strtolower(Yii::$app->user->identity-username)]);
        return null;    
	}
    
     public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord or empty($this->auth_key)) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    
   /*
    * 
    * require_role
    * @param
    * @return
    * 
    */
	public function require_role($role) {
		if (!in_array($role, $this->getRoles())){
			throw new CHttpException(500, Yii::t('app','Insufficient Privileges!'));
		}
	}
    
    /*
 * 
 * has_role
 * @param
 * @return
 * 
 * returns true if the user has the role $role
 */
public function has_role($role) 
{
	return in_array($role, $this->getRoles());
}

/*
 * returns user if the email address or username exists and null else 
 * name: emailOrUsername
 * @param
 * @return User
 * 
 * 
 */
	static function getByEmailOrUsername($str) {

		return static::find()->where('email=:str or username=:str', [':str'=>$str])->one();
	}
    
    public function fullname()
	{
		return $this->firstname.' '.$this->lastname;
	}
    
    const SHOWSHORT = 32;
	public function addressShowShort()
	{
	  if (function_exists('mb_substr')) 
	    return  mb_strlen($this->address, '8bit') < self::SHOWSHORT ? CHtml::encode($this->address) :  mb_substr($this->address, 0, self::SHOWSHORT-1, Yii::app()->charset).'...';
      return strlen($this->address) < self::SHOWSHORT ? CHtml::encode($this->address) :  substr($this->address, 0, self::SHOWSHORT-1).'...'; 
	}
}
