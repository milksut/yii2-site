<?php

namespace portalium\site\widgets;

use Yii;
use yii\base\Widget;
use yii\web\YiiAsset;
use portalium\user\models\User;
use portalium\storage\models\Storage;
use portalium\site\bundles\AccountCardAsset;

/**
 * Account card at the bottom of the side menu: the signed-in user, and a menu to edit the
 * account, open preferences or sign out.
 */
class AccountCard extends Widget
{
    public function run()
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }
        $user = User::findOne(Yii::$app->user->id);
        if ($user === null) {
            return '';
        }

        YiiAsset::register($this->getView());
        AccountCardAsset::register($this->getView());

        return $this->render('account-card', [
            'id' => $this->getId(),
            'name' => self::displayName($user),
            'email' => (string) $user->email,
            'initial' => self::initial($user),
            'avatar' => $this->avatarUrl($user),
            'canEdit' => Yii::$app->user->can('siteWebProfileEdit'),
            'canPreferences' => Yii::$app->user->can('siteWebPreferenceIndex'),
        ]);
    }

    /**
     * First and last name, or the username when both are empty.
     */
    public static function displayName(User $user): string
    {
        $name = trim(trim((string) $user->first_name) . ' ' . trim((string) $user->last_name));

        return $name !== '' ? $name : (string) $user->username;
    }

    /**
     * One capital letter for the round avatar when the user has no picture.
     */
    public static function initial(User $user): string
    {
        $letter = mb_substr(trim(self::displayName($user)), 0, 1, 'UTF-8');

        return $letter !== '' ? mb_strtoupper($letter, 'UTF-8') : '?';
    }

    /**
     * Address of the profile picture, or null when there is none.
     */
    protected function avatarUrl(User $user): ?string
    {
        if (empty($user->id_avatar)) {
            return null;
        }
        $file = Storage::findOne(['id_storage' => $user->id_avatar]);
        if ($file === null || (string) $file->name === '') {
            return null;
        }

        return $file->getFileUrl();
    }
}
