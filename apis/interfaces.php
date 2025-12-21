<?php
	session_start();

	header('Content-Type: application/json');

	include('../cnx/cnx.php');
	include('../global/variables.php');

	switch ($_GET["accion"]) {

		case 'guardar_PesoBalanza':
      $r = array();
      $estado = 0;

      $cod_balanza = $_GET["cod_balanza"];
      $peso = $_GET["peso"];

      // 1. Buscar si existe un peso previo, si no existe crea el registro
        $q_getpeso = "SELECT COUNT(Id) AS _COUNT
                        FROM interfaces_pesosbalanzas
                       WHERE cod_balanza = ".$cod_balanza;

        if ($res_getpeso = mysqli_query($enlace, $q_getpeso)) {
          if (mysqli_num_rows($res_getpeso) > 0) {
            while($row_getpeso = mysqli_fetch_assoc($res_getpeso)) {
              if ($row_getpeso["_COUNT"] == 0){
                $q_insert = "INSERT INTO interfaces_pesosbalanzas (cod_balanza, peso)
                             VALUES (".$cod_balanza.", ".$peso.")";

                if ($res = mysqli_query($enlace, $q_insert)) {
                }
              }
            }
          }
        }

      // 2. Actualiza el peso obtenido
        $q_update = "UPDATE interfaces_pesosbalanzas
                        SET peso = ".$peso."
                      WHERE cod_balanza = ".$cod_balanza;

        if ($res = mysqli_query($enlace, $q_update)) {
          $estado = 1;
        }
        else{
          $estado = 0;
        }

      echo json_encode(array('estado' => $estado));

      break;

    case 'get_Peso':
      $r = array();
      $estado = 0;

      $cod_balanza = $_GET["cod_balanza"];
      $peso = 0;

      $q_peso = "SELECT peso
                   FROM interfaces_pesosbalanzas
                  WHERE cod_balanza = ".$cod_balanza;

      if ($res_peso = mysqli_query($enlace, $q_peso)) {
        if (mysqli_num_rows($res_peso) > 0) {
          $estado = 1;

          while($row_peso = mysqli_fetch_assoc($res_peso)) {
            $peso = $row_peso["peso"];
          }
        }
      }

      echo json_encode(array('estado' => $estado, 'peso' => $peso));

      break;

    case 'get_AnalisisAAS_RackImportarResultados':
      $r = array();
      $estado = 0;

      // Recuperando parámetros
        $cod_equipo = $_GET["cod_equipo"];

      // Obteniendo el Rack solicitado para la importación
        $q_datos = "SELECT id_rack
                      FROM interfaces_aas_importarresultados
                     WHERE cod_equipo = ".$cod_equipo;

        if ($res_datos = mysqli_query($enlace, $q_datos)) {
          if (mysqli_num_rows($res_datos) > 0) {
            $estado = 1;

            while($row_datos = mysqli_fetch_assoc($res_datos)) {
              array_push($r, $row_datos);
            }
          }
        }

      echo json_encode(array('estado' => $estado, 'registros' => $r));

      break;

    case 'get_AnalisisAAS_RackImportarResultados_Muestras':
      $r = array();
      $estado = 0;

      $id_rack = $_GET["id_rack"];

      // Detalle de muestras
        $d = 1;
        $analisis_muestra = '';
        $orden_elementos = '';

        $q_detalle = "SELECT  C.Id AS ID_CABECERA,
                              C.num_vaso,
                              C.cod_interno,
                              UPPER(REPLACE(C.analisis_muestra, '-', ' | ')) AS analisis_muestra,
                              C.peso_muestra,
                              C.fechahora_pesomuestra,
                              C.usuario_pesomuestra,
                              C.tiene_reanalisis,
                              C.is_reanalisis,
                              C.item_reanalisis,
                              TR.descripcion AS TIPO_REPLICA,
                              TM.descripcion AS TIPO_MUESTRA,
                              EM.descripcion AS ESTADO_MUESTRA,
                              IFNULL(AAR.orden_elementos, '') AS orden_elementos
                        FROM  analisislq_aas_cabecera C
                              LEFT JOIN tbconfig_tiporeanalisis TR ON C.cod_tiporeanalisis = TR.Id
                              INNER JOIN analisislq_aas_rack AAR ON C.Id_Rack = AAR.Id
                              INNER JOIN recepcion_ensayos_detalle D ON C.id_ensayosdetalle = D.Id
                              INNER JOIN tbconfig_tiposmuestra TM ON D.cod_tipomuestra = TM.Id
                              INNER JOIN tbconfig_estadosmuestra EM ON D.cod_estadomuestra = EM.Id
                       WHERE  C.id_rack = ".$id_rack."
                      ORDER BY C.Id";

        if ($res_detalle = mysqli_query($enlace, $q_detalle)) {
          if (mysqli_num_rows($res_detalle) > 0) {
            $estado = 1;

            while($row_detalle = mysqli_fetch_assoc($res_detalle)) {
              array_push($r, $row_detalle);
            }
          }
        }

      echo json_encode(array('estado' => $estado, 'registros' => $r));

      break;

    case 'get_AutorizacionVisita':
      $r = array();
      $estado = 0;
      $is_autorizado = 0;

      // Recupera parámetros
        $id_visita = mysqli_real_escape_string($enlace, $_GET["id_visita"]);

      // Obtiene datos
        $q_autorizacion = "SELECT is_autorizado
                             FROM controlingreso_visitas
                            WHERE Id = ".$id_visita."
                              AND autorizado_fechahoraregistro IS NOT NULL";

        if ($res_autorizacion = mysqli_query($enlace, $q_autorizacion)){
          if (mysqli_num_rows($res_autorizacion) > 0) {
            $estado = 1;

            while($row_autorizacion = mysqli_fetch_assoc($res_autorizacion)) {
              $is_autorizado = $row_autorizacion["is_autorizado"];
            }
          }
        }

      echo json_encode(array('estado' => $estado, 'is_autorizado' => $is_autorizado));

      break;

		default:

			# code...

			break;

	}

?>