<?php

namespace Msgframework\Lib\File;

class TmpFile extends File
{
    public function __construct(string $path)
    {
        parent::__construct($path, false);

        register_shutdown_function(function () {
            unlink($this->getPath());
        });
    }
}