# The File library
[![Latest Stable Version](http://poser.pugx.org/msgframework/file/v)](https://packagist.org/packages/msgframework/file)
[![Total Downloads](http://poser.pugx.org/msgframework/file/downloads)](https://packagist.org/packages/msgframework/file)
[![Latest Unstable Version](http://poser.pugx.org/msgframework/file/v/unstable)](https://packagist.org/packages/msgframework/file)
[![License](http://poser.pugx.org/msgframework/file/license)](https://packagist.org/packages/msgframework/file)
[![PHP Version Require](http://poser.pugx.org/msgframework/file/require/php)](https://packagist.org/packages/msgframework/file)

## About
This library provides utilities for the file.

## Usage

### Create file
``` php
...
use Msgframework\Lib\File\File;

$file = new File($path);
```

### Get file extension
``` php
...
$file = new File($path);
$allow_extensions = array('jpg', 'png');

if (!in_array($extension = $file->getExtension(), $allow_extensions)) {
    throw new \RuntimeException(sprintf('File extension "%s" not allowed, allow: %s', $extension, implode(", ", $allow_extensions)));
}
```

### Get file content
``` php
...
$file = new File($path);

$content = $file->getContent();
```

## Installation

You can install this package easily with [Composer](https://getcomposer.org/).

Just require the package with the following command:

    $ composer require msgframework/file
