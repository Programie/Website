<?php
namespace com\selfcoders\website;

use Parsedown;

class CustomizedParsedown extends Parsedown
{
    public string $baseUrl;
    public $relativeLinkHook;

    protected function inlineLink($Excerpt)
    {
        $link = parent::inlineLink($Excerpt);

        $url = $link["element"]["attributes"]["href"];

        $isAbsoluteUrl = parse_url($url, PHP_URL_HOST) !== null;

        if (!$isAbsoluteUrl and $url[0] !== "/") {
            if (is_callable($this->relativeLinkHook)) {
                $url = call_user_func($this->relativeLinkHook, $url);
            }

            $link["element"]["attributes"]["href"] = sprintf("%s/%s", $this->baseUrl, $url);
        }

        if ($isAbsoluteUrl) {
            $link["element"]["attributes"]["target"] = "_blank";
        }

        return $link;
    }
}