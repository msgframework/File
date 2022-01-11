<?php

namespace Msgframework\Lib\File;

use MSGFramework\Lib\File\Exception\NoTmpDirFileException;

class TmpFile extends File
{
    /**
     * Create temporary file
     * @param string|null $directory
     * @param string $prefix
     * @param string $content
     */
    public function __construct(?string $directory = null, string $prefix = 'php_tmpfile_', string $content = '')
    {
        $path = tempnam($directory ?? self::getTempDir(), $prefix);

        file_put_contents($path, $content);
        parent::__construct($path);

        register_shutdown_function(function () {
            self::remove();
        });
    }

    public function __destruct()
    {
        self::remove();
    }

    /**
     * Remove temporary file
     * @return void
     */
    public function remove(): void
    {
        if(file_exists($this->getPathname())) {
            unlink($this->getPathname());
        }
    }

    /**
     * Return path to temporary directory based on system temp dir or ENV
     * @return string
     * @throws NoTmpDirFileException
     */
    public function getTempDir(): string
    {
        if (function_exists('sys_get_temp_dir')) {
            return sys_get_temp_dir();
        } elseif (
            ($tmp = getenv('TMP')) ||
            ($tmp = getenv('TEMP')) ||
            ($tmp = getenv('TMPDIR'))
        ) {
            return realpath($tmp);
        } else {
            throw new NoTmpDirFileException('Temporary files directory not specified');
        }
    }
}