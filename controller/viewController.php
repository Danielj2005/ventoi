<?php
	
	require_once "./model/viewModel.php";

	class viewController extends viewModel{

		/*--------- Controlador obtener plantilla ---------*/
		public function obtener_plantilla_controlador(){
			return require_once "./view/template.php";
		}

		/*--------- Controlador obtener vistas ---------*/
		public function obtener_vistas_controlador(){
			if(isset($_GET['views'])){
				$ruta=explode("/", $_GET['views']);
				$respuesta=viewModel::obtener_vistas_modelo($ruta[0]);
			}else{
				$respuesta="index";
			}
			return $respuesta;
		}
	}