<?php

use claviska\SimpleImage;

interface IImage
{
    public function ResizeToWidth($pixels);

    public function Save($path);
}

class Image implements IImage
{
    private $image;

    public function __construct(SimpleImage $image)
    {
        $this->image = $image;
    }

    public function ResizeToWidth($pixels)
    {
        $this->image->bestFit($pixels, 9999);
    }

    public function Save($path)
    {
        $this->image->toFile($path);
    }
}
