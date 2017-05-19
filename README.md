# PHP Captcha Library
Based on [mewebstudio/captcha](https://github.com/mewebstudio/captcha). 

## Preview
![Preview](http://i.imgur.com/HYtr744.png)

## Requirements
- PHP >=5.4
- [Intervention Image](https://github.com/Intervention/image)

## Composer Installation
```
composer require cocoa/captcha
```

## Usage
```php
use Cocoa\Captcha\CaptchaBuilder;

$builder = new CaptchaBuilder;

$captcha = $builder->build();

$builder->output();
```
```php
use Cocoa\Captcha\CaptchaBuilder;

$builder = new CaptchaBuilder;

$length = 5;

$captcha = $builder->build($length);

$builder->setWidth(120)->setHeight(36)->setContrast(0)->setInvert(false)->setSharpen(0)->setBgColor('#ffffff')->setBgImage(true)->setBlur(0)->setQuality(90)->setLines(3)->setAngle(10)->output();
```
