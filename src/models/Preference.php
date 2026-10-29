<?php

namespace portalium\site\models;

use portalium\user\models\User;
use portalium\workspace\models\Workspace;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "site_preference".
 *
 * @property int $id_preference
 * @property int $id_user
 * @property int $id_setting
 * @property int|null $id_workspace
 * @property string|null $value
 * @property string $date_create
 * @property string $date_update
 *
 * @property Setting $setting
 * @property User $user
 * @property Workspace $workspace
 */
class Preference extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_preference';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_setting'], 'required'],
            [['id_user', 'id_setting', 'id_workspace'], 'integer'],
            [['value'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['id_setting'], 'exist', 'skipOnError' => true, 'targetClass' => Setting::class, 'targetAttribute' => ['id_setting' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id_user']],
            [['id_workspace'], 'exist', 'skipOnError' => true, 'targetClass' => Workspace::class, 'targetAttribute' => ['id_workspace' => 'id_workspace']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_preference' => 'Id Preference',
            'id_user' => 'Id User',
            'id_setting' => 'Id Setting',
            'id_workspace' => 'Id Workspace',
            'value' => 'Value',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[Setting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Setting::class, ['id' => 'id_setting']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id_user' => 'id_user']);
    }

    /**
     * Gets query for [[Workspace]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['id_workspace' => 'id_workspace']);
    }
}
