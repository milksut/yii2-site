<?php

use yii\db\Migration;

class m261014_000010_site_user_own_permissions extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $user = $auth->getRole('user');
        if ($user === null) {
            return true;
        }
        $permissionsName = [
            'siteWebProfileRegenerateTokenOwn',
            'siteApiProfileEditOwn',
            'siteApiProfileEditPasswordOwn',
            'siteWebPreferenceIndexOwn',
            'siteWebPreferenceUpdateOwn',
        ];
        foreach ($permissionsName as $permissionName) {
            $permission = $auth->getPermission($permissionName);
            if ($permission !== null && $permission->ruleName !== null && !$auth->hasChild($user, $permission)) {
                $auth->addChild($user, $permission);
            }
        }
        $regenerateToken = $auth->getPermission('siteWebProfileRegenerateToken');
        $regenerateTokenOwn = $auth->getPermission('siteWebProfileRegenerateTokenOwn');
        $ownLinked = $regenerateTokenOwn !== null && $auth->hasChild($user, $regenerateTokenOwn);
        if ($ownLinked && $regenerateToken !== null && $auth->hasChild($user, $regenerateToken)) {
            $auth->removeChild($user, $regenerateToken);
        }
        return true;
    }

    public function safeDown()
    {
        return true;
    }
}
