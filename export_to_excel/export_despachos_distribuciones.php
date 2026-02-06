<?php
include('../cnx/cnx.php');

// query para obtener todos los despachos
$q_despachos = "
SELECT
    dsp.id AS id_despacho,
    dsp.id_planta,
    dsp.correlativo,
    prov.documento AS documento_proveedor,
    prov.razon_social,
    pln.descripcion AS descripcion_planta,
    pln.ruc AS ruc_planta,
    pln.direccionguia_segundotramo AS direccion_planta,
    dsp.created_at AS fecha_registro,
    (
        SELECT
            COUNT(dsd.id)
        FROM
            despacho_detalle dsd
        WHERE
            dsp.id = dsd.id_despacho AND dsd.is_blending = 1
	) AS blending_usados,
    (
        SELECT
            COUNT(dsd.id)
        FROM
            despacho_detalle dsd
        WHERE
            dsp.id = dsd.id_despacho AND dsd.is_blending = 0
    ) AS lotes_usados,
	dsp.estado
FROM
    despacho dsp
INNER JOIN tb_clientes prov ON
    dsp.id_proveedor = prov.Id
INNER JOIN tbconfig_plantas pln ON
    pln.Id = dsp.id_planta
ORDER BY
    dsp.correlativo
DESC
";

// query para obtener el detalle de un despacho
$q_detalle_despacho = "
SELECT
    dsd.id AS id_despacho_detalle,
    CASE 
        WHEN dsd.is_blending = 1 THEN(
            SELECT
                bln.correlativo
            FROM
                blending bln
            WHERE
                bln.id = dsd.id_mineral
        ) 
        WHEN dsd.is_blending = 0 THEN(
            SELECT
                vcd.cod_gel
            FROM
                catalogolotes lot
            INNER JOIN valorizacion_compramineral_detalle vcd ON
                vcd.cod_lote = lot.ccod_Lote
            INNER JOIN valorizacion_compramineral vc ON
                vc.Id = vcd.id_valorizacion
            INNER JOIN comprobante_pago cp ON
                cp.id_valorizacion = vc.Id
            WHERE
                cp.estado IN('A', 'B', 'C') AND lot.id_CatalogoLotes = dsd.id_mineral
        )
    END AS codigo,
    dsd.is_blending,
    dsd.peso_tomado,
    dsd.peso_actual,
    dsd.peso_actual_log,
    (dsd.peso_tomado - dsd.peso_actual) AS peso_distribuido,
    dsd.estado
FROM
    despacho_detalle dsd
WHERE
    dsd.id_despacho = $id_despacho
ORDER BY
    codigo;
";


// query para obtener las distribuciones de un despacho
$q_distribuciones_despacho = "
SELECT
    dist.id AS id_distribucion,
    dist.id_unidad,
    dist.id_despacho,
    trn.documento AS documento_transportista,
    trn.razon_social AS nombre_transportista,
    tpv.descripcion AS tipo_vehiculo,
    uni.cplaca AS placa,
    dist.segunda_placa,
    uni.nCapacidad AS capacidad,
    (
        SELECT
            SUM(dstd.peso_tomado)
        FROM
            distribucion_detalle dstd
        WHERE
            dstd.id_distribucion = dist.id
    ) AS peso_acumulado,
    dist.fecha_estimada,
    dist.created_at AS fecha_registro,
    dist.estado
FROM
    distribucion dist
INNER JOIN transporte uni ON
    uni.id_transporte = dist.id_unidad
INNER JOIN tb_clientes trn ON
    trn.Id = uni.id_Transportista
INNER JOIN tbconfig_tipovehiculo tpv ON
    tpv.Id = uni.id_tipovehiculo
WHERE
    dist.estado = 'A' AND dist.id_despacho = $id_despacho
ORDER BY
    dist.fecha_estimada ASC,
    dist.id ASC;
";