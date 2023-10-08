<?php
namespace com\selfcoders\website;

use Parsedown;

class CustomizedParsedown extends Parsedown
{
    public string $baseUrl;
    public bool $openLinksInNewTab = false;

    protected function inlineLink($Excerpt)
    {
        $link = parent::inlineLink($Excerpt);

        $url = $link["element"]["attributes"]["href"];

        $isAbsoluteUrl = parse_url($url, PHP_URL_HOST) !== null;

        if (!$isAbsoluteUrl and $url[0] !== "/" and $url[0] !== "#") {
            $link["element"]["attributes"]["href"] = sprintf("%s/%s", $this->baseUrl, $url);
        }

        if ($this->openLinksInNewTab) {
            $link["element"]["attributes"]["target"] = "_blank";
        }

        return $link;
    }
}