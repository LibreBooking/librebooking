<?php

class Url
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var bool
     */
    private $hasQuestionMark = false;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->hasQuestionMark = BookedStringHelper::Contains($url, '?');
    }

    /**
     * @param $urlFragment string
     * @return Url
     */
    public function Add($urlFragment)
    {
        if (!BookedStringHelper::EndsWith($this->url, '/')) {
            $this->url .= '/';
        }

        $this->url .= urlencode($urlFragment);

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Url
     */
    public function AddQueryString($name, $value)
    {
        $char = '?';
        if ($this->hasQuestionMark) {
            $char = '&';
        }

        $this->hasQuestionMark = true;
        $this->url .= sprintf("$char%s=%s", $name, urlencode($value ?? ''));

        return $this;
    }

    /**
     * @return string
     */
    public function ToString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return $this->url;
    }

    /**
     * @return Url
     */
    public function Copy()
    {
        return new Url($this->ToString());
    }
}
