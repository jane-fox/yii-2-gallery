<?php

namespace app\helpers;


use Yii;

class Upload {
 
	//takes a $_FILE as an argument.
	public static function content($file) {

		//Check input
		if ($file['error'] == 0) {
			if (is_uploaded_file($file['tmp_name'])) {

				//Check file type
				$type = Type::get($file['type']);
				if ($type != false) {

					$filename = self::check_filename($file['name'], $type);

					$upload_location = Yii::$app->params['content_path'] . $type . "/";

					move_uploaded_file($_FILES['file']['tmp_name'], $upload_location . $filename);

					//File should now be saved successfully. Create a thumbnail.

					$thumb = Thumbnail::generate($filename, $type);

				}

				return ["file" => $filename, "thumb" => $thumb, "type" => $type];

			}
		}

		return false;

	}

	//Checks for given file, returns unique version to use, video/image/thumb as location
	function check_filename($filename, $location) {

		//Set up save path and name
		$upload_location = Yii::$app->params['content_path'] . $location . "/";

		//First check if filename already exists. Append incrementing number if so
		if (file_exists($upload_location . $filename)) {

			$name = pathinfo($filename, PATHINFO_FILENAME);
			$ext =  pathinfo($filename, PATHINFO_EXTENSION);

			$i = 1;
			while (file_exists($upload_location . $filename)) {
				$filename = $name. $i . "." . $ext;
				$i++;
			}


		}

		return $filename;


	}


}