<?php

namespace app\controllers;

use dektrium\user\controllers\SecurityController as BaseSecurityController;

class SecurityController extends BaseSecurityController
{
	
	public function render($file, $params)
    {
		return parent::render("@app/views/user/security/".$file.".twig", $params);
    }

}