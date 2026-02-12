<?php

namespace portalium\site\models;

use Yii;
use yii\base\Model;
use portalium\site\Module;
use portalium\site\model\Setting;

class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha','captchaAction'=>"auth/default/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Module::t('Name'),
            'email' => Module::t('Email'),
            'subject' => Module::t('Subject'),
            'body' => Module::t('Body'),
            'verifyCode' => Module::t('Verification Code'),
        ];
    }

    public function sendEmail($email)
    {
        return Yii::$app->site->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}