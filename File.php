<?php

namespace Msgframework\Lib\File;

use MSGFramework\Lib\File\Exception\FileException;
use MSGFramework\Lib\File\Exception\FileNotFoundException;
use Symfony\Component\Mime\MimeTypes;

class File extends \SplFileInfo
{
    /**
     * Constructs a new file from the given path.
     *
     * @param string $path      The path to the file
     */
    public function __construct(string $path, bool $checkPath)
    {
        if ($checkPath && !is_file($path)) {
            throw new FileNotFoundException($path);
        }
        parent::__construct($path);
    }

    /**
     * Returns the extension based on the mime type.
     *
     * If the mime type is unknown, returns null.
     *
     * This method uses the mime type as guessed by getMimeType()
     * to guess the file extension.
     *
     * @return string|null The guessed extension or null if it cannot be guessed
     *
     * @see MimeTypes
     * @see getMimeType()
     */
    public function getExtension(): ?string
    {
        if (!class_exists(MimeTypes::class)) {
            throw new \LogicException('You cannot guess the extension as the Mime component is not installed. Try running "composer require symfony/mime".');
        }

        return MimeTypes::getDefault()->getExtensions($this->getMimeType())[0] ?? null;
    }

    /**
     * Returns the mime type of the file.
     *
     * The mime type is guessed using a MimeTypeGuesserInterface instance,
     * which uses finfo_file() then the "file" system binary,
     * depending on which of those are available.
     *
     * @return string|null The guessed mime type (e.g. "application/pdf")
     *
     * @see MimeTypes
     */
    public function getMimeType(): ?string
    {
        if (!class_exists(MimeTypes::class)) {
            throw new \LogicException('You cannot guess the mime type as the Mime component is not installed. Try running "composer require symfony/mime".');
        }

        return MimeTypes::getDefault()->guessMimeType($this->getPathname());
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $content = file_get_contents($this->getPathname());

        if (false === $content) {
            throw new FileException(sprintf('Could not get the content of the file "%s".', $this->getPathname()));
        }

        return $content;
    }

    /**
     * @param string $directory
     * @param string|null $name
     * @return $this
     */
    protected function getTargetFile(string $directory, string $name = null): self
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory.', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory.', $directory));
        }

        $target = rtrim($directory, '/\\').\DIRECTORY_SEPARATOR.(null === $name ? $this->getBasename() : $this->getName($name));

        return new self($target, false);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getName(string $name): string
    {
        $originalName = str_replace('\\', '/', $name);
        $pos = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);

        return $originalName;
    }
}