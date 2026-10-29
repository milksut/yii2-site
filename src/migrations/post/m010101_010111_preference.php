<?php

use yii\db\Migration;
use portalium\site\Module;
use portalium\user\Module as UserModule;
use portalium\workspace\Module as WorkspaceModule;
class m010101_010111_preference extends Migration
{
    public function up()
    {

        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(Module::$tablePrefix . 'preference', [
            'id_preference' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_setting' => $this->integer()->notNull(),
            'id_workspace'=>$this->integer()->null(),
            'value' => $this->text(),
            'date_create' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'date_update' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
        ],
            $tableOptions
        );

        $this->addForeignKey(
            '{{%fk-' . Module::$tablePrefix . 'preference_id_user}}',
            '{{%' . Module::$tablePrefix . 'preference}}',
            'id_user',
            '{{%' . UserModule::$tablePrefix . 'user}}',
            'id_user',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-' . Module::$tablePrefix . 'preference_id_setting}}',
            '{{%' . Module::$tablePrefix . 'preference}}',
            'id_setting',
            '{{%' . Module::$tablePrefix . 'setting}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-' . Module::$tablePrefix . 'preference_id_workspace}}',
            '{{%' . Module::$tablePrefix . 'preference}}',
            'id_workspace',
            '{{%' . WorkspaceModule::$tablePrefix . 'workspace}}',
            'id_workspace',
            'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%' . Module::$tablePrefix . 'preference}}');
    }

}