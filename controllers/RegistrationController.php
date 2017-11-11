<?php

namespace app\controllers;

use dektrium\user\controllers\RegistrationController as BaseRegistrationController;

class RegistrationController extends BaseRegistrationController
{
	
	public function render($file, $params)
    {
		return parent::render("@app/views/user/registration/".$file.".twig", $params);
    }

}