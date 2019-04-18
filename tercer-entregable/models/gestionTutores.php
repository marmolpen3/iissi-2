<?php

function getTutores($conexion) {
    try {
        $consulta = "SELECT * FROM TUTORESLEGALES TUT LEFT JOIN PERSONAS PER ON TUT.DNI = PER.DNI ORDER BY PER.NOMBRE ASC";
        $stmt = $conexion->query($consulta);
        return $stmt;
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: excepcion.php");
    }
}

function getAllTutores() {
    $query = "SELECT * FROM TUTORESLEGALES TUT LEFT JOIN PERSONAS PER ON TUT.DNI = PER.DNI";
    return $query;
}

function getTutor($conexion, $oid_tut) {
    try {
        $consulta = "SELECT * FROM TUTORESLEGALES TUT LEFT JOIN PERSONAS PER ON TUT.DNI = PER.DNI WHERE TUT.OID_TUT =:oid_tut";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_tut', $oid_tut);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: excepcion.php");
    }
}

function getPartTut($conexion, $oid_tut) {
    try {
        $consulta = "SELECT * FROM TUTORESLEGALES TUT LEFT JOIN PARTICIPANTES PART ON TUT.OID_tut= PART.OID_tut LEFT JOIN PERSONAS P ON PART.dni= P.dni";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':oid_tut', $oid_tut);
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        $_SESSION["excepcion"] = $e->GetMessage();
        Header("Location: ../excepcion.php");
    }
}

?>