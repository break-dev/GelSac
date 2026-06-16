<?php
  header("Content-Type: application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=leyes_gestionar.xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("../cnx/cnx.php");

  $fecha_inicio = $_GET["fecha_inicio"];
  $fecha_fin = $_GET["fecha_fin"];
  $estado = $_GET["filtro_estado"];
  $filtro_lote = isset($_GET["filtro_lote"]) ? $_GET["filtro_lote"] : "";

  // Construir filtro de lotes si aplica
  $arr_lotes = "";
  if ($filtro_lote != "") {
    $arr = explode(",", $filtro_lote);
    foreach ($arr as $l) {
      $arr_lotes .= "'$l',";
    }
    $arr_lotes = substr($arr_lotes, 0, -1);
  }

  // Obtener lotes de base de datos
  $q_lotes = "SELECT L.id_CatalogoLotes as Id,
                     L.ccod_Lote,
                     L.dFechaCreacion,
                     L.id_UsuarioCreacion,
                     COUNT(I.cod_interno) as cantidad_importar
                FROM import_resultadosleyes_detalle I
                INNER JOIN catalogolotes L ON (I.cod_interno = L.ccod_Lote)
              WHERE L.cEstado_Registro = 'A'";

  if (strlen($arr_lotes) > 0){
    $q_lotes .= "   AND L.ccod_Lote IN ($arr_lotes)";
  } else {
    $q_lotes .= "   AND DATE(L.dFechaCreacion) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    if ($estado != 99){
      $q_lotes .= "   AND L.ccod_Lote " . (($estado == 1) ? 'IN' : 'NOT IN') . " (SELECT cod_lote FROM tb_leyes_analisis_cierre)";
    }
  }

  $q_lotes .= " GROUP BY L.ccod_Lote";

  // Cargar lotes históricos desde el JSON
  $json_path = dirname(__DIR__) . '/apis/repo_old/cierre_leyes.json';
  $historical_lotes = [];
  if (file_exists($json_path)) {
    $historical_data = json_decode(file_get_contents($json_path), true);
    if (is_array($historical_data)) {
      foreach ($historical_data as $item) {
        $fecha_item = substr($item["creacion"], 0, 10);
        
        $match = false;
        if (strlen($arr_lotes) > 0) {
          $lotes_permitidos = explode(',', str_replace("'", "", $arr_lotes));
          if (in_array($item["codigo"], $lotes_permitidos)) {
            $match = true;
          }
        } else {
          if ($fecha_item >= $fecha_inicio && $fecha_item <= $fecha_fin) {
            if ($estado == 99) {
              $match = true;
            } else if ($estado == 1) {
              $match = true;
            } else if ($estado == 0) {
              $match = false;
            }
          }
        }

        if ($match) {
          $historical_lotes[$item["codigo"]] = [
            "ccod_Lote" => $item["codigo"],
            "dFechaCreacion" => $item["creacion"],
            "id_UsuarioCreacion" => "HISTORICO",
            "is_historico" => true,
            "item_data" => $item
          ];
        }
      }
    }
  }

  $res_lotes = mysqli_query($enlace, $q_lotes);
  $res_rows = [];
  if ($res_lotes) {
    while ($row = mysqli_fetch_assoc($res_lotes)) {
      $cod = $row["ccod_Lote"];
      if (isset($historical_lotes[$cod])) {
        $row["is_historico"] = true;
        $row["item_data"] = $historical_lotes[$cod]["item_data"];
        unset($historical_lotes[$cod]);
      } else {
        $row["is_historico"] = false;
      }
      $res_rows[] = $row;
    }
  }

  foreach ($historical_lotes as $hl) {
    $res_rows[] = [
      "ccod_Lote" => $hl["ccod_Lote"],
      "dFechaCreacion" => $hl["dFechaCreacion"],
      "id_UsuarioCreacion" => $hl["id_UsuarioCreacion"],
      "is_historico" => true,
      "item_data" => $hl["item_data"]
    ];
  }

  echo "<table border=1 style=\"border-collapse: collapse; font-size: 12px\">";
  echo "<thead>";
  echo "<tr style='background-color:#219992;color:#ffffff;'><th rowspan=2>Lote</th><th rowspan=2>Fecha</th>";

  // Obtener cabeceras dinámicas
  $arr_id_leyes_grupo = array();
  $q_grupos = "SELECT DISTINCT GA.id_grupo, G.tiene_tipo
                 FROM tb_leyes_grupos_analitos GA
                 INNER JOIN tb_leyes_grupos G ON G.Id = GA.id_grupo
                WHERE G.estado = 'A'";
  $res_grupos = mysqli_query($enlace, $q_grupos);

  while($rg = mysqli_fetch_assoc($res_grupos)) {
    $id_grupo = $rg["id_grupo"];
    $tiene_tipo = $rg["tiene_tipo"];

    if ($tiene_tipo == 1) {
      echo "<th rowspan=2 style='background-color:#816951;color:#ffffff;'>Tipo</th>";
    }

    $q_elem = "SELECT A.abv FROM tb_leyes_grupos_analitos GA
                 INNER JOIN tb_ensayos_analisis A ON A.Id = GA.id_elemento
                WHERE GA.id_grupo = $id_grupo AND A.estado = 'A'
                ORDER BY GA.orden";
    $res_elem = mysqli_query($enlace, $q_elem);
    $cant = mysqli_num_rows($res_elem);

    foreach ($res_elem as $re) {
      echo "<th colspan=2 style='background-color:#816951;color:#ffffff;'>{$re["abv"]}</th>";
    }
  }

  echo "</tr>";
  echo "<tr style='background-color:#219992;color:#ffffff;'>";

  mysqli_data_seek($res_grupos, 0); // reset
  while($rg = mysqli_fetch_assoc($res_grupos)) {
    $id_grupo = $rg["id_grupo"];
    $tiene_tipo = $rg["tiene_tipo"];

    $q_elem = "SELECT A.abv FROM tb_leyes_grupos_analitos GA
                 INNER JOIN tb_ensayos_analisis A ON A.Id = GA.id_elemento
                WHERE GA.id_grupo = $id_grupo AND A.estado = 'A'
                ORDER BY GA.orden";
    $res_elem = mysqli_query($enlace, $q_elem);

    foreach ($res_elem as $re) {
      echo "<th>Valor</th><th>Promedio</th>";
    }
  }

  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

  foreach ($res_rows as $row) {
    $cod_lote = $row["ccod_Lote"];
    $is_historico = $row["is_historico"];
    $historical_item = $is_historico ? $row["item_data"] : null;

    echo "<tr><td>{$cod_lote}</td><td>{$row["dFechaCreacion"]}</td>";

    // Por cada grupo y elemento obtener tipo y valor
    mysqli_data_seek($res_grupos, 0);
    while($rg = mysqli_fetch_assoc($res_grupos)) {
      $id_grupo = $rg["id_grupo"];
      $tiene_tipo = $rg["tiene_tipo"];

      $q_elem = "SELECT A.abv FROM tb_leyes_grupos_analitos GA
                   INNER JOIN tb_ensayos_analisis A ON A.Id = GA.id_elemento
                  WHERE GA.id_grupo = $id_grupo AND A.estado = 'A'
                  ORDER BY GA.orden";
      $res_elem = mysqli_query($enlace, $q_elem);

      foreach ($res_elem as $re) {
        $abv = strtolower($re["abv"]);
        
        if ($historical_item !== null) {
          // Lote Histórico
          if ($tiene_tipo == 1) {
            $tipo = isset($historical_item["tipo"]) ? $historical_item["tipo"] : "";
            echo "<td>$tipo</td>";
          }

          $val = "";
          if ($id_grupo == 3 && $abv == 'newau') {
            $val = $historical_item["ley_consolidada_au"];
          } else if ($id_grupo == 3 && $abv == 'newag') {
            $val = $historical_item["ley_consolidada_ag"];
          } else if ($id_grupo == 4 && $abv == 'h2o') {
            $val = $historical_item["humedad"];
          } else if ($id_grupo == 5 && $abv == 'recup') {
            $val = $historical_item["recuperacion"];
          }
          
          echo "<td>$val</td><td>$val</td>";
        } else {
          // Lote Normal
          if ($tiene_tipo == 1) {
            $q_tipo = "SELECT porc_{$abv}_tipo as tipo FROM import_resultadosleyes_detalle WHERE cod_interno = '$cod_lote' LIMIT 1";
            $res_tipo = mysqli_query($enlace, $q_tipo);
            $tipo = "";
            if ($rt = mysqli_fetch_assoc($res_tipo)) {
              $tipo = $rt["tipo"];
            }
            echo "<td>$tipo</td>";
          }

          $q_valor = "SELECT porc_{$abv} as valor FROM import_resultadosleyes_detalle WHERE cod_interno = '$cod_lote' LIMIT 1";
          $res_valor = mysqli_query($enlace, $q_valor);
          $valor = "";
          if ($rv = mysqli_fetch_assoc($res_valor)) {
            $valor = $rv["valor"];
          }

          // para este resumen, promedio = valor (no hay más de uno)
          echo "<td>$valor</td><td>$valor</td>";
        }
      }
    }

    echo "</tr>";
  }

  echo "</tbody>";
  echo "</table>";
?>
