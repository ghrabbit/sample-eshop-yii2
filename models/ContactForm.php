<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
			'verifyCode'=>Yii::t('app','Verification Code'),
			'name'=>Yii::t('app','Family Name'),
			'email'=>Yii::t('app','Email'),
			'subject'=>Yii::t('app','Subject'),
			'body'=>Yii::t('app','Body'),
			'title'=>Yii::t('app','Contact'),
			'refreshCaptcha'=>Yii::t('app','refreshCaptcha'),
			'captchaHelp'=>Yii::t('app','captchaHelp'),
			'submit'=>Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
			'filloutForm'=>Yii::t('app','filloutContactForm'),
		];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
