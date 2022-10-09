<?
error_reporting(0);
require_once 'classes/sql.php';
include_once "inc/functions.php";
$you = catch_user(intval($_COOKIE["uid"]), $_COOKIE["hashcode"], 1);
if (!$you["priveleged"])
	die("<script>location='index.php';</script>");

SQL::q("TRUNCATE TABLE `images` ");

$bufer = array();
function ext($file)
{
	$e = explode(".", $file);
	return $e[count($e) - 1];
}
function return_allfiles($_dir)
{
	global $bufer;
	$dir = @opendir($_dir);
	while (false !== ($file = readdir($dir))) {
		if ($file == '.' or $file == '..') continue;
		if (ext($file) != $file)
			$bufer[] = $_dir . "/" . $file;
		else
			return_allfiles($_dir . "/" . $file);
	}
}

return_allfiles("images/weapons");
foreach ($bufer as $b) {
	list($width, $height, $type, $attr) = getimagesize($b);
	$file = str_replace("images/weapons/", "", $b);
	$w = SQL::q1("SELECT COUNT(*) as count FROM images WHERE address='" . $file . "'")['count'];
	if (!$w) {
		SQL::q("INSERT INTO images (address,width,height,type) VALUES('" . $file . "'," . $width . "," . $height . ",1)");
	}
}

$dir = @opendir("images/magic");
while (false !== ($file = readdir($dir))) {
	list($width, $height, $type, $attr) = getimagesize("images/magic/" . $file);
	$w = SQL::q1("SELECT COUNT(*) as count FROM images WHERE address='" . $file . "'")['count'];
	if (!$w) {
		SQL::q("INSERT INTO images (address, width, height, type) VALUES ('" . $file . "'," . $width . "," . $height . ",2)");
	}
}

$dir = @opendir("images/persons");
while (false !== ($file = readdir($dir))) {
	list($width, $height, $type, $attr) = getimagesize("images/persons/" . $file);
	$w = SQL::q1("SELECT COUNT(*) as count FROM images WHERE address='" . $file . "'")['count'];
	if (!$w) {
		SQL::q("INSERT INTO images (address,width,height,type) VALUES ('" . $file . "'," . $width . "," . $height . ",3)");
	}
}
echo "<script>alert('The image library was updated successifully!');</script>";
