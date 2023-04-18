<?php
require 'includes/app.php';
incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Contacto</h1>
    <picture>
        <source srcset="build/img/destacada3.webp" type="image/webp">
        <img width="300" height="200" src="build/img/destacada3.jpg" alt="Imagen Contacto">
    </picture>

    <h2>Llene el formulario de contacto</h2>

    <form class="formulario">
        <fieldset>
            <legend>Información Personal</legend>
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Escribe tu nombre" id="nombre">

            <label for="email">E-mail</label>
            <input type="email" placeholder="correo@correo.com" id="email">


            <label for="telefono">Teléfono</label>
            <input type="tel" placeholder="Tu teléfono" id="telefono">

            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" spellcheck="value" placeholder="Escribe algo"></textarea>
        </fieldset>

        <fieldset>
            <legend>Información sobre la propiedad</legend>
            <label for="opciones">Vende o compra</label>
            <select id="opciones">
                <option value="" disabled selected>-- Seleccione --</option>
                <option value="Compra">Compra</option>
                <option value="Vende">Vende</option>
            </select>
            <label for="presupuesto">Precio y/o presupuesto</label>
            <input type="number" placeholder="Monto" id="presupuesto">
        </fieldset>

        <fieldset>
            <legend>Información sobre contacto</legend>
            <p>Cómo desea ser contactado?</p>
            <div class="forma-contacto">
                <label for="contactar-telefono">Teléfono</label>
                <input name="contacto" type="radio" value="telefono" id="contactar-telefono">

                <label for="contactar-email">E-mail</label>
                <input name="contacto" type="radio" value="email" id="contactar-email">
            </div>
            <p>Si eligió teléfono, elija la hora y fecha para ser contactado</p>

            <label for="fecha">Fecha</label>
            <input type="date" id="fecha">

            <label for="hora">Hora</label>
            <input type="time" id="hora" min="09:00" max="18:00">
        </fieldset>
        <div class="alinear-derecha">
            <input type="submit" value="Enviar" class="boton-verde">
        </div>
    </form>
</main>

<?php
incluirTemplate('footer');
?>