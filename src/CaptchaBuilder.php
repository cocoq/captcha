<?php

namespace Cocoa\Captcha;

use Intervention\Image\ImageManager;

class CaptchaBuilder
{
	/**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var ImageManager->image
     */
    protected $image;

    /**
     * @var ImageManager->canvas
     */
    protected $canvas;

    /**
	 * @var string
	 */
    protected $text;

    /**
     * @var array
     */
    protected $fonts = [];

    /**
     * @var array
     */
    protected $fontColors = [];

    /**
     * @var array
     */
    protected $backgrounds = [];

    /**
     * @var int
     */
    protected $length = 5;

    /**
     * @var int
     */
    protected $width = 120;

    /**
     * @var int
     */
    protected $height = 36;

    /**
     * @var int
     */
    protected $angle = 15;

    /**
     * @var int
     */
    protected $lines = 3;

    /**
     * @var string
     */
    protected $characters = '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ';

    /**
     * @var int
     */
    protected $contrast = 0;

    /**
     * @var int
     */
    protected $quality = 90;

    /**
     * @var int
     */
    protected $sharpen = 0;

    /**
     * @var int
     */
    protected $blur = 0;

    /**
     * @var bool
     */
    protected $bgImage = true;

    /**
     * @var string
     */
    protected $bgColor = '#ffffff';

    /**
     * @var bool
     */
    protected $invert = false;

    public function __construct()
    {
        $this->setAssets();

        $this->imageManager = new ImageManager;
    }

    /**
     * @return ImageManager->response
     */
    public function create()
    {
        $this->canvas = $this->imageManager->canvas(
            $this->width,
            $this->height,
            $this->bgColor
        );

        if ($this->bgImage) {
            $this->image = $this->imageManager->make($this->background())->resize(
                $this->width, $this->height
            );

            $this->canvas->insert($this->image);
        } else {
            $this->image = $this->canvas;
        }

        $this->contrast && $this->image->contrast($this->contrast);

        $this->text();

        $this->lines();

        $this->sharpen && $this->image->sharpen($this->sharpen);

        $this->invert && $this->image->invert($this->invert);

        $this->blur && $this->image->blur($this->blur);

        return $this->image->response('png', $this->quality);
    }

    public function output()
    {
        echo $this->create();
    }

    /**
     * @return string
     */
    public function getCaptcha()
    {
        return $this->text;
    }

    /**
     * @param  int $length
     * @return string
     */
    public function build($length = null)
    {
        $length && $this->length = $length;

        return $this->generate();
    }

    /**
     * @param  array $backgrounds
     * @return $this
     */
    public function setBackgrounds($backgrounds)
    {
        $this->backgrounds = $backgrounds;

        return $this;
    }

    /**
     * @param  array $fonts
     * @return $this
     */
    public function setFonts($fonts)
    {
        $this->fonts = $fonts;

        return $this;
    }

    /**
     * @param  int $contrast
     * @return $this
     */
    public function setContrast($contrast)
    {
        $this->contrast = $contrast;

        return $this;
    }
    
    /**
     * @param  bool $invert
     * @return $this
     */
    public function setInvert($invert)
    {
        $this->invert = $invert;

        return $this;
    }
    
    /**
     * @param  int $sharpen
     * @return $this
     */
    public function setSharpen($sharpen)
    {
        $this->sharpen = $sharpen;

        return $this;
    }
    
    /**
     * @param  string $bgColor
     * @return $this
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;

        return $this;
    }
    
    /**
     * @param  bool $bgImage
     * @return $this
     */
    public function setBgImage($bgImage)
    {
        $this->bgImage = $bgImage;

        return $this;
    }
    
    /**
     * @param  int $blur
     * @return $this
     */
    public function setBlur($blur)
    {
    	$this->blur = $blur;

    	return $this;
    }
    
    /**
     * @param  int $quality
     * @return $this
     */
    public function setQuality($quality)
    {
    	$this->quality = $quality;

    	return $this;
    }
    
    /**
     * @param  int $lines
     * @return $this
     */
    public function setLines($lines)
    {
    	$this->lines = $lines;

    	return $this;
    }
    
    /**
     * @param  int $angle
     * @return $this
     */
    public function setAngle($angle)
    {
    	$this->angle = $angle;

    	return $this;
    }
    
    /**
     * @param  string $characters
     * @return $this
     */
    public function setCharacters($characters)
    {
    	$this->characters = $characters;

    	return $this;
    }
    
    /**
     * @param  int $width
     * @return $this
     */
    public function setWidth($width)
    {
    	$this->width = $width;

    	return $this;
    }
    
    /**
     * @param  int $height
     * @return $this
     */
    public function setHeight($height)
    {
    	$this->height = $height;

    	return $this;
    }
    
    /**
     * @param  array $fontColors
     * @return $this
     */
    public function setFontColors($fontColors)
    {
    	$this->fontColors = $fontColors;

    	return $this;
    }

    /**
     * Set Assets
     */
    protected function setAssets()
    {
    	$this->fonts = glob(__DIR__ . '/../assets/fonts/*.*');
    	$this->backgrounds = glob(__DIR__ . '/../assets/backgrounds/*.*');
    }

    /**
     * @return string
     */
    protected function generate()
    {
        return $this->text = substr(
            str_shuffle($this->characters), 0, $this->length
        );
    }

    /**
     * @return string
     */
    protected function background()
    {
        return $this->backgrounds[array_rand($this->backgrounds)];
    }

    protected function text()
    {
        $marginTop = $this->image->height() / $this->length;

        $chars = str_split($this->text);

        array_map(function ($char, $i) use ($marginTop) {
        	$marginLeft = ($i * $this->image->width() / $this->length);

            $this->image->text($char, $marginLeft, $marginTop, function ($font) {
                $font->file($this->font());
                $font->size($this->fontSize());
                $font->color($this->fontColor());
                $font->align('left');
                $font->valign('top');
                $font->angle($this->angle());
            });

        }, $chars, array_keys($chars));
    }
    
    /**
     * @return string
     */
    protected function font()
    {
        return $this->fonts[array_rand($this->fonts)];
    }

    /**
     * @return int
     */
    protected function fontSize()
    {
        return mt_rand($this->image->height() - 10, $this->image->height());
    }

    /**
     * @return mixed
     */
    protected function fontColor()
    {
        return $this->fontColors
        	   ? $this->fontColors[array_rand($this->fontColors)] 
        	   : [mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)];
    }

    /**
     * @return int
     */
    protected function angle()
    {
        return mt_rand((-1 * $this->angle), $this->angle);
    }

    protected function lines()
    {
    	array_map(function ($i) {
    		$this->image->line(
                mt_rand(0, $this->image->width()) + $i * mt_rand(0, $this->image->height()),
                mt_rand(0, $this->image->height()),
                mt_rand(0, $this->image->width()),
                mt_rand(0, $this->image->height()),
                function ($draw) {
                    $draw->color($this->fontColor());
                }
            );
    	}, range(0, $this->lines));
    }
}
