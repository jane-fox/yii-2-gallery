<?php

namespace app\helpers;
use Yii;



class Thumbnail {
 
	//takes a post model as an argument.
    public static function generate($file, $type, $thumb_size = 250) {


		$file_path = Yii::$app->params['content_path'] . $type . "/";

		$input = $file_path . $file;

		$name = pathinfo($file, PATHINFO_FILENAME) . ".png";
		$filename = Upload::check_filename($name, "thumb");


		$save_location = Yii::$app->params['content_path'] . "thumb/";
		$output = $save_location . $filename;


		if ($type == "image") {

			$src_img = imagecreatefromstring(file_get_contents($input));
			$old_x = imageSX($src_img);
			$old_y = imageSY($src_img);

			if ($old_x > $old_y) {
				$thumb_w = $thumb_size;
				$thumb_h = $old_y * ($thumb_size/$old_x);
			}
			if ($old_x < $old_y) {
				$thumb_w = $old_x * ($thumb_size/$old_y);
				$thumb_h = $thumb_size;
			}
			if ($old_x == $old_y) {
				$thumb_w = $thumb_size;
				$thumb_h = $thumb_size;
			}

			$dst_img = ImageCreateTrueColor($thumb_w,$thumb_h);
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
			imagepng($dst_img, $output);

			//Create thumbnail for video
		} else {

			$thumbnail = Yii::$app->params['video_thumbnailer'];

			shell_exec ("$thumbnail -i \"{$input}\" -ss 00:00:01.000  -vframes 1 -filter:v scale=\"{$thumb_size}:-1\" \"{$output}\"");

		}

		return $filename;

    }
	
}
