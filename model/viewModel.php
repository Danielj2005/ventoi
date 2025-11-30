<?php
	class viewModel{

		/*--------- Modelo obtener vistas ---------*/
		protected static function obtener_vistas_modelo($view){
			$listaBlanca = ["dashboard", "producto", "entrada","proveedor","recovery"
		];


			if(in_array($view, $listaBlanca)) {

				$contenido = is_file("./view/content/$view-view.php") ? "./view/content/$view-view.php" : "404";
				
			}elseif($view == "login"){ 
				
				$contenido = "login";
			
			}else{ $contenido = "404"; }

			return $contenido;
		}
	}