<?php

namespace portalium\site\models;

use yii\base\Model;
use portalium\site\Module;
use Yii;
use portalium\user\models\User;

class ProfileForm extends Model
{
    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $id_avatar;
    public $access_token;

    public function rules()
    {
        $rules = [
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => '\portalium\user\models\User', 'filter' => function ($query) {
                $query->andWhere(['not', ['id_user' => Yii::$app->user->identity->id_user]]);
            }, 'message' => Module::t('This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\portalium\user\models\User', 'filter' => function ($query) {
                $query->andWhere(['not', ['id_user' => Yii::$app->user->identity->id_user]]);
            }, 'message' => Module::t('This email address has already been taken.')],
            ['first_name', 'safe'],
            ['last_name', 'safe'],
            ['id_avatar', 'safe'],
            ['access_token', 'safe'],
            [['id'], 'integer'],
        ];

        return $rules;
    }


    public function attributeLabels()
    {
        return [
            'first_name' => Module::t('First Name'),
            'last_name' => Module::t('Last Name'),
            'username' => Module::t('Username'),
            'email' => Module::t('Email'),
        ];
    }


    public function updateUser()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = User::findOne(Yii::$app->user->identity->id_user);

        if ($user) {
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->id_avatar = $this->id_avatar;
            return $user->save();
        }
    }

    public function filterPostData($data)
    {
        if (isset($data['access_token'])) {
            unset($data['access_token']);
        }
        return $data;
    }
}