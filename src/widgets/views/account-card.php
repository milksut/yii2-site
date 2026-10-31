<?php

use yii\helpers\Html;
use portalium\site\Module;

/* @var $this yii\web\View */
/* @var $id string */
/* @var $name string */
/* @var $email string */
/* @var $initial string */
/* @var $avatar string|null */
/* @var $canEdit bool */
/* @var $canPreferences bool */

$picture = $avatar !== null
    ? Html::img($avatar, ['class' => 'account-avatar', 'alt' => ''])
    : Html::tag('span', Html::encode($initial), ['class' => 'account-avatar', 'aria-hidden' => 'true']);
?>
<div class="account-card dropup" id="<?= Html::encode($id) ?>">
    <button type="button" class="account-card-toggle" data-bs-toggle="dropdown" aria-expanded="false"
            data-bs-popper-config='{"strategy":"fixed"}' title="<?= Html::encode($name) ?>">
        <?= $picture ?>
        <span class="account-card-text">
            <span class="account-card-name"><?= Html::encode($name) ?></span>
            <?php if ($email !== '') : ?>
                <span class="account-card-email"><?= Html::encode($email) ?></span>
            <?php endif; ?>
        </span>
        <svg class="account-card-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 15l5 5 5-5M7 9l5-5 5 5"></path></svg>
    </button>
    <div class="dropdown-menu account-card-menu">
        <?php if ($canEdit) : ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user', 'aria-hidden' => 'true']) . Module::t('Edit Account'), ['/site/profile/edit'], ['class' => 'dropdown-item']) ?>
        <?php endif; ?>
        <?php if ($canPreferences) : ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-sliders', 'aria-hidden' => 'true']) . Module::t('Preferences'), ['/site/preference/index'], ['class' => 'dropdown-item']) ?>
        <?php endif; ?>
        <?php if ($canEdit || $canPreferences) : ?>
            <div class="dropdown-divider"></div>
        <?php endif; ?>
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-sign-out', 'aria-hidden' => 'true']) . Module::t('Logout'), ['/auth/default/logout'], ['class' => 'dropdown-item', 'data-method' => 'post']) ?>
    </div>
</div>
