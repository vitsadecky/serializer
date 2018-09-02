<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Tomas Kulhanek
 * Email: info@tirus.cz
 */

namespace HelpPC\Serializer\Utils;

class SplFileInfo extends \SplFileInfo
{

    /** @var bool */
    private $temp;

    public function __construct($file_name, $temp = false)
    {
        $this->temp = $temp;
        parent::__construct($file_name);
    }

    public function isTemp(): bool
    {
        return $this->temp;
    }

    protected static function getTempNam(string $type): string
    {
        $filePath = tempnam(sys_get_temp_dir(), $type);
        return $filePath;
    }

    public function __destruct()
    {
        if ($this->temp && file_exists($this->getRealPath())) {
            @unlink($this->getRealPath());
        }
    }

    public static function createInTemp($content): SplFileInfo
    {
        $obj = new static(self::getTempNam((string)strtotime('now')), true);
        if ($content !== null) {
            @file_put_contents($obj->getRealPath(), $content);
        }
        return $obj;
    }

    /**
     * Returns the contents of the file.
     *
     * @return string the contents of the file
     *
     * @throws \RuntimeException
     */
    public function getContents(): string
    {
        set_error_handler(function ($type, $msg) use (&$error) {
            $error = $msg;
        });
        $content = file_get_contents($this->getPathname());
        restore_error_handler();
        if (false === $content) {
            throw new \RuntimeException($error);
        }

        return $content;
    }

    public static function createFromSplFileInfo(\SplFileInfo $fileInfo): SplFileInfo
    {
        return new static($fileInfo->getRealPath());
    }

    public function __toString(): string
    {
        return $this->getContents();
    }
}
