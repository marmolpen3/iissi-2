<?php

function getPatrocinadores($conexion) {
    try {
        $consulta = "SELECT * FROM INSTITUCIONES WHERE espatrocinador = 1";
        $stmt = $conexion->query($consulta);
        return $stmt;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function getAllPatrocinadores() {
    $query = "SELECT * FROM INSTITUCIONES WHERE espatrocinador = 1";
    return $query;
}

function searchPatrocinadores($conexion, $str) {
    $patrocinadores = getPatrocinadores($conexion);
    $res = "";
    foreach ($patrocinadores as $patr) {
        if (strpos(strtolower($patr["NOMBRE"]), $str) !== false) {
            $res[] = $patr;
        }
    }
    return $res;
}

function getPatrocinador($conexion, $cif) {
    try {
        $consulta = "SELECT * FROM INSTITUCIONES WHERE espatrocinador = 1 AND CIF =:cif";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':cif', $cif);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function insertarPatrocinador($conexion, $pat) {
    try {
        $stmt = $conexion->prepare("CALL REGISTRAR_PATROCINADOR(:cif, :nombre, :telefono, :direccion, :localidad, :provincia, :cp, :email)");
        $stmt->bindParam(':cif', $pat["cif"]);
        $stmt->bindParam(':nombre', $pat["nombre"]);
        $stmt->bindParam(':telefono', $pat["telefono"]);
        $stmt->bindParam(':direccion', $pat["direccion"]);
        $stmt->bindParam(':localidad', $pat["localidad"]);
        $stmt->bindParam(':provincia', $pat["provincia"]);
        $stmt->bindParam(':cp', $pat["cp"]);
        $stmt->bindParam(':email', $pat["email"]);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../execepion.php");
    }
}

function actualizarPatrocinador($conexion, $pat) {
    try {
        $stmt = $conexion->prepare("CALL ACT_INSTITUCION(:cif, :nombre, :telefono, :direccion, :localidad, :provincia, :cp, :email)");
        $stmt->bindParam(':cif', $pat["cif"]);
        $stmt->bindParam(':nombre', $pat["nombre"]);
        $stmt->bindParam(':telefono', $pat["telefono"]);
        $stmt->bindParam(':direccion', $pat["direccion"]);
        $stmt->bindParam(':localidad', $pat["localidad"]);
        $stmt->bindParam(':provincia', $pat["provincia"]);
        $stmt->bindParam(':cp', $pat["cp"]);
        $stmt->bindParam(':email', $pat["email"]);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function eliminarPatrocinador($conexion, $cif) {
    try {
        $stmt = $conexion->prepare("CALL ELIMINAR_INSTITUCION(:cif)");
        $stmt->bindParam(':cif', $cif);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}
function validarAltaPatrocinador($patrocinador){
    //validaci??n del dni
    if($patrocinador["cif"]==""){
        $errores[] = "<p>El CIF debe completarse</p>";
    }else if(!preg_match("/^[A-Z][0-9]{8}$/", $patrocinador["cif"])){
        $errores[] = "<p>El CIF debe contener una letra may??scula y 8 n??meros: " . $patrocinador["cif"] . "</p>";
    }
    //validaci??n del nombre
    if ($patrocinador["nombre"]=="") {
        $errores[] = "<p>El nombre debe completarse</p>";
    }
    //validaci??n del email
    if($patrocinador["email"]==""){ 
        $errores[] = "<p>El email debe completarse</p>";
    }else if(!filter_var($patrocinador["email"], FILTER_VALIDATE_EMAIL)){
        $errores[] = "<p>El email es incorrecto: " . $patrocinador["email"]. "</p>";
    }
    //validaci??n del n??mero de tel??fono
    if ($patrocinador["telefono"]=="") {
        $errores[] = "<p>El n??mero de tel??fono debe completarse</p>";
    }elseif (!preg_match("/^[0-9]{9}$/", $patrocinador["telefono"])) {
        $errores[] = "<p>El telefono debe contener 9 n??meros: ". $patrocinador["telefono"] ."</p>";
    }
    //validaci??n del c??digo postal
    if ($participante["cp"] != "") {
		if (!preg_match("/^[0-9]{5}$/", $participante["cp"])) {
        $errores[] = "<p>El c??digo postal debe contener 5 n??meros: ". $participante["cp"] ."</p>";
    	}
	}
    return $errores;
}
?>