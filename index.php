<?php
	require_once "./config/APP.php";
	require_once "./controller/viewController.php";
		
	$plantilla = new viewController();
	$plantilla->obtener_plantilla_controlador();