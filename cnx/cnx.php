<?php

	// Conexión a la base de datos del Dashboard
		$enlace = mysqli_connect("localhost", "root", "", "bdGelSac");

	if (!$enlace) {
	    echo "Error: No se pudo conectar al motor de BD del Dashboard." . PHP_EOL;
	    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
	    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}

	// Cambiando idioma al servidor
		$q_utf = "SET NAMES 'utf8'";

		if ($res = mysqli_query($enlace, $q_utf)) {
    }
  
		mysqli_set_charset($enlace, "utf8");

	function cerrarSesion($conn){
		mysqli_close($conn);
	}
?>