<?php

namespace portalium\site\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use portalium\site\Module;
use portalium\theme\widgets\Nav;
use portalium\menu\models\MenuItem;

class Language extends Widget
{
    public $options;

    public $icon;
    public $style;
    public $display;

    public $placement;

    public function init()
    {
        if (!$this->icon) {
        }

        $this->options['class'] = 'placementWidget';
        if ($this->placement == 'top-to-bottom') {
            $this->options['data-bs-placement'] = $this->placement;
            $this->registerCss();
        }
        if ($this->placement == 'side-by-side') {
            $this->registerCss();
        }



        parent::init();
    }

    public function run()
    {
        $languages = Yii::$app->setting->getConfig('app::language');
        $langItems = [];
        $active = Yii::$app->language;
        foreach ($languages as $key => $value) {
            $langItems[] = [
                'label' => Module::t($value),
                'url' => ['/site/home/lang', 'lang' => $key],
                'active' => $active == $key,
            ];
        }

        $menuItems[] = [
            'label' => $this->generateLabel("Language"),
            'url' => ['/site/home/lang', 'lang' => Yii::$app->language],
            'items' => $langItems,
            'display' => $this->display,
            'placement' => $this->placement,
        ];
        return Nav::widget([
            'options' => $this->options,
            'items' => $menuItems,
        ]);
    }

    private function generateLabel($text)
    {
        $label = "";
        if (isset($this->display)) {
            switch ($this->display) {
                case MenuItem::TYPE_DISPLAY['icon']:
                    $label = $this->icon;
                    break;
                case MenuItem::TYPE_DISPLAY['icon-text']:
                    $label = $this->icon . Module::t($text);
                    break;
                case MenuItem::TYPE_DISPLAY['text']:
                    $label = Module::t($text);
                    break;
                default:
                    $label = $this->icon . Module::t($text);
                    break;
            }
        } else {
            $label = $this->icon . Module::t($text);
        }

        return $label;
    }

    private function registerCss()
    {
        $css = <<<CSS
    .placementWidget[data-bs-placement="side-by-side"] {
    }
    .placementWidget[data-bs-placement="top-to-bottom"] li a i {
     display: block;
     flex-direction: column; 
     align-items: center;
    }
    CSS;
        $this->getView()->registerCss($css);
    }
}
