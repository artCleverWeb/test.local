<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $APPLICATION
 */
?>
<!doctype html>
<html lang="ru">
<head>
    <title><?= $APPLICATION->ShowTitle(); ?></title>
    <meta charset="UTF-8">
    <?php
    $APPLICATION->ShowMeta("robots");
    $APPLICATION->ShowHeadStrings();
    $APPLICATION->ShowHeadScripts();
    $APPLICATION->ShowCSS();
    ?>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>
<header>
    <div class="header__panel">
        <ul class="language__panel">
            <li><a href="/?lang=ru">RU</a></li>
            <li><a href="/?lang=en">EN</a></li>
        </ul>
    </div>
</header>
