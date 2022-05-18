<?php
/*  ---------------------------------------------------------------------------
 * 	@package	: Logo Thumbnail Settings
 *	@author 	: Akinola Abdulakeem
 *	@version	: 1.0
 *	@link		: https://akinolaakeem.com
 *	--------------------------------------------------------------------------- */


function make_thumb_jpg($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = 134;
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);

}

function make_thumb_png($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefrompng($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = 134;
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    // Transparent Background
    imagealphablending($virtual_image, false);
    $transparency = imagecolorallocatealpha($virtual_image, 0, 0, 0, 127);
    imagefill($virtual_image, 0, 0, $transparency);
    imagesavealpha($virtual_image, true);

	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

	/* create the physical thumbnail image to its destination */
	imagepng($virtual_image, $dest);

}