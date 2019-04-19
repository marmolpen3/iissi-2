<?php
session_start();

if (!$_SESSION["admin"]) {
    Header("Location: index.php");
}

include_once("models/gestionBD.php");
include_once("models/gestionPatrocinadores.php");
include_once("includes/functions.php");

// si viene a la vista para editar datos de un patrocinador registrado
if (isset($_GET["edit"]) && isset($_GET["cif"])) {
    $conexion = crearConexionBD();
    $patrocinador = getpatrocinador($conexion, $_REQUEST["cif"]);
    cerrarConexionBD($conexion);
} else {
    $patrocinador["NOMBRE"] = "";
    $patrocinador["CIF"] = "";
    $patrocinador["EMAIL"] = "";
    $patrocinador["TELEFONO"] = "";
    $patrocinador["DIRECCION"] = "";
    $patrocinador["LOCALIDAD"] = "";
    $patrocinador["PROVINCIA"] = "";
    $patrocinador["CODIGOPOSTAL"] = "";
}

// TODO hay que recoger algo de vuelta de la validacion
if (isset($_SESSION["errores"])) {
    $errores = $_SESSION["errores"];
    unset($_SESSION["errores"]);
}

$page_title = "Nuevo patrocinador";
include_once("includes/head.php");
?>

<body>
    <?php include_once("includes/header.php"); ?>
    <main class="container">
        <div class="content">
            <div class="content__module">
                <div class="module-title">
                    <!-- Si vista de edición, muestra Editar patrocinador, si no, Nuevo patrocinador -->
                    <h1><?php echo isset($_GET["edit"]) ? "Editar" : "Nuevo" ?> patrocinador</h1>
                </div>
                <div class="form">
                    <fieldset>
                        <legend>Datos del patrocinador</legend>
                        <!-- TODO mostrar errores de validación -->
                        <form action="controllers/controlPatrocinadores.php" method="POST">
                            <!-- input hidden para el cif que identifica al patrocinador a editar -->
                            <input type="hidden" name="cif" value="<?php echo $patrocinador["CIF"] ?>">
                            <div class="form-row">
                                <input type="text" name="nombre" value="<?php echo $patrocinador["NOMBRE"] ?>" placeholder="Nombre" autofocus="autofocus" />
                                <input type="text" name="cif" value="<?php echo $patrocinador["CIF"] ?>" placeholder="CIF" <?php if (isset($_GET["edit"])) echo "readonly" ?>/>
                            </div>
                            <div class="form-row">
                                <?php if ($patrocinador["EMAIL"] != "") { ?>
                                    <input type="email" name="email" value="<?php echo $patrocinador["EMAIL"] ?>" placeholder="Email" />
                                <?php } else { ?>
                                    <input type="email" name="email" placeholder="Email" />
                                <?php } ?>
                                <input type="text" name="telefono" value="<?php echo $patrocinador["TELEFONO"] ?>" placeholder="Teléfono" />
                            </div>
                            <div class="form-row">
                                <input type="text" name="direccion" value="<?php echo $patrocinador["DIRECCION"] ?>" placeholder="Dirección" />
                                <input type="text" name="localidad" value="<?php echo $patrocinador["LOCALIDAD"] ?>" placeholder="Localidad" />
                                <input type="text" name="provincia" value="<?php echo $patrocinador["PROVINCIA"] ?>" placeholder="Provincia" />
                                <input type="text" name="cp" value="<?php echo $patrocinador["CODIGOPOSTAL"] ?>" placeholder="Código postal" />
                            </div>
                            <div class="form-row right">
                            <?php if (!isset($_GET["edit"])) { ?>
                                <button type="reset" class="btn cancel">Cancelar</button>
                            <?php } ?>
                                <button type="submit" class="btn primary" name="submit" value="<?php echo isset($_GET["edit"]) ? "edit" : "insert";?>">Guardar</button>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>