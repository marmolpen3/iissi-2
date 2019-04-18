<?php
session_start();

if (!$_SESSION["admin"]) {
    Header("Location: index.php");
}

include_once("../models/gestionBD.php");
include_once("../models/gestionTutores.php");
include_once("../includes/functions.php");

if ($_REQUEST["submit"] == "insert") {
    $nuevoTutor["nombre"] = $_REQUEST["nombre"];
    $nuevoTutor["apellidos"] = $_REQUEST["apellidos"];
    $nuevoTutor["dni"] = $_REQUEST["dni"];
    $nuevoTutor["fechaNacimiento"] = getFechaBD($_REQUEST["fechaNacimiento"]);
    $nuevoTutor["email"] = $_REQUEST["email"];
    $nuevoTutor["telefono"] = $_REQUEST["telefono"];
    $nuevoTutor["direccion"] = $_REQUEST["direccion"];
    $nuevoTutor["localidad"] = $_REQUEST["localidad"];
    $nuevoTutor["provincia"] = $_REQUEST["provincia"];
    $nuevoTutor["cp"] = $_REQUEST["cp"];
    $nuevoTutor["participante"] = $_REQUEST["participante"];

    $conexion = crearConexionBD();
    insertarParticipante($conexion, $nuevoParticipante);
    cerrarConexionBD($conexion);
    Header("Location: ../tutores.php");
} else if ($_REQUEST["submit"] == 'delete') {
    $conexion = crearConexionBD();
    eliminarParticipante($conexion, $_REQUEST["dni"]);
    cerrarConexionBD($conexion);
    Header("Location: ../tutores.php");
} else if ($_REQUEST["submit"] == 'edit') {
    $tutor["nombre"] = $_REQUEST["nombre"];
    $tutor["apellidos"] = $_REQUEST["apellidos"];
    $tutor["dni"] = $_REQUEST["dni"];
    $tutor["fechaNacimiento"] = getFechaBD($_REQUEST["fechaNacimiento"]);
    $tutor["email"] = $_REQUEST["email"];
    $tutor["telefono"] = $_REQUEST["telefono"];
    $tutor["direccion"] = $_REQUEST["direccion"];
    $tutor["localidad"] = $_REQUEST["localidad"];
    $tutor["provincia"] = $_REQUEST["provincia"];
    $tutor["cp"] = $_REQUEST["cp"];

    $conexion = crearConexionBD();
    actualizarParticipante($conexion, $participante);
    cerrarConexionBD($conexion);
    Header("Location: ../perfilTutor.php?oid_part=" . $_REQUEST["oid_tut"]);
}

/*
$errores = validarAltaParticipante($nuevoParticipante);

if (count($errores) > 0) {
    $_SESSION["errores"] = $errores;
    Header("Location: nuevoParticipante.php");
} else {
    // todo OK, accion de inserción
}
*/
?>