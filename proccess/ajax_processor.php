<?php 
	switch ($_POST["opc"]) {
		case '1':
			save_conf();
			break;
		
		default:
			# code...
			break;
	}

	function save_conf(){
		try{
			$archivo = 'conf';
			$abrir = fopen($archivo,'r+');
			$contenido = fread($abrir,filesize($archivo));
			fclose($abrir);
			 
			// Separar linea por linea
			$contenido = explode("\n",$contenido);
			 // var_dump($contenido);
			// Modificar linea deseada ( 2 ) 
			$contenido[0] = $_POST["email"];
			$contenido[1] = $_POST["password"];
			$contenido[2] = $_POST["smtp"];
			$contenido[3] = $_POST["host"];
			$contenido[4] = $_POST["port"];
			$contenido[5] = $_POST["setfrom"];
			 
			// Unir archivo
			$contenido = implode("\n",$contenido);
			 
			// Guardar Archivo
			$abrir = fopen($archivo,'w');
			fwrite($abrir,$contenido);
			fclose($abrir);
			echo "Datos Guardados";
		}catch(Exception $e){
			echo "error";
		}
	}
?>