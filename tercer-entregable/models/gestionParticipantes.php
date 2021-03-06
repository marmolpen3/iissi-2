<?php

include_once("/gestionTutores.php");

function getParticipantes($conexion) {
    try {
        $consulta = "SELECT * FROM PARTICIPANTES PART LEFT JOIN PERSONAS PER ON PART.DNI = PER.DNI";
        $stmt = $conexion->query($consulta);
        return $stmt;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function getAllParticipantes() {
    $query = "SELECT * FROM PARTICIPANTES PART LEFT JOIN PERSONAS PER ON PART.DNI = PER.DNI";
    return $query;
}

function searchParticipantes($conexion, $str) {
    $participantes = getParticipantes($conexion);
    $res = "";
    foreach ($participantes as $part) {
        if (strpos(strtolower($part["APELLIDOS"]), $str) !== false) {
            $res[] = $part;
        }
    }
    return $res;
}

function getParticipante($conexion, $oid_part) {
    try {
        $consulta = "SELECT * FROM PARTICIPANTES PART LEFT JOIN PERSONAS PER ON PART.DNI = PER.DNI WHERE PART.OID_PART =:oid_part";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_part', $oid_part);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}
function validarOidPart($conexion, $oid_part){
    try {
        $consulta = "SELECT COUNT(*) FROM PARTICIPANTES WHERE OID_Part=:oid_part";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_part', $oid_part);
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

function getInformesMedicos($conexion, $oid_part) {
    try {
        $consulta = "SELECT * FROM INFORMESMEDICOS WHERE OID_PART =:oid_part ORDER BY FECHA";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_part', $oid_part);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function getHistorialParticipacion($conexion, $oid_part) {
    try {
        $consulta = "SELECT P.ubicacion AS Proj_lugar, A.OID_Act AS OID_Act, A.nombre AS Act_nombre, A.fechaInicio AS Act_fechainicio FROM PERSONAS NATURAL JOIN PARTICIPANTES NATURAL JOIN INSCRIPCIONES I LEFT JOIN ACTIVIDADES A ON A.OID_Act=I.OID_Act
        LEFT JOIN PROYECTOS P ON P.OID_Proj=A.OID_Proj
        WHERE OID_Part=:oid_part ORDER BY A.fechainicio DESC";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_part', $oid_part);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function getProximasActPart($conexion, $oid_part) {
    try {
        $consulta = "SELECT INS.*, ACT.*, PROJ.UBICACION FROM INSCRIPCIONES INS LEFT JOIN ACTIVIDADES ACT ON INS.OID_ACT=ACT.OID_ACT LEFT JOIN PROYECTOS PROJ ON ACT.OID_PROJ=PROJ.OID_PROJ WHERE INS.OID_PART=:oid_part AND ACT.FECHAINICIO >= (SYSDATE+5) ORDER BY ACT.FECHAINICIO ASC";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_part', $oid_part);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function getRecibos($conexion, $oid_part) {
    try {
        $consulta = "SELECT * FROM RECIBOS WHERE OID_Part =:oid_part";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_part', $oid_part);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function getRecibo($conexion, $oid_rec) {
    try {
        $consulta = "SELECT * FROM RECIBOS WHERE OID_Rec =:oid_rec";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_rec', $oid_rec);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function insertarParticipante($conexion, $part) {
    try {
        $stmt = $conexion->prepare("CALL REGISTRAR_PARTICIPANTE(:dni, :nombre, :apellidos, :fechaNacimiento, :direccion, :localidad, :provincia, :cp, :email, :telefono, '123456', :grado, null, :dni_tut)");
        $stmt->bindParam(':dni', $part["dni"]);
        $stmt->bindParam(':nombre', $part["nombre"]);
        $stmt->bindParam(':apellidos', $part["apellidos"]);
        $stmt->bindParam(':fechaNacimiento', $part["fechaNacimiento"]);
        $stmt->bindParam(':direccion', $part["direccion"]);
        $stmt->bindParam(':localidad', $part["localidad"]);
        $stmt->bindParam(':provincia', $part["provincia"]);
        $stmt->bindParam(':cp', $part["cp"]);
        $stmt->bindParam(':email', $part["email"]);
        $stmt->bindParam(':telefono', $part["telefono"]);
        $stmt->bindParam(':grado', $part["discapacidad"]);
        $stmt->bindParam(':dni_tut', $part["tutor"]);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../execepion.php");
    }
}

function actualizarParticipante($conexion, $part) {
    try {
        $stmt = $conexion->prepare("CALL ACT_PERSONA(:dni, :nombre, :apellidos, :fechaNacimiento, :direccion, :localidad, :provincia, :cp, :email, :telefono, '123456')");
        $stmt->bindParam(':dni', $part["dni"]);
        $stmt->bindParam(':nombre', $part["nombre"]);
        $stmt->bindParam(':apellidos', $part["apellidos"]);
        $stmt->bindParam(':fechaNacimiento', $part["fechaNacimiento"]);
        $stmt->bindParam(':direccion', $part["direccion"]);
        $stmt->bindParam(':localidad', $part["localidad"]);
        $stmt->bindParam(':provincia', $part["provincia"]);
        $stmt->bindParam(':cp', $part["cp"]);
        $stmt->bindParam(':email', $part["email"]);
        $stmt->bindParam(':telefono', $part["telefono"]);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function eliminarParticipante($conexion, $dni) {
    try {
        $stmt = $conexion->prepare("CALL ELIMINAR_PERSONA(:dni)");
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function insertarInforme($conexion, $inf) {
    try {
        $stmt = $conexion->prepare("CALL ADD_INFORMEMEDICO(:oid_part, :descripcion)");
        $stmt->bindParam(':oid_part', $inf["oid_part"]);
        $stmt->bindParam(':descripcion', $inf["descripcion"]);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function actualizarRecibo($conexion, $rec) {
    try {
        $stmt = $conexion->prepare("CALL ACT_RECIBO(:oid_rec, :vencimiento, :importe, :estado)");
        $stmt->bindParam(':oid_rec', $rec["oid_rec"]);
        $stmt->bindParam(':vencimiento', $rec["vencimiento"]);
        $stmt->bindParam(':importe', $rec["importe"]);
        $stmt->bindParam(':estado', $rec["estado"]);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

function validarAltaParticipante($participante, $conexion){
	//validaci??n del dni
    if (!$_REQUEST["submit"] == 'edit') {
	   if($participante["dni"]==""){
		  $errores[] = "<p>El DNI debe completarse</p>";
	   }else if(!preg_match("/^[0-9]{8}[A-Z]$/", $participante["dni"])){
		  $errores[] = "<p>El DNI debe contener 8 n??meros y una letra may??scula: " . $participante["dni"] . "</p>";
	   }
    }
	//validaci??n del nombre
	if ($participante["nombre"]=="") {
		$errores[] = "<p>El nombre debe completarse</p>";
    }
    //validaci??n de los apellidos
	if ($participante["apellidos"]=="") {
		$errores[] = "<p>Los apellidos deben completarse</p>";
    }
    //validaci??n del grado de discapacidad
    if (!$_REQUEST["submit"] == 'edit') {
        if ($participante["discapacidad"]=="") {
            $errores[] = "<p>El grado de discapacidad debe completarse</p>";
        }else if(!preg_match("/^0[,]{0,1}[0-9]{0,2}$/", $participante["discapacidad"] || $participante["discapacidad"] != "1") ){
		    $errores[] = "<p>El grado de discapacidad debe ser un n??mero decimal entre 0 y 1: " . $participante["discapacidad"] . "</p>";
	    }
    }
    //validaci??n de la fecha de nacimiento
    $fechaMin = date("Y/m/d", strtotime("now -5 year"));
    $fechaNac = formatFecha($participante["fechaNacimiento"]);
	if ($participante["fechaNacimiento"]=="") {
		$errores[] = "<p>La fecha de nacimiento debe completarse</p>";
	}elseif ($fechaNac > $fechaMin) {
        $errores[] = "<p>El participante debe tener al menos 5 a??os</p>";
    }
	//validaci??n del email
	if ($participante["email"] != "") {
        if(!filter_var($participante["email"], FILTER_VALIDATE_EMAIL)){
		$errores[] = "<p>El email es incorrecto: " . $participante["email"]. "</p>";
	    }
    }
    //Validaci??n tutor legal
    if (!$_REQUEST["submit"] == 'edit') {
        $consultaTutor= consultarTutor($conexion, $participante["tutor"]);
        if($consultaTutor == 0){
            $errores[]="<p>El tutor legal con DNI: ".$participante["tutor"]." no existe</p>";
        }
    }
	//validaci??n del n??mero de tel??fono
	if ($participante["telefono"]=="") {
		$errores[] = "<p>El n??mero de tel??fono debe completarse</p>";
	}elseif (!preg_match("/^[0-9]{9}$/", $participante["telefono"])) {
		$errores[] = "<p>El telefono debe contener 9 n??meros: ". $participante["telefono"] ."</p>";
	}
	//validaci??n del c??digo postal
    if ($participante["cp"] != "") {
	    if (!preg_match("/^[0-9]{5}$/", $participante["cp"])) {
            $errores[] = "<p>El c??digo postal debe contener 5 n??meros: ". $participante["cp"] ."</p>";
    	}
	}
	return $errores;
}

function validarAltaInforme($inf) {

    //validaci??n de la descripci??n
    if ($inf["descripcion"]=="") {
		$errores[] = "<p>La descripci??n debe completarse</p>";
    }

    return $errores;
}

function validarAltaRecibo($rec) {
    
    //validaci??n del estado del recibo
    if ($rec["estado"] != "pagado" && $rec["estado"] != "pendiente" && $rec["estado"] != "anulado") {
        $errores[] = "<p>El estado del recibo debe ser \"pagado\", \"pendiente\" o \"anulado\"</p>";
    }
    return $errores;
}


?>