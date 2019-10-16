<?php

namespace App;

use Exception;

class Extension
{
    const DEFAULT_EXTENSION_VERSION = 'latest';
    const NAME_DELIMITER = '.';
    const VERSION_DELIMITER = '@';

    /** @var string */
    private $publisher;

    /** @var string */
    private $package;

    /** @var string */
    private $version;

    public function __construct(string $publisher, string $package, string $version)
    {
        $this->publisher = $publisher;
        $this->package = $package;
        $this->version = $version;
    }

    public static function parse(string $extensionIdentifier): Extension
    {
        $data = $extensionIdentifier;
        $version = self::DEFAULT_EXTENSION_VERSION;
        if (strpos($data, self::VERSION_DELIMITER) !== false) {
            $split = explode(self::VERSION_DELIMITER, $data, 2);
            $version = $split[1];
            $data = $split[0];
        }

        $names = explode(self::NAME_DELIMITER, $data);
        if (count($names) !== 2 ) {
            throw new Exception('Failed to parse extension "'.$extensionIdentifier.'".');
        }

        return new Extension($names[0], $names[1], $version);
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getFileName(): string
    {
        return $this->publisher.'-'.$this->package.'-'.$this->version.'.vsix';
    }

    public function getPackageUrl(): string
    {
        return "https://marketplace.visualstudio.com/_apis/public/gallery/publishers/{$this->publisher}/vsextensions/{$this->package}/{$this->version}/vspackage";
    }
}
