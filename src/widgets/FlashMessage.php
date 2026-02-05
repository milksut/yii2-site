<?php

namespace portalium\site\widgets;

use Yii;

use portalium\bootstrap5\Alert;
use portalium\bootstrap5\Widget;

class FlashMessage extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public $autoDismiss = true;

    public $dismissDuration = 5000;

    public function init()
    {
        parent::init();
        Yii::$app->view->registerJs('if ($.pjax) $.pjax.defaults.timeout = 30000;');
        if ($this->autoDismiss) {

            $this->view->registerJs('
                $(".alert").each(function() {
                    setTimeout(function() {
                        $(this).alert("close");
                    }.bind(this),' .  $this->dismissDuration . ');
                });
            ');
        }


        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    $this->options['class'] = $this->alertTypes[$type] . $appendCss;
                    $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;

                    echo Alert::widget([
                        'body' => $message,
                        'closeButton' => $this->closeButton,
                        'options' => $this->options,
                    ]);
                }

                $session->removeFlash($type);
            }
        }
    }
}
