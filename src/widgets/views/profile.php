<?php

use portalium\menu\models\MenuItem;
use portalium\site\bundles\ProfileAsset;
use portalium\site\Module;
use yii\helpers\Html;

ProfileAsset::register($this);

$iconSize = (isset($style['iconSize']) && $style['iconSize'] != '') ? $style['iconSize'] : 20;
$placementStyle='';
if ($placement == 'top-to-bottom') {
    $placementStyle = 'topToBottom';
} elseif ($placement == 'side-by-side') {
    $placementStyle = 'sideBySide';
}


//var_dump($placement);
//exit;

?>
<ul class="card-box nav dropdown" > 
<li class="nav-item dropdown <?= $placementStyle ?>">
    <a href="#" class="nav-link dropdown-toggle profile flex-column" data-bs-toggle="dropdown" data-bs-placement="<?php echo $placement?> " aria-expanded="false">
        <?php if ($model !== null) : ?>
            <img src="<?= Html::encode($filePath) ?>" alt="<?= Html::encode($title) ?>" width="<?= (int) $iconSize ?>" height="<?= (int) $iconSize ?>" class="rounded-circle flex-column">
            <span class="photo-label flex-column"><?= $label ?></span>

        <?php else : ?>
            <span class="ımagelabel">
                <span class="profile-picture initials"><?= Html::encode($usernameInitial) ?></span>
                <?php if ($first_name !== null && $first_name !== '') : ?>
                    <span class="profile-name"><?= Html::encode($first_name) ?></span>
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </a>
    <ul class="dropdown-menu">
        <li>
            <div class="d-flex flex-column text-center p-3">
                <?php if ($model !== null) : ?>
                    <img src="<?= Html::encode($filePath) ?>" alt="<?= Html::encode($title) ?>" width="80" height="80" class="rounded-circle mx-auto mb-3">
                <?php else : ?>
                    <span class="profile-picture initials rounded-circle mx-auto mb-3" style=""><?= Html::encode($usernameInitial) ?></span>
                <?php endif; ?>
                <span><?= Html::encode($username) ?></span>
                <?php if ($first_name !== null || $last_name !== null) : ?>
                    <div class="small"><?= Html::encode($first_name . " " . $last_name) ?></div>
                <?php endif; ?>
            </div>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="/site/profile/edit" style="text-align: center; border:none !important"><?php echo Module::t('Edit Account') ?></a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
           <li>
            <?= yii\helpers\Html::a(
                Module::t('Logout'), 
                ['/auth/default/logout'], 
                [
                    'class' => 'dropdown-item',
                    'data-method' => 'post',
                    'style' => 'text-align: center; border:none !important'
                ]
            ) ?>
        </li>
    </ul>
</li>
</ul>