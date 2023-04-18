<?php
require '../../includes/app.php';

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

sesionUsuario();
$db = conectarDB();

//Consulta a la bases de datos
$consulta = " SELECT * FROM vendedores; ";
$resultado = mysqli_query($db, $consulta);

//Arreglo que almacenara los errores 
$errores = Propiedad::getErrores();

//Variables que guardaran la información del formulario
$titulo = "";
$precio = "";
$descripcion = "";
$habitaciones = "";
$wc = "";
$estacionamiento = "";
$vendedorId = "";
$creado = date("Y/m/d");
$imagen = "";


// ----Ejecuto el código después que se envíe el formulario----

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $propiedad = new Propiedad($_POST);

    $imagen = $_FILES['imagen'];

    if ($imagen['tmp_name']) {
        //Guardamos el tipo
        $imagenType = $imagen["type"];
        //Dividimos type en un array para concatenar la extension
        $extension = explode("/", $imagenType);
        //Generar un nombre único con su extension
        $nombreImagen = md5(uniqid(rand(), true)) . "." . $extension[1];
        //Realizar rezize a la img con intervention
        $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }


    $errores = $propiedad->validar();


    //Revisar que el arreglo de errores este vació
    if (empty($errores)) {
        try {
            $resultado = $propiedad->guardar();

            if (!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

            $image->save(CARPETA_IMAGENES . $nombreImagen);
            header('Location: /admin?resultado=1');
        } catch (Throwable $ex) {
            echo 'No se pudo insertar los datos. Error : ' . $ex->getMessage() . '<br>' . ' Código: ' . $ex->getCode();
            exit;
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>

    <!--Mostrar errores -->
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach ?>

    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Nombre completo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Nombre" value="<?php echo $titulo ?>"> <!-- //El value mostrara los datos almacenados en las variables -->

            <label for="precio">Telefono:</label>
            <input type="number" id="precio" name="precio" placeholder="Telefono" value="<?php echo $precio ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripción del estudiante:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Detalles del estudiantes</legend>
            <label for="habitaciones">Piso:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Eje: 3" min="1" max="9" value="<?php echo $habitaciones ?>">

            <label for="wc">Curso:</label>
            <input type="number" id="wc" name="wc" placeholder="Eje: 3" min="1" max="9" value="<?php echo $wc ?>">

            <label for="estacionamiento">Seccion:</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Eje: 3" min="1" max="9" value="<?php echo $estacionamiento ?>">
        </fieldset>

        <fieldset>
            <legend>Encargado</legend>
            <select name="vendedorId">
                <option value="">--Seleccione un encargado</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?> //Devuelve información en array
                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : '' ?> value="<?php echo $vendedor['id'] ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido'] ?> </option>
                <?php endwhile ?>
            </select>
        </fieldset>
        <input type="submit" value="Agregar estudiante" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>