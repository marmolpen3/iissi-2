<?php
session_start();

if (!$_SESSION["admin"]) {
    Header("Location: index.php");
}

if (!isset($_GET["oid_tut"])) {
    Header("Location: tutores.php");
}

include_once("models/gestionBD.php");
include_once("models/gestionParticipantes.php");
include_once("models/gestionTutores.php");
include_once("includes/functions.php");

$conexion = crearConexionBD();
$tutor = getTutor($conexion, $_GET["oid_tut"]);
$participante = getPartTut($conexion, $tutor["OID_TUT"]);
cerrarConexionBD($conexion);

$page_title = "Tutor: " . $tutor["NOMBRE"] . " " . $tutor["APELLIDOS"];
include_once("includes/head.php");
?>

<body>
    <?php include_once("includes/header.php"); ?>     
    <main class="container">
        <div class="content__module">
            <div class="row">
                <div class="col-12 col-tab-12">
                    <div class="module-title">
                        <h1><?php echo $tutor["NOMBRE"] . " " . $tutor["APELLIDOS"]; ?></h1>
                        <form action="controllers/controlTutores.php" method="POST">
                            <input type="hidden" name="dni" value="<?php echo $tutor["DNI"] ?>">
                            <a class="btn primary" href="formTutor.php?edit=true&oid_tut=<?php echo $tutor["OID_TUT"]; ?>">Editar</a>
                            <button class="btn cancel" type="submit" name="submit" value="delete">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-tab-12">
                    <div class="module-title">
                        <h2>Datos personales</h2>
                    </div>
                    <div class="content-tab">
                        <table class="tab vertical">
                            <tr>
                                <th>DNI</th>
                                <td><?php echo $tutor["DNI"]; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha nacimiento</th>
                                <td><?php echo getFechaFormatView($tutor["FECHANACIMIENTO"]) ?></td>
                            </tr>
                        <tr>
                                <th>Direcci??n</th>
                                <td><?php echo $tutor["DIRECCION"]; ?></td>
                            </tr>
                            <tr>
                                <th>C??digo postal</th>
                                <td><?php echo $tutor["CODIGOPOSTAL"]; ?></td>
                            </tr>
                            <tr>
                                <th>Localidad</th>
                                <td><?php echo $tutor["LOCALIDAD"]; ?></td>
                            </tr>
                            <tr>
                                <th>Provincia</th>
                                <td><?php echo $tutor["PROVINCIA"]; ?></td>
                            </tr>
                            <tr>
                                <th>Tel??fono</th>
                                <td><?php echo $tutor["TELEFONO"]; ?></td>
                            </tr>
                        <?php if (isset($tutor["EMAIL"])) { ?>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $tutor["EMAIL"]; ?></td>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>
            <?php if ($participante["OID_TUT"]==$tutor["OID_TUT"]) { ?>
                <div class="col-6 col-tab-12">
                    <div class="module-title">
                        <h2>Participante</h2>
                    </div>
                    <div class="content-tab">
                        <table class="tab vertical">
                            <tr>
                                <th>DNI</th>
                                <td><?php echo $participante["DNI"]; ?></td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td><?php echo $participante["NOMBRE"] . " " . $participante["APELLIDOS"]; ?></td>
                            </tr>
                            <tr>
                                <th>Tel??fono</th>
                                <td><?php echo $participante["TELEFONO"]; ?></td>
                            </tr>
                            <?php if (isset($participante["EMAIL"])) { ?>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $participante["EMAIL"]; ?></td>
                            </tr>
                        <?php }?>
                        </table>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div> <!--end module-->
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>