<?php
$path1 = $_GET['player'];
$path = "skins/".$path1.".png";
//echo $path;
if(!is_file($path)) $path = "default.png";
if(($skin = imagecreatefrompng($path)) == False) {
            // Error occured
            throw new Exception("Could not open PNG file.");
}

$r = 200;
$g = 12;

$newImage = imagecreatetruecolor(37, 32);
$background = imagecolorallocate($newImage, $r, $g, $b);
imagefilledrectangle($newImage, 0, 0, 37, 32, $background);//
imagecolorallocatealpha($newImage, 255, 255, 255, 127);
imagecolortransparent($newImage, $background);

imagecopy($newImage, $skin, 4, 0, 8, 8, 8, 8);
//$this->imagecopyalpha($newImage, $this->image, 4, 0, 40, 8, 8, 8, imagecolorat($this->image, 63, 0));
imagecopy($newImage, $skin, 4, 8, 20, 20, 8, 12);
imagecopy($newImage, $skin, 8, 20, 4, 20, 4, 12);
imagecopy($newImage, $skin, 4, 20, 4, 20, 4, 12);
imagecopy($newImage, $skin, 12, 8, 44, 20, 4, 12);
imagecopy($newImage, $skin, 0, 8, 44, 20, 4, 12);

imagecopy($newImage, $skin, 25, 0, 24, 8, 8, 8);
//$this->imagecopyalpha($newImage, $this->image, 25, 0, 56, 8, 8, 8, imagecolorat($this->image, 63, 0));
imagecopy($newImage, $skin, 25, 8, 32, 20, 8, 12);
imagecopy($newImage, $skin, 29, 20, 12, 20, 4, 12);
imagecopy($newImage, $skin, 25, 20, 12, 20, 4, 12);
imagecopy($newImage, $skin, 33, 8, 52, 20, 4, 12);
imagecopy($newImage, $skin, 21, 8, 52, 20, 4, 12);

$scale = 5;
$newWidth = 37 * $scale;
$newHeight = 32 * $scale;

$img = imagecreatetruecolor($newWidth, $newHeight);
$background = imagecolorallocate($img, $r, $g, $b);
imagefilledrectangle($img, 0, 0, $newWidth, $newHeight, $background);//
imagecolorallocatealpha($img, 255, 255, 255, 127);
imagecolortransparent($img, $background);
imagecopyresized($img, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, 37, 32);
 
//header('Content-type: image/png' || 'Content-type: image/x-png');
header('Content-type: image/png');
//$img = $newImage;
imagepng($img);
imagedestroy($img);