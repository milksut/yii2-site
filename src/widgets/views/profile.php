<?php

use portalium\site\bundles\ProfileAsset;
use portalium\site\Module;

ProfileAsset::register($this);

$iconSize = (isset($style['iconSize']) && $style['iconSize'] != '') ? $style['iconSize'] : 20;
$placementStyle = '';
if ($placement == 'top-to-bottom') {
    $placementStyle = 'topToBottom';
    echo '<style>
    .topToBottom {
        display: block;
        flex-direction: column; 
        align-items: center;
    }
    </style>';
} elseif ($placement == 'side-by-side') {
    $placementStyle = 'sideBySide';
    echo '<style>
    .sideBySide .profile {
        flex-direction: row !important;
    }
    .sideBySide .profile img,
    .sideBySide .profile span.photo-label {
        margin-left: 10px;
        margin-top: 0;
    }
    </style>';
}


//var_dump($placement);
//exit;

?>
<ul class="card-box nav dropdown">
    <li class="nav-item dropdown <?= $placementStyle ?>" style="width: 100%;">
        <a href="#" class="nav-link dropdown-toggle profile flex-column" data-bs-toggle="dropdown" data-bs-placement="<?php echo $placement ?> " aria-expanded="false" style="padding-bottom:7px !important; margin-bottom:0px; padding-top:3px !important; display:flex; align-items: center;">
            <?php if ($model !== null) : ?>
                <img src="<?= $filePath ?>" alt="<?= $title ?>" width="<?= $iconSize ?>" height="<?= $iconSize ?>" class="rounded-circle me-2 flex-column" style="margin-top:5px; margin-left: -5px;">
                <span class="photo-label flex-column" style="vertical-align: middle;margin-left: 10px;"><?= $label ?></span>

            <?php else : ?>
                <div class="imagelabel" style="height: 100%; width:100%">
                    <span class="profile-picture initials" style="display: inline-block; vertical-align: middle; "><?= $usernameInitial ?></span>
                    <div style=" padding-top: 5px; display: inline-block; vertical-align: middle;  margin-right:15px ;"><?php echo $first_name  ?></div>
                </div>
            <?php endif; ?>
        </a>
        <ul class="dropdown-menu">
            <li>
                <div class="d-flex flex-column text-center p-3">
                    <?php if ($model !== null) : ?>
                        <img src="<?= $filePath ?>" alt="<?= $title ?>" width="80" height="80" class="rounded-circle mx-auto mb-3">
                    <?php else : ?>
                        <span class="profile-picture initials rounded-circle mx-auto mb-3" style=""><?= $usernameInitial ?></span>
                    <?php endif; ?>
                    <span><?= $username ?></span>
                    <?php if ($first_name !== null || $last_name !== null) : ?>
                        <div class="small"><?= $first_name . " " . $last_name ?></div>
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
            <li><a class="dropdown-item " href="/site/auth/logout" style="text-align: center;border:none !important"><?php echo Module::t('Logout') ?></a></li>
        </ul>
    </li>
</ul>