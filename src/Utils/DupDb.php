<?php

namespace DupChallenge\Utils;

final class DupDb
{
    const SCAN_TABLE = 'dup_scan_logs';

    /**
     * Remove records from scan log table
     * @return void
     */
    public static function cleanScanLogTable()
    {
        global $wpdb;
        $scanLogTbl = $wpdb->prefix . self::SCAN_TABLE;

        $wpdb->query("TRUNCATE TABLE `{$scanLogTbl}`");
    }

    /**
     * create scan logs in chunks
     * @param array $dataInArray Provide data in array
     * @return void
     */
    public static function insertScanLogsInChunks($dataInArray)
    {
        global $wpdb;

        if (! is_array($dataInArray) && ! empty($dataInArray)) {
            return;
        }

        $scanLogTbl = $wpdb->prefix . self::SCAN_TABLE;
        $batchSize = 100;

        $chunks = array_chunk($dataInArray, $batchSize);

        foreach ($chunks as $batch) {
            $insertValues = [];

            foreach ($batch as $item) {
                $insertValues[] = sprintf(
                    "('%s', %s, '%s', '%d', %d)",
                    esc_sql($item['path']),
                    esc_sql($item['type']),
                    esc_sql($item['parent_directory']),
                    esc_sql($item['size']),
                    esc_sql($item['nodes'])
                );
            }

            $query = "INSERT INTO $scanLogTbl (path, type, parent_directory, size, nodes) VALUES " . implode(", ", $insertValues);
            $wpdb->query($query);
        }
    }
}
