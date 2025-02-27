<?php

    namespace portalium\site\widgets;

    use Yii;
    use yii\base\Widget;
    use yii\helpers\Html;
    use portalium\storage\models\Storage;
    use portalium\user\models\User;
    use portalium\menu\models\MenuItem;
    use portalium\site\Module;

    class Profile extends Widget
    {
        public $display;
        public $style;
        public $label;
        public $options;
        public $placement;

        public function init()
        {
            parent::init();
            $this->style = '{"icon":"","color":"","iconSize":"","display":"3","childDisplay":"1", "placement":"default"}';
            $this->style = json_decode($this->style, true);

            $this->options['class'] = 'placementWidget';
            if($this->placement == 'top-to-bottom'){
                $this->options['data-bs-placement'] = $this->placement;
                $this->registerCss();
            }if($this->placement == 'side-by-side'){
                $this->registerCss();
            }



        }

        public function run()
        {

            $label = $this->generateLabel("Profile");
            if (Yii::$app->user->isGuest == true) {
                return false;
            } else {
                $user = User::findOne(Yii::$app->user->id);
                $username = $user->username;
                $last_name = $user->last_name;
                $first_name = $user->first_name;
                $id_avatar = $user->id_avatar;
                $storage = new Storage();
                $model = $storage->find()->andWhere(['id_storage' => $id_avatar])->one();
                $title = "";
                $usernameInitial = '';
                $filePath = '';
                if ($model !== null) {
                    $filename = $model->name;
                    if ($filename !== null && $filename !== '') {
                        $filePath = Yii::getAlias('@web/data/' . $filename);
                        $title = $model->title;
                    }
                } else {
                    $usernameInitial = mb_substr($username, 0, 1, 'UTF-8');
                    $title = $username;
                }

                return $this->render('./profile', [
                    'username' => $username,
                    'last_name' => $last_name,
                    'first_name' => $first_name,
                    'id_avatar' => $id_avatar,
                    'model' => $model,
                    'title' => $title,
                    'usernameInitial' => $usernameInitial,
                    'filePath' => $filePath,
                    'style' => $this->style,
                    'label' => $label,
                    'placement'=>$this->placement,
                    'options' =>$this->options,
                ]);
            }
        }


        private function generateLabel($text)
        {
            $label = isset($this->style['display']) && $this->style['display'] === MenuItem::TYPE_DISPLAY['icon-text']
                ? ""
                : Module::t($text);

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