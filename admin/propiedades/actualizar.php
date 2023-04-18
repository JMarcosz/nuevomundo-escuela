<?php
require '../../includes/app.php';
sesionUsuario();
$db = conectarDB();

//Validar ID
$id = $_GET["id"];
$id = filter_var($id, FILTER_VALIDATE_INT);
if (!$id) {
  header('Location: /admin');
}

//Obtener resultados
$consulta = " SELECT * FROM propiedades WHERE id= ${id}; ";
$resultado = mysqli_query($db, $consulta);
$propiedad = mysqli_fetch_assoc($resultado);

//Consulta a la bases de datos
$consulta = " SELECT * FROM vendedores; ";
$resultado = mysqli_query($db, $consulta);

//Arreglo que almacenara los errores 
$errores = [];

//Variables que rellan con la bd 
$titulo = $propiedad["titulo"];
$precio = $propiedad["precio"];
$descripcion = $propiedad["descripcion"];
$habitaciones = $propiedad["habitaciones"];
$wc = $propiedad["wc"];
$estacionamiento = $propiedad["estacionamiento"];
$vendedorId = $propiedad["vendedorId"];
$imagenPropiedad = $propiedad["imagen"];
$imagen = "";

// ----Ejecuto el código después que se envíe el formulario----

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $titulo =  mysqli_real_escape_string($db, $_POST["titulo"]); //mysqli_real_escape_string sanitiza la información, y necesita dos parámetros, db y la información
  $precio = mysqli_real_escape_string($db, $_POST["precio"]);
  $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
  $habitaciones = mysqli_real_escape_string($db, $_POST["habitaciones"]);
  $wc = mysqli_real_escape_string($db, $_POST["wc"]);
  $estacionamiento = mysqli_real_escape_string($db, $_POST["estacionamiento"]);
  $vendedorId = mysqli_real_escape_string($db, $_POST["vendedor"]);
  $creado = date("Y/m/d");
  $imagen = $_FILES["imagen"];

  //Validación del formulario
  if (!$titulo) {
    $errores[] = "Debes añadir un titulo";
  }

  if (!$precio) {
    $errores[] = "El precio es obligatorio";
  }

  if (strlen($descripcion) < 50) {
    $errores[] = "La descripción es obligatoria  y debe tener mínimo 50 caracteres.";
  }

  if (!$habitaciones) {
    $errores[] = "La cantidad de habitaciones es obligatoria";
  }

  if (!$wc) {
    $errores[] = "La cantidad de baños es obligatoria";
  }

  if (!$estacionamiento) {
    $errores[] = "La cantidad de estacionamientos es obligatoria";
  }

  if (!$vendedorId) {
    $errores[] = "Elige un vendedor";
  }

  //Validad por tamaño
  $medida = 1000 * 1000;
  if ($imagen["size"] > $medida) {
    $errores[] = "La imagen es muy pesada, debe ser menor a 1MB";
  }

  //Revisar que el arreglo de errores este vació
  if (empty($errores)) {

    //Crear carpeta
    $carpetaImagenes = '../../imagenes/';

    if (!is_dir($carpetaImagenes)) {
      mkdir($carpetaImagenes);
    }

    $nombreImagen = '';
    if ($imagen['name']) {
      unlink($carpetaImagenes . $propiedad["imagen"]);

      //Guardamos el tipo
      $imagenType = $imagen["type"];
      $extension = explode("/", $imagenType);
      $nombreImagen = md5(uniqid(rand(), true)) . "." . $extension[1];
      move_uploaded_file($imagen["tmp_name"], $carpetaImagenes . $nombreImagen);
    } else {
      $nombreImagen = $propiedad['imagen'];
    }

    //Queries para insertar en la base de datos
    $query = " UPDATE propiedades SET titulo = '${titulo}', precio = ${precio}, imagen = '${nombreImagen}' , descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = ${wc}, estacionamiento = ${estacionamiento}, vendedorId = ${vendedorId} WHERE id = ${id}; ";

    //Capturar errores en caso de que no se inserten los datos
    try {
      $resultado = mysqli_query($db, $query);

      //Redireccionar a la pagina principal;
      header('Location: /admin?resultado=2');
    } catch (Throwable $ex) {
      echo 'Ha ocurrido un problema con la bases de datos, no se pudo insertar los datos. Error : ' . $ex->getMessage() . '<br>' . ' Código de error: ' . $ex->getCode();
      exit;
    }
  }
}
incluirTemplate('header');
?>

<main class="contenedor seccion">
  <h1>Actualizar estudiante</h1>
  <a href="/admin" class="boton boton-verde">Volver</a>

  <!--Mostrar errores -->
  <?php foreach ($errores as $error) : ?>
    <div class="alerta error">
      <?php echo $error; ?>
    </div>
  <?php endforeach ?>

  <form class="formulario" method="POST" enctype="multipart/form-data">
    <fieldset>
      <legend>Información General</legend>
      <label for="titulo">Nombre:</label>
      <input type="text" id="titulo" name="titulo" placeholder="Nombre" value="<?php echo $titulo; ?>"> <!-- //El value mostrara los datos almacenados en las variables -->

      <label for="precio">Telefono:</label>
      <input type="number" id="precio" name="precio" placeholder="Telefono" value="<?php echo $precio; ?>">

      <label for="imagen">Imagen:</label>
      <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
      <img class="imagen-small" src="/imagenes/<?php echo $imagenPropiedad; ?>" alt="">

      <label for="descripcion">Descripción del estudiante:</label>
      <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
    </fieldset>

    <fieldset>
      <legend>Información del estudiante</legend>
      <label for="habitaciones">Piso:</label>
      <input type="number" id="habitaciones" name="habitaciones" placeholder="Eje: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

      <label for="wc">Curso:</label>
      <input type="number" id="wc" name="wc" placeholder="Eje: 3" min="1" max="9" value="<?php echo $wc; ?>">

      <label for="estacionamiento">Sección:</label>
      <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Eje: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
    </fieldset>

    <fieldset>
      <legend>Encargado</legend>
      <select name="vendedor">
        <option value="">--Seleccione un encargado</option>
        <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?> //Devuelve información en array
          <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : '' ?> value="<?php echo $vendedor['id'] ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?> </option>
        <?php endwhile ?>
      </select>
    </fieldset>
    <input type="submit" value="Actualizar estudiante" class="boton boton-verde">
  </form>
</main>

<?php
incluirTemplate('footer');
?>