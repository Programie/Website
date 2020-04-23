<?php
namespace com\selfcoders\website;

use Parsedown;

class CustomizedParsedown extends Parsedown
{
    public $baseUrl;
    public $imageBaseUrl;

    protected function inlineImage($excerpt)
    {
        $image = parent::inlineImage($excerpt);

        if ($image === null) {
            return null;
        }

        $image["element"]["attributes"]["src"] = preg_replace("|^(" . preg_quote($this->baseUrl) . ")|", $this->imageBaseUrl, $image["element"]["attributes"]["src"]);
        $image["element"]["attributes"]["class"] = "img-fluid";

        return $image;
    }

    protected function inlineLink($Excerpt)
    {
        $link = parent::inlineLink($Excerpt);

        $url = $link["element"]["attributes"]["href"];

        if ($link["element"]["name"] !== "img" and parse_url($url, PHP_URL_HOST) === null) {
            $link["element"]["attributes"]["href"] = $this->baseUrl . $url;
        }

        return $link;
    }
}