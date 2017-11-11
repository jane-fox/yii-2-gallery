<?php

namespace app\models;

use dektrium\user\models\User as BaseUser;

//Extension of a user module
class User extends BaseUser {





	//Methods for comment module
	public function getCommentatorAvatar()
	{
		return $this->avatar_url;
	}

	public function getCommentatorName()
	{
		return $this->name;
	}

	public function getCommentatorUrl()
	{
		return ['/profile', 'id' => $this->id]; // or false, if user does not have a public page
	}


}
