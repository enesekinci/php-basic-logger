<?php

namespace EnesEkinci\PhpBasicLogger;

use EnesEkinci\PhpBasicLogger\Exception\CreateLogDirectoryError;
use EnesEkinci\PhpBasicLogger\Exception\CreateLogFileError;
use EnesEkinci\PhpBasicLogger\Exception\NotFoundDirectory;
use EnesEkinci\PhpBasicLogger\Exception\WriteLogFileError;

final class Log
{
    private static ?string $file = null;

    public static function setPath(string $path)
    {
        self::$file = $path;
        if (!file_exists($path)) {
            $f_create = @mkdir($path, 0755, true);
            if (!$f_create)
                throw new CreateLogDirectoryError('Failed to create log path =>' . $path);
        }
        self::$file .=  '/log-' . date('Y-m-d') . '.txt';
    }

    private static function getPath()
    {
        $path = rtrim(self::$file, '/');
        if (is_null($path)) {
            throw new NotFoundDirectory('specify a directory');
        }
        return rtrim(self::$file, '/');
    }

    private static function getLog()
    {
        if (false === file_exists(self::$file)) {
            $f_open = @fopen(self::$file, 'w');
            if (!$f_open)
                throw new CreateLogFileError('Failed to create log file =>' . self::$file);
        }

        $content = file_get_contents(self::$file);
        return $content;
    }

    public static function add($data)
    {
        $log = self::getLog();
        $line = date('Y-m-d H:i:s');
        $line .= ' - ';
        $line .= json_encode($data);
        $log .= PHP_EOL;
        $log .= $line;
        self::set($log);
        return true;
    }

    private static function set($data)
    {
        $log = @fopen(self::$file, 'w');

        if (!$log)
            throw new CreateLogFileError('Unable to open file! =>' . self::$file);

        if (!is_writeable(self::$file))
            throw new WriteLogFileError('Unable to read file, check file permissions! =>' . self::$file);

        fwrite($log, $data);
        fclose($log);

        return true;
    }
}
