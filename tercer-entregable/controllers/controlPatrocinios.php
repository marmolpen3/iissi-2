<?php
session_start();

if (!$_SESSION["admin"] && !isset($_SESSION["login"])) {
    Header("Location: index.php");
}

include_once("../models/gestionBD.php");
include_once("../models/gestionActividades.php");

if ($_REQUEST["submit"] == 'inscribir') {
    $patrocinio["cif"] = $_REQUEST["cif"];
    $patrocinio["oid_act"] = $_REQUEST["oid_act"];

    $conexion = crearConexionBD();
    $patrocinadores = getPatrocinios($conexion, $patrocinio["oid_act"]);
    $errores = validarPatrocinio($patrocinio, $patrocinadores);
    if (count($errores)> 0) {
    	$_SESSION["errores"] = $errores;
    	Header("Location: ../formPatrocinio.php?oid_act=" . $patrocinio["oid_act"]);
    } else {
    	addPatrocinio($conexion, $patrocinio);
    	cerrarConexionBD($conexion);
        Header("Location: ../perfilActividad.php?oid_act=" . $patrocinio["oid_act"]);
    }
    
}

Header("Location: ../perfilActividad.php?oid_act=" . $patrocinio["oid_act"]);

?>