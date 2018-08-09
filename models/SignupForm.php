<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the signup form.
 */
class SignupForm extends Model
{
   	public $username;
	public $email;
	public $firstname;
	public $lastname;
	public $password;
	public $phone;
	public $address;
	public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
			['username, email, firstname, lastname, password', 'required'],
			// email has to be a valid email address
			['email', 'email'],
			['phone', 'length', 'max'=>11],
			['address', 'length', 'max'=>255],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()],
        ];
    }
    
    /**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return [
			'verifyCode'=>Yii::t('app','Verification Code'),
			'username'=>Yii::t('app','Username'),
			'firstname'=>Yii::t('app','Firstname'),
			'lastname'=>Yii::t('app','Lastname'),
			'email'=>Yii::t('app','Email'),
			'password'=>Yii::t('app','Password'),
			'phone' => Yii::t('app','Phone'),
			'address' => Yii::t('app','Address'),
			//
			'refreshCode' => Yii::t('app','Refresh Code'),
			'captchaHelp' => Yii::t('app','captchaHelp'),
			'submit' => Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
		];
	}

}
