<?php

namespace DupChallenge\Actions;

use Exception;

class BackupScan
{
    private static $instance = null;

    private function __construct()
    {
        add_action('wp_ajax_duplicator_scan_directories_and_files', [$this, 'scanDirectoriesAndFiles']);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function scanDirectoriesAndFiles()
    {
        try {
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'duplicator_scan_directories_and_files')) {
                throw new Exception('Invalid request');
            }

            wp_send_json_success(['message' => 'test']);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
}
