<?php

namespace DupChallenge;

use DupChallenge\Utils\DupDb;

/**
 * Uninstall class
 */
class Unistall
{
    /**
     * Register unistall hoosk
     *
     * @return void
     */
    public static function register()
    {
        if (is_admin()) {
            register_deactivation_hook(DUP_CHALLENGE_FILE, array(__CLASS__, 'deactivate'));
        }
    }

    /**
     * Deactivation Hook
     *
     * @return void
     */
    public static function deactivate()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . DupDb::SCAN_TABLE;
        $wpdb->query("DROP TABLE IF EXISTS `{$tableName}`");
    }
}
