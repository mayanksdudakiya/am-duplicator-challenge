<?php

namespace DupChallenge\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileAndDirectoryHelper
{
    /**
     * Get the size of directory
     * @param $directoryPath Provide directory path to get the size
     * @return int
     */
    public static function getDirectorySize($directoryPath)
    {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directoryPath)) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }
}
