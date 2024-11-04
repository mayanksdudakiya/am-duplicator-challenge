<?php

namespace DupChallenge;

use DupChallenge\Utils\DupDb;

/**
 * Install class
 */
class Install
{
    /**
     * Register install hoosk
     *
     * @return void
     */
    public static function register()
    {
        if (is_admin()) {
            register_activation_hook(DUP_CHALLENGE_FILE, array(__CLASS__, 'onActivation'));
        }
    }

    /**
     * Install plugin
     *
     * @return void
     */
    public static function onActivation()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . DupDb::SCAN_TABLE;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            path TEXT NOT NULL,
            type TINYINT(1) NOT NULL,
            parent_directory VARCHAR(256) NULL,
            size BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            nodes BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY  (id),
            INDEX (parent_directory)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        @dbDelta($sql);
    }
}
