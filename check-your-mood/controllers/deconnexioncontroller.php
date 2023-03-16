<?php
namespace controllers;

use services\HomeService;
use services\MoodService;
use yasmf\HttpHelper;
use yasmf\View;

class DeconnexionController {

    public function index() {
		
		session_destroy();

		session_start();
        $view = new View("check-your-mood/views/accueil");
        return $view;
    }

}
