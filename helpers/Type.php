<?php

namespace app\helpers;


class Type {

	//Get image/video type from MIME
    public static function get($type) {

		$image_whitelist = ["image/png", "image/jpeg", "image/jpg"];
		$video_whitelist = ["video/mp4", "video/ogg", "video/webm", "video/avi", "video/mpeg", "video/msvideo", "video/quicktime"];

		if(in_array($type, $image_whitelist)) {
			return "image";
		}

		if(in_array($type, $video_whitelist)) {
			return "video";
		}

		return false;
	}

	public function for_model($model) {
		return self::get($model->type);
	}

}
