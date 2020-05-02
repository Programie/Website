<?php
namespace com\selfcoders\website;

class Utils
{
    public static function isSafePath(string $basePath, string $path)
    {
        $basePath = realpath($basePath);
        if ($basePath === false) {
            return false;
        }

        $basePath = sprintf("%s/", $basePath);

        $path = realpath($path);
        if ($path === false) {
            return false;
        }

        return substr($path, 0, strlen($basePath)) === $basePath;
    }
}