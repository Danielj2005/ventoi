<?php
	class viewModel{

		/*--------- Modelo obtener vistas ---------*/
		protected static function obtener_vistas_modelo($view){
			$listaBlanca = ["dashboard", "producto", "entrada","proveedor",
			"gestion_servicios","ventas","cliente","factura","categoria","marca","modelo_producto",
			"user","plan","monthlyPayment","payments","enterprise","binnacle","setting"];


			if(in_array($view, $listaBlanca)) {

				if(is_file("./view/content/$view-view.php")){

					$contenido = "./view/content/$view-view.php";

				}else{ $contenido = "404"; }

			}elseif($view == "login"){ $contenido = "login"; }

			else{ $contenido = "404"; }

			return $contenido;
		}
	}