<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    static function () {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Cundd\\Fleet\\Command\\InfoCommandController';
    }
);
