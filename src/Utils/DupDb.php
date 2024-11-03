<?php

namespace DupChallenge\Utils;

final class DupDb
{
    const SCAN_TABLE = 'dup_scan_logs';

    public static function cleanScanLogTable()
    {
        global $wpdb;
        $scanLogTbl = $wpdb->prefix . self::SCAN_TABLE;

        $wpdb->query("TRUNCATE TABLE `{$scanLogTbl}`");
    }

    public static function insertScanLogsInChunks($dataInArray)
    {
        global $wpdb;

        if ( ! is_array($dataInArray) && ! empty($dataInArray) ) {
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
                    esc_sql($item['owner']),
                    esc_sql($item['size']),
                    esc_sql($item['nodes'])
                );
            }

            $query = "INSERT INTO $scanLogTbl (path, type, owner, size, nodes) VALUES " . implode(", ", $insertValues);
            $wpdb->query($query);
        }
    }
}
