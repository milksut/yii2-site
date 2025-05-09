<?php

namespace portalium\site\controllers\web;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use portalium\web\Controller as WebController;
use portalium\site\Module;
use portalium\site\models\Setting;
use portalium\site\models\ContactForm;
use portalium\content\models\Category;

class HomeController extends WebController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['except'] = ['index', 'error', 'about', 'contact', 'lang', 'privacy'];
        return $behaviors;
    }

    public function actionIndex()
    {
        $content = "";

        if (Yii::$app->hasModule('content')) {
            $content = \portalium\content\models\Content::find()->where(['id_content' => Yii::$app->setting->getValue('page::home')])->one();
            if ($content) {
                return $this->redirect(['/content/default/show', 'id' => $content->id_content]);
            } else {
                if (Yii::$app->user->isGuest) {
                    return $this->redirect('site/auth/login');
                } else {
                    $content = "<h1>" . Module::t('No content') . "</h1>";
                }
            }
        } else {
            $content = "<div class=\"site-index\">
                            <div class=\"jumbotron\">
                                <h1>" . Module::t('Portalium Home') . "</h1>
                            </div>
                        </div>";
        }
        return $this->render('index',['content' => $content]);
    }

    public function actionAbout()
    {
        if (Yii::$app->setting->getValue('page::about'))
            return $this->render('about');
        return $this->goHome();
    }

    public function actionError()
    {
        $message = Yii::$app->errorHandler->exception->getMessage();
        $name = Yii::$app->errorHandler->exception->getName();
        return $this->render('error', ['message' => $message, 'name' => $name]);
    }

    public function actionContact()
    {
        if (Yii::$app->setting->getValue('form::contact')) {
            $model = new ContactForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail(Yii::$app->setting->getValue('email::address'))) {
                    Yii::$app->session->addFlash('success', Module::t('Thank you for contacting us. We will respond to you as soon as possible.'));
                } else {
                    Yii::$app->session->addFlash('error', Module::t('There was an error sending your message.'));
                }

                return $this->refresh();
            } else {
                return $this->render('contact', [
                    'model' => $model,
                ]);
            }
        }

        return $this->goHome();
    }

    public function actionLang($lang)
    {
        Yii::$app->session->set('lang', $lang);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPrivacy()
    {
        $content = "";
        $content_category = Category::find()->where(['slug' => "privacy"])->one();
        if (Yii::$app->hasModule('content') && $content_category) {
            $content = \portalium\content\models\Content::find()->where(['id_category' => $content_category->id_category])->one();
            if ($content) {
                $content = $content->body;
            } else {
                $content = "<h1>" . Module::t('No content') . "</h1>";
            }
        } else {
            $content = "<div class=\"site-index\">
                            <div class=\"jumbotron\">
                                <h1>" . Module::t('No Content') . "</h1>
                            </div>
                        </div>";
        }
        return $this->render('index',['content' => $content]);
    }
}
