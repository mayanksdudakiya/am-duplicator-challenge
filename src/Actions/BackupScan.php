<?php

namespace DupChallenge\Actions;

use DupChallenge\Helpers\FileAndDirectoryHelper;
use DupChallenge\Utils\DupDb;
use Exception;

final class BackupScan
{
    private static $instance = null;

    private $scannedResult = [];

    /**
     * Class constructor
     *
     * @return void
     */
    private function __construct()
    {
        add_action('wp_ajax_duplicator_scan_directories_and_files', [$this, 'scanDirectoriesAndFiles']);
    }

    /**
     * Get the singleton instance of the BackupScan class.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Scan directories and files via AJAX.
     *
     * This function verifies the nonce and sends a JSON response
     * with the result of the scan operation.
     *
     * @return void
     */
    public function scanDirectoriesAndFiles()
    {
        try {
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'duplicator_scan_directories_and_files')) {
                throw new Exception('Invalid request');
            }

            DupDb::cleanScanLogTable();
            $scanDir = $this->scanner();
            DupDb::insertScanLogsInChunks($scanDir);

            wp_send_json_success(['message' => $scanDir]);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
    * Scan directory by provided path
    * @param string $path Provide directory path to scan
    * @return array
    */
    private function scanner($path = ABSPATH, $owner = null)
    {
        try {
            if (!file_exists($path)) {
                throw new Exception('Invalid directory or file');
            }

            $scannedResult = scandir($path);

            foreach ($scannedResult as $fileOrDirectory) {
                if ($fileOrDirectory === '.' || $fileOrDirectory === '..') {
                    continue;
                }

                $newPath = trailingslashit($path)  . $fileOrDirectory;

                if (is_dir($newPath)) {
                    $this->scannedResult[] = [
                        'type' => FileAndDirectoryHelper::TYPE_DIRECTORY,
                        'name' => $fileOrDirectory,
                        'size' => FileAndDirectoryHelper::getDirectorySize($newPath),
                        'path' => $newPath,
                        'owner' => $owner,
                        'nodes' => $this->nodeCounts($newPath)
                    ];

                    $this->scanner($newPath, $fileOrDirectory);
                } else {
                    $this->scannedResult[] = [
                        'type' => FileAndDirectoryHelper::TYPE_FILE,
                        'name' => $fileOrDirectory,
                        'size' => filesize($newPath),
                        'path' => $newPath,
                        'owner' => $owner,
                        'nodes' => 1,
                    ];
                }
            }

            return $this->scannedResult;
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    public function nodeCounts($directory)
    {
        $count = 0;
        $contents = scandir($directory);

        foreach ($contents as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;

            $count++;

            if (is_dir($path)) {
                $count += $this->nodeCounts($path);
            }
        }

        return $count;
    }
}
