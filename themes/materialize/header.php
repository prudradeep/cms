<?php
/*
*Author:    Pradeep Rajput
*Email:     prithviraj.rudraksh@gmail.com 
*Website:   ----------
*Theme:     Materialize
*View:      Header
*/

if (! defined('PHINDART')) { die('Access denied'); }
?>
<!DOCTYPE html>
<html lang="<?= self::getSiteInfo('language') ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?= self::getSiteInfo('description') ?>">
    <meta name="keywords" content="<?= self::getSiteInfo('keys') ?>">
    <title><?= self::getSiteInfo('title') ?></title>

    <!-- Materialize -->
    <link href="<?=BASE.THEME_PATH ?>/materialize/css/materialize.min.css" rel="stylesheet" />
    <link href="<?=BASE.THEME_PATH ?>/materialize/css/phindart.css" rel="stylesheet" />
    <?= self::getCssPlugs() ?>
    <?= self::getFontPlugs() ?>
    <?= self::getJsPlugs() ?>
    <script src="<?=BASE.THEME_PATH ?>/materialize/js/materialize.min.js"></script>
  </head>
  <body>