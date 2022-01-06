<?php

namespace Msgframework\Lib\File;

class TmpFile extends File
{
    public function __construct($filesystem, ?string $filename = null)
    {
        if(!$filename) $filename = uniqid('tmp_');

        $path = FILES_DIR . "/tmp/{$filename}";

        parent::__construct($path);

        register_shutdown_function(function () use ($filesystem) {
            if ($filesystem->fileExists($this->getLocalPath())) {
                $filesystem->delete($this->getLocalPath());
            }
        });
    }
}