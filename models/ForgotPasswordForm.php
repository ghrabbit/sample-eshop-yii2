<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ForgotPasswordForm class.
 * ForgotPasswordForm is the data structure for keeping
 * ForgotPassword form data. It is used by the 'forgotPassword' action of 'AccountController'.
 */
class ForgotPasswordForm extends Model
{
	public $emailOrUsername;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return [
			// emailOrUsername is required
			['emailOrUsername', 'required'],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()],
		];
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return [
			'verifyCode'=>Yii::t('app','Verification Code'),
			'emailOrUsername'=>Yii::t('app','emailOrUsername'),
			
			'refreshCode' => Yii::t('app','Refresh Code'),
			'captchaHelp' => Yii::t('app','captchaHelp'),
			'submit' => Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
		];
	}
}
