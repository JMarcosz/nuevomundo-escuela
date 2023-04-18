document.addEventListener("DOMContentLoaded", function () {
  eventListeners();
  darkMode();
  borraMensaje();
});
function darkMode() {
  const botonDarkMode = document.querySelector(".dark-boton");
  const darkModePreferences = window.matchMedia("(prefers-color-scheme: Dark)");
  console.log(darkModePreferences.matches);

  if (darkModePreferences.matches) {
    document.body.classList.add("dark-mode");
  } else {
    document.body.classList.remove("dark-mode");
  }
  darkModePreferences.addEventListener("change", function () {
    if (darkModePreferences.matches) {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  });

  botonDarkMode.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");

    //Para que el modo elegido se quede guardado en local-storage
    if (document.body.classList.contains("dark-mode")) {
      localStorage.setItem("modo-oscuro", "true");
    } else {
      localStorage.setItem("modo-oscuro", "false");
    }
  });

  //Obtenemos el modo del color actual
  if (localStorage.getItem("modo-oscuro") === "true") {
    document.body.classList.add("dark-mode");
  } else {
    document.body.classList.remove("dark-mode");
  }
}

function eventListeners() {
  const mobileMenu = document.querySelector(".menu-hamburguesa");
  mobileMenu.addEventListener("click", navegacionResponsive);
}

function navegacionResponsive() {
  const navegacion = document.querySelector(".nav");
  navegacion.classList.toggle("mostrar");
}

function borraMensaje() {
  const mensajeConfirm = document.querySelector(".ocultar");
  if (mensajeConfirm !== null) {
    setTimeout(function () {
      const padre = mensajeConfirm.parentElement;
      padre.removeChild(mensajeConfirm);
    }, 2500);
    console.log("Hay mensaje de error");
  } else {
    console.log("No hay mensaje de error");
  }
}
