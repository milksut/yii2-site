<?php

namespace portalium\site;

use Yii;
use portalium\base\Event;
use portalium\user\Module as UserModule;
use portalium\site\components\TriggerActions;
use portalium\site\components\SettingActions;
use portalium\site\models\Setting;

class Module extends \portalium\base\Module
{
    const EVENT_ON_LOGIN = 'siteAfterLogin';
    const EVENT_ON_SIGNUP = 'siteAfterSignup';

    const EVENT_SETTING_UPDATE = 'siteSettingUpdate';

    public static $description = 'Site Management Module';
    public static $name = 'Site';
    public $apiRules = [
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => [
                'site/auth',
                'site/setting',
                'site/preference',
            ]
        ],
    ];

    public static $tablePrefix = 'site_';

    private static $settingTableExists = [];

    public function getMenuItems()
    {
        $menuItems = [
            [
                [
                    'menu' => 'web',
                    'type' => 'widget',
                    'label' => 'portalium\site\widgets\LoginButton',
                    'name' => 'Login',
                ],
                [
                    'menu' => 'web',
                    'type' => 'widget',
                    'label' => 'portalium\site\widgets\Language',
                    'name' => 'Language',
                ],
                [
                    'menu' => 'web',
                    'type' => 'action',
                    'route' => '/site/setting/index',
                ],
                [
                    'menu' => 'web',
                    'type' => 'action',
                    'route' => '/site/preference/index',
                ],
                [
                    'menu' => 'web',
                    'type' => 'widget',
                    'label' => 'portalium\site\widgets\Profile',
                    'name' => 'Profile',
                ],
                [
                    'menu' => 'web',
                    'type' => 'action',
                    'route' => '/site/profile/edit',
                ]
            ],
        ];
        return $menuItems;
    }

    public static function moduleInit()
    {
        $settings = [];
        $connection = spl_object_id(Yii::$app->db);
        if (empty(self::$settingTableExists[$connection])) {
            self::$settingTableExists[$connection] = Yii::$app->db->getTableSchema(Setting::tableName(), true) !== null;
        }
        if (self::$settingTableExists[$connection]) {
            $settings = Setting::find()
                ->where(['name' => ['app::language', 'site::timezone']])
                ->indexBy('name')
                ->all();
        }

        if (!Yii::$app instanceof \yii\console\Application) {
            $lang = Yii::$app->session->get('lang');
            if (!$lang && isset($settings['app::language'])) {
                $lang = $settings['app::language']->value;
            }
            if ($lang) {
                Yii::$app->language = $lang;
            }
        }

        self::registerTranslation('site', '@portalium/site/messages', [
            'site' => 'site.php',
        ]);

        if (isset($settings['site::timezone']) && $settings['site::timezone']->value) {
            $timezone = $settings['site::timezone']->value;
            Yii::$app->timeZone = $timezone;
            Yii::$app->formatter->defaultTimeZone = $timezone;
        }
    }

    public function registerComponents()
    {
        return [
            'setting' => [
                'class' => 'portalium\site\components\Setting',
            ],
            'site' => [
                'class' => 'portalium\site\components\Site',
            ],
        ];
    }

    public static function t($message, array $params = [])
    {
        return parent::coreT('site', $message, $params);
    }

    public static function settingT($category, $message, array $params = [])
    {
        self::registerTranslation($category, '@portalium/' . $category . '/messages', [
            $category => $category . '.php',
        ]);

        return parent::coreT($category, $message, $params);
    }

    public function registerEvents()
    {
        Event::on($this::className(), UserModule::EVENT_USER_CREATE, [new TriggerActions(), 'onUserCreateBefore']);
        Event::on($this::className(), self::EVENT_SETTING_UPDATE, [new SettingActions(), 'changedSetting']);
    }
}
