<?php

class ContrastingColor
{
    /**
     * @var string|null
     */
    private $sourceColor;

    public function __construct($sourceColor)
    {
        $this->sourceColor = str_replace('#', '', $sourceColor);
    }

    public function GetHex()
    {
        // http://24ways.org/2010/calculating-color-contrast/
        $r = hexdec(substr($this->sourceColor, 0, 2));
        $g = hexdec(substr($this->sourceColor, 2, 2));
        $b = hexdec(substr($this->sourceColor, 4, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        return ($yiq >= 128) ? '#000000' : '#FFFFFF';
    }

    public function __toString()
    {
        return $this->GetHex();
    }
}

class AdjustedColor
{
    /**
     * @var string|null
     */
    private $sourceColor;

    /**
     * @var string|null
     */
    private $steps;

    public function __construct($sourceColor, $steps = 50)
    {
        $this->sourceColor = str_replace('#', '', $sourceColor);
        $this->steps = $steps;
    }

    public function GetHex()
    {
        if (!preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $this->sourceColor, $parts)) {
            return '';
        }
        $out = '';
        for ($i = 1; $i <= 3; $i++) {
            $parts[$i] = hexdec($parts[$i]);
            $parts[$i] = round($parts[$i] * $this->steps / 100);
            $out .= str_pad(dechex($parts[$i]), 2, '0', STR_PAD_LEFT);
        }
        return '#' . $out;
    }

    public function __toString()
    {
        return $this->GetHex();
    }
}
