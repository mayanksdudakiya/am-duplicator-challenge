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
}
