<?php

use yii\db\Migration;
use portalium\site\rbac\OwnRule;


class m211115_010203_preference_rbac extends Migration
{
    public function up()
    {
        $auth = \Yii::$app->authManager;

        $rule = new OwnRule();
        $auth->add($rule);
        $role = \Yii::$app->setting->getValue('site::admin_role');
        $admin = (isset($role) && $role != '') ? $auth->getRole($role) : $auth->getRole('admin');

        $siteWebPreferenceIndex = $auth->createPermission('siteWebPreferenceIndex');
        $siteWebPreferenceIndex->description = 'Site Web Preference Index';
        $auth->add($siteWebPreferenceIndex);
        $auth->addChild($admin, $siteWebPreferenceIndex);

        $siteWebPreferenceUpdate = $auth->createPermission('siteWebPreferenceUpdate');
        $siteWebPreferenceUpdate->description = 'Site Web Preference Update';
        $auth->add($siteWebPreferenceUpdate);
        $auth->addChild($admin, $siteWebPreferenceUpdate);

        $siteApiPreferenceIndex = $auth->createPermission('siteApiPreferenceIndex');
        $siteApiPreferenceIndex->description = 'Site Api Preference Index';
        $auth->add($siteApiPreferenceIndex);
        $auth->addChild($admin, $siteApiPreferenceIndex);

        $siteApiPreferenceUpdate = $auth->createPermission('siteApiPreferenceUpdate');
        $siteApiPreferenceUpdate->description = 'Site Api Preference Update';
        $auth->add($siteApiPreferenceUpdate);
        $auth->addChild($admin, $siteApiPreferenceUpdate);

        $siteWebPreferenceIndexOwn = $auth->createPermission('siteWebPreferenceIndexOwn');
        $siteWebPreferenceIndexOwn->description = 'Site Web PreferenceIndexOwn';
        $siteWebPreferenceIndexOwn->ruleName = $rule->name;
        $auth->add($siteWebPreferenceIndexOwn);
        $auth->addChild($admin, $siteWebPreferenceIndexOwn);
        $siteWebPreferenceIndex = $auth->getPermission('siteWebPreferenceIndex');
        $auth->addChild($siteWebPreferenceIndexOwn, $siteWebPreferenceIndex);

        $siteWebPreferenceUpdateOwn = $auth->createPermission('siteWebPreferenceUpdateOwn');
        $siteWebPreferenceUpdateOwn->description = 'Site Web PreferenceUpdateOwn';
        $siteWebPreferenceUpdateOwn->ruleName = $rule->name;
        $auth->add($siteWebPreferenceUpdateOwn);
        $auth->addChild($admin, $siteWebPreferenceUpdateOwn);
        $siteWebPreferenceUpdate = $auth->getPermission('siteWebPreferenceUpdate');
        $auth->addChild($siteWebPreferenceUpdateOwn, $siteWebPreferenceUpdate);

        $siteApiPreferenceUpdateOwn = $auth->createPermission('siteApiPreferenceUpdateOwn');
        $siteApiPreferenceUpdateOwn->description = 'Site Api PreferenceUpdateOwn';
        $siteApiPreferenceUpdateOwn->ruleName = $rule->name;
        $auth->add($siteApiPreferenceUpdateOwn);
        $auth->addChild($admin, $siteApiPreferenceUpdateOwn);
        $siteApiPreferenceUpdate = $auth->getPermission('siteApiPreferenceUpdate');
        $auth->addChild($siteApiPreferenceUpdateOwn, $siteApiPreferenceUpdate);

        $siteApiPreferenceIndexOwn = $auth->createPermission('siteApiPreferenceIndexOwn');
        $siteApiPreferenceIndexOwn->description = 'Site Api PreferenceIndexOwn';
        $auth->add($siteApiPreferenceIndexOwn);
        $auth->addChild($admin, $siteApiPreferenceIndexOwn);


    }
    public function down()
    {
        $auth->remove($auth->getPermission('siteWebPreferenceIndex'));
        $auth->remove($auth->getPermission('siteWebPreferenceUpdate'));

        $auth->remove($auth->getPermission('siteApiPreferenceIndex'));
        $auth->remove($auth->getPermission('siteApiPreferenceUpdate'));

        $auth->remove($auth->getPermission('siteOwnWebPreferenceIndex'));
        $auth->remove($auth->getPermission('siteOwnWebPreferenceUpdate'));

        $auth->remove($auth->getPermission('siteOwnApiPreferenceIndex'));
        $auth->remove($auth->getPermission('siteOwnApiPreferenceUpdate'));
    }
}