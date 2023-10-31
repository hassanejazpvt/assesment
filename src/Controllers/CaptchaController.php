<?php

declare(strict_types=1);

namespace Hassan\Assesment\Controllers;

class CaptchaController
{
    public function generate(): void
    {
        // Generate a random 5-character string
        $randomText = substr(md5((string) mt_rand()), 0, 5);

        $_SESSION['captcha'] = $randomText;

        $width  = 100;
        $height = 40;

        $image = imagecreatetruecolor($width, $height);

        $bgColor   = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $bgColor);

        imagestring($image, 5, 30, 12, $randomText, $textColor);

        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
    }
}
