<?php

use App\Propiedad;

require '../includes/app.php';
sesionUsuario();
$db = conectarDB();

//Implementar metodo para obtener propiedades
$propiedades = Propiedad::viewAll();
$resultado =  $_GET['resultado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $id = filter_var($id, FILTER_VALIDATE_INT);
  if ($id) {
    $query = " SELECT imagen FROM propiedades WHERE id = ${id}; ";
    $resultado = mysqli_query($db, $query);
    $propiedad = mysqli_fetch_assoc($resultado);
    unlink('../imagenes/' . $propiedad['imagen']);
  }

  $query = " DELETE FROM propiedades WHERE id = ${id}; ";
  $resultado = mysqli_query($db, $query);
  if ($resultado) {
    header('Location: /admin?resultado=3');
  }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
  <h1>Administrador de estudiantes</h1>
  <?php if (intval($resultado) === 1) :  ?>
    <p class="alerta ocultar exito">Estudiante creado correctamente</p>
  <?php elseif (intval($resultado) === 2) :  ?>
    <p class="alerta ocultar exito">Estudiante actualizado correctamente</p>
  <?php elseif (intval($resultado) === 3) :  ?>
    <p class="alerta ocultar exito">Estudiante eliminado correctamente</p>
  <?php endif ?>

  <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nuevo estudiante</a>

  <table class="propiedades">
    <thead>
      <tr>
        <th>ID</th>
        <th>NOMBRE COMPLETO</th>
        <th>IMAGEN</th>
        <th>TELEFONO</th>
        <th>ACCIONES</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($propiedades as $propiedad) : ?>
        <tr>
          <td><?php echo $propiedad->id; ?></td>
          <td><?php echo $propiedad->titulo; ?></td>
          <td><img class="imagen-propiedades" src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="Casa en ola playa"></td>
          <td><?php echo $propiedad->precio;?></td>
          <td class="columns">
            <form method="POST" class="w-100">
              <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">
              <input class="boton-rojo-block" type="submit" value="Eliminar">
            </form>
            <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>

<?php
incluirTemplate('footer');
?>