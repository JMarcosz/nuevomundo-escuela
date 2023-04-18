<?php
require 'includes/app.php';
$db = conectarDB();

//Autenticar al usuario
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = "El email es obligatorio o no es valido";
    }

    if (!$password) {
        $errores[] = "El password es obligatorio o no es valido";
    }

    if (empty($errores)) {
        //revisar si el usuario existe
        $query = " SELECT * FROM usuarios WHERE email = '${email}' ";
        $resultado = mysqli_query($db, $query);

        if ($resultado->num_rows) {
            $usuario = mysqli_fetch_assoc($resultado);
            //verificar password
            $auth = password_verify($password, $usuario['password']);

            if ($auth) {
                //El usuario esta autenticado
                session_start();
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;
                header('Location: /admin');
            } else {
                $errores[] = "Contraseña incorrecta";
            }
        } else {
            $errores[] = "El usuario no existe";
        }
    }
}


incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar sesión</h1>

    <!--Mostrar errores -->

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach ?>

    <form method="POST" class="formulario">
        <fieldset>
            <legend>Email y Contraseña</legend>
            <label for="email">E-mail</label>
            <input name="email" type="email" placeholder="correo@correo.com" id="email">

            <label for="password">Contraseña</label>
            <input name="password" type="password" placeholder="Tu contraseña" id="password">
        </fieldset>
        <input type="submit" value="Iniciar Sesión" class="boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>