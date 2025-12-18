<?php

namespace portalium\site\models;

use Exception;
use portalium\base\Event;
use yii\base\Model;
use portalium\site\Module;
use Yii;
use portalium\user\models\User;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifyCode;
    public $first_name;
    public $last_name;
    public $isApiRequest;
    const RECAPTCHA_ACTIVE = 1;
    const RECAPTCHA_PASSIVE = 0;


    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\portalium\user\models\User', 'message' => Module::t('This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_.]+$/', 'message' => Module::t('Username can only contain alphanumeric characters, underscores, and dots.')],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\portalium\user\models\User', 'message' => Module::t('This email address has already been taken.')],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['first_name', 'safe'],
            ['last_name', 'safe'],
            [
                ['verifyCode'], \himiklab\yii2\recaptcha\ReCaptchaValidator3::className(),
                'secret' => '6LdtOVspAAAAABzrPaWYTrqkSq4ppo6ZX1Z_vuzn',
                'threshold' => '0.5',
                'action' => 'signup',
                'when' => function () {
                    if (Yii::$app instanceof \portalium\web\Controller) {
                        if (Yii::$app->setting->getValue('site::recaptcha') == Yii::$app->site->RECAPTCHA_ACTIVE) {
                            return Yii::$app->setting->getValue('site::recaptcha');
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            ],


        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Module::t('Username'),
            'email' => Module::t('Email'),
            'password' => Module::t('Password'),
            'verifyCode' => Module::t('Verify Code'),
            'rememberMe' => Module::t('Remember Me'),
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->setPassword($this->password);
        $user->access_token = \Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = Yii::$app->setting->getValue('site::verifyEmail') ? User::STATUS_PASSIVE : (Yii::$app->setting->getValue('site::userStatus'));


        if ($user->save()) {
            if (Yii::$app->setting->getValue('site::verifyEmail')) {
                try {
                    if ($this->sendEmail($user)) {
                        Yii::$app->trigger(Module::EVENT_ON_SIGNUP, new Event(['payload' => $user]));
                        Yii::$app->session->setFlash('success', 'Your registration has been successfully completed. Confirm the confirmation e-mail sent to your e-mail.');
                        return $user;
                    }
                } catch (Exception $e) {
                    Yii::warning($e->getMessage());
                    Yii::$app->session->addFlash('error', Module::t('SMTP error: Unable to send verification email. Please try again later.'));
                    $user->delete();
                }
            } else if (!(Yii::$app->setting->getValue('site::verifyEmail'))) {
                Yii::$app->session->setFlash('success', 'Your registration has been successfully completed.');
                Yii::$app->trigger(Module::EVENT_ON_SIGNUP, new Event(['payload' => $user]));
                return $user;
            }
        } else {
            Yii::warning($user->errors);
        }
        return null;
    }

    protected function sendEmail($user)
    {
        Yii::$app->site->mailer->setViewPath(Yii::getAlias('@portalium/site/mail'));
        return Yii::$app
            ->site
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->setting->getValue('email::address') => Yii::$app->setting->getValue('email::displayname')])
            ->setTo($this->email)
            ->setSubject('Account registration at ' .  Yii::$app->setting->getValue('app::title'))
            ->send();
    }
}
