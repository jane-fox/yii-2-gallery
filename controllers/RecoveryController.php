<?php

namespace app\controllers;

use dektrium\user\controllers\RecoveryController as BaseRecoveryController;

class RecoveryController extends BaseRecoveryController
{
	
	public function render($file, $params)
    {
		return parent::render("@app/views/user/recovery/".$file.".twig", $params);
    }

}