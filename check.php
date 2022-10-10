<?php
error_reporting(0);
function uncrypt($value,$key)
{
	$a=0;
	for($i=0;$i<strlen($value);$i++)
	$a += (ord($value[$i])<<(($i+23)>>1)<<1)^($key^9+$i);
	$a %= 10000;
	$a = abs($a);
	if ($a<1000) $a+=2343;
	return $a;
}
if (empty($_GET["a1"]))$_GET["a1"]='123';
if (empty($_GET["a2"]))$_GET["a2"]=123;
$code = uncrypt($_GET["a1"],$_GET["a2"]);


$image=imagecreatetruecolor(80,45);

$color=ImageColorAllocate($image,rand(200,255),rand(200,255),rand(200,255));

if(isset($code)){
$image = put_number($image,substr($code,0,1),1);
$image = put_number($image,substr($code,1,1),2);
$image = put_number($image,substr($code,2,1),3);
$image = put_number($image,substr($code,3,1),4);
imageline($image, 0, 0, 80, rand(0,45), ImageColorAllocate($image,mt_rand(0,225), mt_rand(0,225), mt_rand(0,225)));
imageline($image, 0, 0, 80, rand(0,45), ImageColorAllocate($image,mt_rand(0,225), mt_rand(0,225), mt_rand(0,225)));
imageline($image, 0, rand(0,45), 80, 0, ImageColorAllocate($image,mt_rand(0,225), mt_rand(0,225), mt_rand(0,225)));
imageline($image, 0, rand(0,45), 80, 0, ImageColorAllocate($image,mt_rand(0,225), mt_rand(0,225), mt_rand(0,225)));
}

for($i=0;$i<80;$i++)
{
$color=ImageColorAllocate($image,rand(50,140),rand(50,140),rand(50,140));
for($j=0;$j<45;$j++)
if (!ImageColorAt($image, $i, $j))
 imagesetpixel ( $image, $i, $j, $color );
}

ImageGIF($image);

function put_number($image,$number,$left)
{
	$rotate=imagecreatetruecolor(20,20);
	$color=ImageColorAllocate($image,rand(200,255),rand(200,255),rand(200,255));
	ImageString($rotate,5,0,0,$number ,$color);
	$rotate = imagerotate($rotate, rand(-15,15), 0);
	imagecopyresized($image,$rotate,($left-1)*20,rand(1,15),0,0,30,30,20,20);
	return $image;
}
?>