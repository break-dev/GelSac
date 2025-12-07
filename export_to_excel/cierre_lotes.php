
<?php
  header("Content-Type: application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=leyes_gestionar.xls");
  header("Pragma: no-cache");
  header("Expires: 0");

  include("../cnx/cnx.php");
  include("../global/variables.php");
  include("../global/auxiliares.php");

  $fecha_inicio = $_GET["fecha_inicio"];
  $fecha_fin = $_GET["fecha_fin"];
  $estado = $_GET["filtro_estado"];
  $filtro_lote = isset($_GET["filtro_lote"]) ? $_GET["filtro_lote"] : "";

  $arr_lotes = "";
  if ($filtro_lote != "") {
    $arr = explode(",", $filtro_lote);
    foreach ($arr as $l) {
      $arr_lotes .= "'$l',";
    }
    $arr_lotes = substr($arr_lotes, 0, -1);
  }

  echo "<table border=1 style='border-collapse: collapse; font-size: 10pt'>";
  echo "<thead>";
  echo "<tr style='background-color:#816951; color:#ffffff; font-weight:bold;'>";
  echo "<th colspan='2'>Información Lote</th>";

  $cab_grupos = [];
  $analitos = [];

  $q_grupos = "SELECT GA.id_grupo, G.tiene_tipo
               FROM tb_leyes_grupos_analitos GA
               INNER JOIN tb_leyes_grupos G ON G.Id = GA.id_grupo
               WHERE G.estado = 'A'
               GROUP BY GA.id_grupo";
  $r_grupos = mysqli_query($enlace, $q_grupos);

  while ($g = mysqli_fetch_assoc($r_grupos)) {
    $cab_grupos[] = $g;
    $idg = $g["id_grupo"];

    $q_a = "SELECT A.abv FROM tb_leyes_grupos_analitos GA
            INNER JOIN tb_ensayos_analisis A ON A.Id = GA.id_elemento
            WHERE GA.id_grupo = $idg AND A.estado = 'A'
            ORDER BY GA.orden";
    $r_a = mysqli_query($enlace, $q_a);
    $temp = [];

    while ($a = mysqli_fetch_assoc($r_a)) {
      $temp[] = $a["abv"];
    }
    $analitos[$idg] = $temp;

    $colspan = count($temp) * 2;
    if ($g["tiene_tipo"] == "1") {
      echo "<th rowspan='2'>Tipo</th>";
    }
    echo "<th colspan='$colspan'>" . implode(" / ", $temp) . "</th>";
  }
  echo "</tr>";

  echo "<tr style='background-color:#219992; color:#ffffff;'>";

  foreach ($cab_grupos as $g) {
    if ($g["tiene_tipo"] == "1") {
      echo ""; // ya añadido antes
    }
    foreach ($analitos[$g["id_grupo"]] as $abv) {
      echo "<th>Valor</th><th>Promedio</th>";
    }
  }

  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

  $q_lotes = "SELECT L.ccod_Lote, L.dFechaCreacion
              FROM catalogolotes L
              WHERE L.cEstado_Registro = 'A'";

  if ($arr_lotes != "") {
    $q_lotes .= " AND L.ccod_Lote IN ($arr_lotes)";
  } else {
    $q_lotes .= " AND DATE(L.dFechaCreacion) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    if ($estado != 99) {
      $q_lotes .= " AND L.ccod_Lote " . (($estado == 1) ? "IN" : "NOT IN") . " (SELECT cod_lote FROM tb_leyes_analisis_cierre)";
    }
  }

  $q_lotes .= " ORDER BY L.dFechaCreacion DESC";

  $r_lotes = mysqli_query($enlace, $q_lotes);

  while ($l = mysqli_fetch_assoc($r_lotes)) {
    echo "<tr>";
    echo "<td>" . $l["ccod_Lote"] . "</td>";
    echo "<td>" . $l["dFechaCreacion"] . "</td>";

    foreach ($cab_grupos as $g) {
      $idg = $g["id_grupo"];
      $tiene_tipo = $g["tiene_tipo"];

      if ($tiene_tipo == "1") {
        $q_tipo = "SELECT porc_" . strtolower($analitos[$idg][0]) . "_tipo AS tipo
                   FROM import_resultadosleyes_detalle
                   WHERE cod_interno = '" . $l["ccod_Lote"] . "' LIMIT 1";
        $r_tipo = mysqli_query($enlace, $q_tipo);
        $tipo = "";
        if ($t = mysqli_fetch_assoc($r_tipo)) {
          $tipo = $t["tipo"];
        }
        echo "<td>$tipo</td>";
      }

      foreach ($analitos[$idg] as $abv) {
        $abv_l = strtolower($abv);
        $q_val = "SELECT porc_$abv_l AS valor
                  FROM import_resultadosleyes_detalle
                  WHERE cod_interno = '" . $l["ccod_Lote"] . "' LIMIT 1";
        $r_val = mysqli_query($enlace, $q_val);
        $valor = "0";
        if ($v = mysqli_fetch_assoc($r_val)) {
          $valor = $v["valor"];
        }
        echo "<td>$valor</td><td>$valor</td>";
      }
    }

    echo "</tr>";
  }

  echo "</tbody></table>";
?>
