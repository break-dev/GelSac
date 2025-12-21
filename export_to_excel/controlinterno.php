<?php
	session_start();

	include('../cnx/cnx.php');
  include('../global/variables.php');

	header('Content-type: application/vnd.ms-excel; charset=iso-8859-1');
	header('Content-Disposition: attachment;filename=Control interno.xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	// Recuperando las variables enviadas
	$tabla_html_param = $_POST["tabla_html_param"];


	echo "\xEF\xBB\xBF";
  // Imprime la tabla HTML
  echo $tabla_html_param;
  exit;

?>