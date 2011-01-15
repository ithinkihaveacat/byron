<?php

// $Id$

/**
 * Crops (without resizing) $src so that it fits within the bounding box
 * ($max_w, $max_h).  The cropped section is centered.
 */

function imageboundingbox($src, $dst_w, $dst_h) {

    $dst = imagecreatetruecolor($dst_w, $dst_h);

	$src_w = imagesx($src);
	$src_h = imagesy($src);

	$src_x = ($dst_w < $src_w) ? floor(($src_w - $dst_w) / 2) : 0;
	$src_y = ($dst_h < $src_h) ? floor(($src_h - $dst_h) / 2) : 0;

	imagecopy($dst, $src, 0, 0, $src_x, $src_y, $dst_w, $dst_h);
    
    return $dst;

}

/**
 * Resamples (resizes) $src to a width of at least $min_w, and
 * a height of at least $min_h.  The destination can be either
 * smaller or larger than the source; in both cases the aspect
 * ratio is maintained.  (Set $min_w or $min_h to 0 to resize
 * by height or width.)
 *
 * TODO We're not especially careful without rounding
 * pixel values; we're probably going to be wrong by
 * up to one pixel on occasion.
 *
 * @param image $src source image
 * @param integer $min_w minimum width of destination image
 * @param integer $min_h minimum height of destination image
 * @return resource image at least $min_w wide and $min_h high
 */

function imageresamplemin($src, $min_w, $min_h) {

	$src_w = imagesx($src);
	$src_h = imagesy($src);

	$dst_w = $min_w;
	$dst_h = $min_h;

	if ($dst_w < (($src_w / $src_h) * $min_h)) {
		$dst_w = ($src_w / $src_h) * $min_h;
	}

	if ($dst_h < (($src_h / $src_w) * $min_w)) {
		$dst_h = ($src_h / $src_w) * $min_w;
	}

	$dst = imagecreatetruecolor($dst_w, $dst_h);

	imagecopyresampled($dst, $src, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

	return $dst;

}

/**
 * Resamples (resizes) $src to a maximum width of at most $max_w, and
 * a maximum height of at most $max_h.  (The aspect ratio is maintained.)
 *
 * TODO We're not especially careful without rounding
 * pixel values; we're probably going to be wrong by
 * up to one pixel on occasion.
 *
 * @param resource $src source image
 * @param integer $max_w maximum width of destination image
 * @param integer $max_h maximum height of destination image
 * @return resource image no wider than $max_w and no higher than $max_h
 */

function imageresamplemax($src, $max_w, $max_h) {

	$src_w = imagesx($src);
	$src_h = imagesy($src);

	$dst_w = ($src_w / $src_h) * $max_h; // width  if we scale by $max_h
	$dst_h = ($src_h / $src_h) * $max_w; // height if we scale by $max_w

	if ($dst_w > $max_w) {
		$dst_w = $max_w;
		$dst_h = ($src_h / $src_w) * $dst_w;
	}
	else if ($dst_h > $max_h) {
		$dst_h = $max_h;
		$dst_w = ($src_w / $src_h) * $dst_h;
	}

	$dst = imagecreatetruecolor($dst_w, $dst_h);

	imagecopyresampled($dst, $src, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

	return $dst;

}

/**
 * Resamples (resizes) $src to a width of $max_w, and a height
 * of exactly $max_h.  (The image is enlarged if $dst is larger
 * than $src.)
 *
 * TODO We're not especially careful without rounding
 * pixel values; we're probably going to be wrong by
 * up to one pixel on occasion.
 *
 * TODO What to do about the border colour/gravity?
 *
 * @param resource $src source image
 * @param integer $exact_w exact width of destination image
 * @param integer $exact_h exact height of destination image
 * @return resource image exactly $exact_w by $exact_h
 */

function imageresampleexact($src, $exact_w, $exact_h, $gravity = "center") {

	$src_w = imagesx($src);
	$src_h = imagesy($src);

	$dst_w = $exact_w;
	$dst_h = $exact_h;

	if ($dst_w > (($src_w / $src_h) * $exact_h)) {
		$dst_w = ($src_w / $src_h) * $exact_h;
	}

	if ($dst_h > (($src_h / $src_w) * $exact_w)) {
		$dst_h = ($src_h / $src_w) * $exact_w;
	}

	$dst = imagecreatetruecolor($exact_w, $exact_h);

//	$bgcolor = imagecolorallocate($dst, $bg[0], $bg[1], $bg[2]);
//	imagefill($dst, 0, 0, $bgcolor);

	$off_x = floor($exact_w - $dst_w) / 2;
	$off_y = floor($exact_h - $dst_h) / 2;

	imagecopyresampled($dst, $src, $off_x, $off_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

	return $dst;

}

/**
 * Loads image file given by $filename.  JPG, GIF and PNG types are supported.
 *
 * @param string $filename image filename
 * @return resource either image object, or false (if error occurred)
 */

function imageload($filename) {
    
	($img = @imagecreatefromjpeg($filename)) || ($img = @imagecreatefromgif($filename)) || ($img = @imagecreatefrompng($filename));
    
    return $img;

}

/**
 * Saves image as a JPG to $filename.  (Wrapper for "imagejpeg".)
 *
 * @param resource $img image object
 * @param string $filename filename to store image to
 * @param integer $quality JPG quality (optional; defaults to 85)
 * @return boolean true is save was successful, otherwise false
 */

function imagesave($img, $filename, $quality = 85) {

	return imagejpeg($img, $filename, $quality);

}

?>
