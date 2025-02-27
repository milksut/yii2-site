<?php
use yii\db\Migration;

class m010101_010101_site_rbac extends Migration
{
    public function up()
    {

        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);

        $siteWebSettingIndex = $auth->createPermission('siteWebSettingIndex');
        $siteWebSettingIndex->description = 'Site Web Setting Index';
        $auth->add($siteWebSettingIndex);
        $auth->addChild($admin, $siteWebSettingIndex);

        $siteWebSettingUpdate = $auth->createPermission('siteWebSettingUpdate');
        $siteWebSettingUpdate->description = 'Site Web Setting Update';
        $auth->add($siteWebSettingUpdate);
        $auth->addChild($admin, $siteWebSettingUpdate);

        $siteAPISettingIndex = $auth->createPermission('siteAPISettingIndex');
        $siteAPISettingIndex->description = 'Site API Setting Index';
        $auth->add($siteAPISettingIndex);
        $auth->addChild($admin, $siteAPISettingIndex);

    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $auth->remove($auth->getPermission('siteWebSettingIndex'));
        $auth->remove($auth->getPermission('siteWebSettingUpdate'));

    }
}