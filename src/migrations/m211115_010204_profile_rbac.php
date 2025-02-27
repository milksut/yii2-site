<?php

use yii\db\Migration;
use portalium\site\rbac\OwnRule;
use yii\rbac\Rule;

class m211115_010204_profile_rbac extends Migration
{
    public function up()
    {
        $auth = \Yii::$app->authManager;

        $rule = new OwnRule();
        $auth->getRule('siteOwnRule');
        $role = \Yii::$app->setting->getValue('site::admin_role');
        $admin = (isset($role) && $role != '') ? $auth->getRole($role) : $auth->getRole('admin');

       
        $siteApiProfileEdit = $auth->createPermission('siteApiProfileEdit');
        $siteApiProfileEdit->description = 'Site Api Profile Edit';
        $auth->add($siteApiProfileEdit);
        $auth->addChild($admin, $siteApiProfileEdit);


        $siteApiProfileEditPassword = $auth->createPermission('siteApiProfileEditPassword');
        $siteApiProfileEditPassword->description = 'Site Api Profile Change Password';
        $auth->add($siteApiProfileEditPassword);
        $auth->addChild($admin, $siteApiProfileEditPassword);


        $siteWebProfiletEdit = $auth->createPermission('siteWebProfileEdit');
        $siteWebProfiletEdit->description = 'Site Web Profile Edit';
        $auth->add($siteWebProfiletEdit);
        $auth->addChild($admin,  $siteWebProfiletEdit);


        $siteWebProfileEditPassword = $auth->createPermission('siteWebProfileEditPassword');
        $siteWebProfileEditPassword->description = 'Site Web Profile Change Password';
        $auth->add($siteWebProfileEditPassword);
        $auth->addChild($admin, $siteWebProfileEditPassword);


        $siteApiProfileEditOwn = $auth->createPermission('siteApiProfileEditOwn');
        $siteApiProfileEditOwn->description = 'Site Api Profile EditOwn';
        $siteApiProfileEditOwn->ruleName = $rule->name;
        $auth->add($siteApiProfileEditOwn);
        $auth->addChild($admin, $siteApiProfileEditOwn);
        $siteApiProfileEdit = $auth->getPermission('siteApiProfileEdit');
        $auth->addChild($siteApiProfileEditOwn, $siteApiProfileEdit);



        $siteApiProfileEditPasswordOwn = $auth->createPermission('siteApiProfileEditPasswordOwn');
        $siteApiProfileEditPasswordOwn->description = 'Site Api Profile Edit PasswordOwn';
        $siteApiProfileEditPasswordOwn->ruleName = $rule->name;
        $auth->add($siteApiProfileEditPasswordOwn);
        $auth->addChild($admin, $siteApiProfileEditPasswordOwn);
        $siteApiProfileEditPassword = $auth->getPermission('siteApiProfileEditPassword');
        $auth->addChild($siteApiProfileEditPasswordOwn, $siteApiProfileEditPassword);


        $siteWebProfiletEditOwn = $auth->createPermission('siteWebProfileEditOwn');
        $siteWebProfiletEditOwn->description = 'Site Web Profile EditOwn';
        $siteWebProfiletEditOwn->ruleName = $rule->name;
        $auth->add($siteWebProfiletEditOwn);
        $auth->addChild($admin,  $siteWebProfiletEditOwn);
        $siteWebProfileEdit = $auth->getPermission('siteWebProfileEdit');
        $auth->addChild($siteWebProfiletEditOwn, $siteWebProfileEdit);


        $siteWebProfileEditPasswordOwn = $auth->createPermission('siteWebProfileEditPasswordOwn');
        $siteWebProfileEditPasswordOwn->description = 'Site Web Profile Edit PasswordOwn';
        $siteWebProfileEditPasswordOwn->ruleName = $rule->name;
        $auth->add($siteWebProfileEditPasswordOwn);
        $auth->addChild($admin, $siteWebProfileEditPasswordOwn);
        $siteWebProfileEditPassword = $auth->getPermission('siteWebProfileEditPassword');
        $auth->addChild($siteWebProfileEditPasswordOwn, $siteWebProfileEditPassword);


        $siteWebProfileRegenerateToken = $auth->createPermission('siteWebProfileRegenerateToken');
        $siteWebProfileRegenerateToken->description = 'Site Web Profile Regenerate Token';
        $auth->add($siteWebProfileRegenerateToken);
        $auth->addChild($admin, $siteWebProfileRegenerateToken);

    }
    public function down()
    {
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('siteApiProfileEditPassword'));
        $auth->remove($auth->getPermission('siteApiProfileEdit'));
        $auth->remove($auth->getPermission('siteWebProfileEditPassword'));
        $auth->remove($auth->getPermission('siteWebProfileEdit'));
        $auth->remove($auth->getPermission('siteWebProfileRegenerateToken'));

        $auth->remove($auth->getPermission('siteOwnApiProfileEditPassword'));
        $auth->remove($auth->getPermission('siteOwnApiProfileEdit'));
        $auth->remove($auth->getPermission('siteOwnWebProfileEditPassword'));
        $auth->remove($auth->getPermission('siteOwnWebProfileEdit'));
    }
}
