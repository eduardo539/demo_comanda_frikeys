document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const btn = document.getElementById("sidebarCollapse");

  if (btn) {
    btn.addEventListener("click", function () {
      sidebar.classList.toggle("active");
    });
  }

  // Opcional: Marcar como activo el link actual según la URL
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll("#sidebar a");

  navLinks.forEach((link) => {
    if (
      link.getAttribute("href") !== "#" &&
      currentPath.includes(link.getAttribute("href"))
    ) {
      navLinks.forEach((l) => l.classList.remove("active"));
      link.classList.add("active");
    }
  });
});

/**
 * --- 1. FUNCIÓN GLOBAL DE ALERTAS ---
 * Esta función vive fuera para que sea visible desde el AJAX y desde la carga inicial.
 */
function verificarAlertasURL() {
  const params = new URLSearchParams(window.location.search);

  // --- CASO DE ÉXITO ---
  if (params.get("success") === "ok") {
    Swal.fire({
      title: "¡Operación Exitosa!",
      text: "Los cambios se han guardado correctamente.",
      icon: "success",
      confirmButtonColor: "#38b2ac",
      confirmButtonText: "Continuar",
    }).then(() => {
      limpiarURL();
    });
  }

  // --- CASO DE ERROR (Llaves foráneas, fallos de DB, etc.) ---
  else if (params.get("error")) {
    let titulo = "No se pudo realizar";
    let mensaje = "Ocurrió un error inesperado.";
    const codigoError = params.get("error");

    // Personalizamos el mensaje según el código que enviemos desde PHP
    if (codigoError === "db_relacion") {
      mensaje =
        "No se puede eliminar el registro porque tiene historial vinculado.";
    } else if (codigoError === "falla") {
      mensaje =
        "No se pudo completar la acción en la base de datos ya que el registro cuenta con historial.";
    }

    Swal.fire({
      title: titulo,
      text: mensaje,
      icon: "error",
      confirmButtonColor: "#d33",
      confirmButtonText: "Entendido",
    }).then(() => {
      limpiarURL();
    });
  }
}

// Función auxiliar para no repetir código de limpieza de URL
function limpiarURL() {
  window.history.replaceState({}, document.title, window.location.pathname);
}

/**
 * --- 2. CARGA INICIAL ---
 */
document.addEventListener("DOMContentLoaded", function () {
  // Al cargar la página, revisamos si ya viene con el parámetro en la URL
  verificarAlertasURL();
});

document.addEventListener("DOMContentLoaded", function () {
  const contentArea = document.getElementById("content-area");
  const links = document.querySelectorAll(".nav-link-ajax");

  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const modulo = this.getAttribute("data-modulo");
      if (!modulo) return;

      // Cambiar estado activo en el sidebar
      links.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");

      // Cargar contenido
      cargarContenido(modulo);
    });
  });

  // Dentro de admin_dashboard.js
  function cargarContenido(modulo) {
    const contentArea = document.getElementById("content-area");

    // Determinamos la carpeta según el módulo
    let carpeta = "cocina";
    if (modulo === "gestion_perfil") {
      carpeta = "modulos";
    }

    // Construimos la ruta dinámica
    const ruta = `./views/${carpeta}/${modulo}.php`;

    fetch(ruta, {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    })
      .then((response) => {
        if (!response.ok) throw new Error("Página no encontrada");
        return response.text();
      })
      .then((html) => {
        if (html) {
          contentArea.innerHTML = html;

          // Re-inicializamos la validación de password si cargamos el perfil
          if (modulo === "gestion_perfil") {
            if (typeof activarValidacionPassword === "function") {
              activarValidacionPassword();
            }
          }
        }
      })
      .catch((err) => {
        console.error("Error al cargar el módulo:", err);
        contentArea.innerHTML =
          '<div class="alert alert-danger">Error al cargar el contenido.</div>';
      });
  }
});

document.addEventListener("click", function (e) {
  // Buscamos el botón o el elemento más cercano con la clase btn-detalle
  const btn = e.target.closest(".btn-detalle");

  if (btn) {
    // Ahora 'btn' existe, podemos sacar los atributos
    const folio = btn.getAttribute("data-folio");
    const estado = btn.getAttribute("data-estado");
    const bodyModal = document.getElementById("bodyDetallePedido");

    // Mostramos el spinner de carga
    bodyModal.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Obteniendo comanda #${folio}...</p>
            </div>`;

    // Llamada AJAX con ambos parámetros
    fetch(
      `./views/cocina/ajax_detalle_pedido.php?folio=${folio}&estado=${encodeURIComponent(estado)}`,
    )
      .then((response) => response.text())
      .then((html) => {
        bodyModal.innerHTML = html;
      })
      .catch((err) => {
        bodyModal.innerHTML =
          '<div class="alert alert-danger">Error al cargar detalles.</div>';
      });
  }
});

document.addEventListener("click", function (e) {
  // Buscamos el botón con la clase que definiste
  const btnAccion = e.target.closest(".btn-accion-cocina");

  if (btnAccion) {
    // 1. Extraemos los datos del botón
    const folio = btnAccion.getAttribute("data-folio");
    const nuevoEstado = btnAccion.getAttribute("data-nuevo-estado");

    // 2. Confirmación visual (Opcional pero recomendado)
    const mensaje =
      nuevoEstado == "2" ? "¿Comenzar a preparar?" : "¿Marcar como terminado?";

    if (confirm(mensaje)) {
      // 3. Enviamos los datos por POST a tu RewriteRule
      const formData = new FormData();
      formData.append("folio", folio);
      formData.append("estado_id", nuevoEstado);

      fetch("updateEstadoPlatilloxFolio", {
        // Aquí usamos tu RewriteRule
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            // Cerramos modal y recargamos para ver los cambios en la tabla principal
            location.reload();
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch((err) => console.error("Error en la petición:", err));
    }
  }
});

///////////////////////////////////////////////////////////////////////////////////
//Actualizacion para el modal de editar usuario
document.addEventListener("submit", function (e) {
  // Usamos closest para asegurar que capturamos el formulario dentro del modal
  const form = e.target.closest("#modalNewPass form");
  if (e.target && form) {
    e.preventDefault();

    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');
    const originalHTML = btnSubmit.innerHTML;

    // Estado de carga
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

    // Usar RUTA_BASE para evitar problemas de carpetas /modulos/
    fetch("cambiarPasswUsuario", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) throw new Error("Error de red");
        return response.text();
      })
      .then((data) => {
        const respuesta = data.trim();

        if (respuesta === "success") {
          // 1. CERRAR MODAL (Primero que nada)
          const modalEl = document.getElementById("modalNewPass");
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if (modalInstance) modalInstance.hide();

          // 2. FEEDBACK VISUAL (SweetAlert)
          // Usamos .then() del Swal para que la recarga ocurra DESPUÉS de que el usuario vea el éxito
          Swal.fire({
            title: "¡Contraseña Cambiada!",
            text: "Tu contraseña ha sido actualizada correctamente.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false,
          }).then(() => {
            // 3. CAMBIAR URL SIN RECARGAR
            const nuevaURL = window.location.pathname + "?success=ok";
            window.history.pushState({ path: nuevaURL }, "", nuevaURL);

            // 4. RECARGAR CONTENIDO (Solo si es necesario)
            const activeLink = document.querySelector(".nav-link-ajax.active");
            if (activeLink) {
              cargarContenido(activeLink.getAttribute("data-modulo"));
            }

            // 5. ALERTAS GLOBALES
            if (typeof verificarAlertasURL === "function") {
              verificarAlertasURL();
            }
          });
        } else {
          // Si el servidor responde algo que no es "success"
          Swal.fire(
            "Atención",
            "No se pudo actualizar: " + respuesta,
            "warning",
          );
        }
      })
      .catch((err) => {
        console.error("Error en AJAX:", err);
        Swal.fire(
          "Error",
          "Fallo en la conexión. Revisa tu contraseña actual o internet.",
          "error",
        );
      })
      .finally(() => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalHTML;
      });
  }
});

document.addEventListener("submit", function (e) {
  // Cambiamos el selector para que apunte a tu formulario de perfil
  if (e.target && e.target.id === "formActualizaPerfil") {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

    fetch("actualizaPerfil", {
      // Asegúrate de que esta sea la ruta a tu PHP
      method: "POST",
      body: formData,
    })
      // MODIFICACIÓN RECOMENDADA
      .then((response) => {
        if (!response.ok)
          throw new Error("Error en servidor: " + response.status);
        return response.text();
      })
      .then((data) => {
        console.log("Respuesta cruda:", data); // Mira qué llega realmente
        if (data.trim() === "success") {
          Swal.fire({
            title: "¡Actualizado!",
            icon: "success",
            timer: 1500,
          }).then(() => {
            // MOVER LA RECARGA AQUÍ
            // Así esperas a que la alerta termine antes de refrescar el contenido
            const activeLink = document.querySelector(".nav-link-ajax.active");
            if (activeLink) {
              cargarContenido(activeLink.getAttribute("data-modulo"));
            }
          });
        } else {
          Swal.fire("Error", "Respuesta inesperada: " + data, "error");
        }
      })
      .catch((err) => {
        console.error("Error crítico en envío AJAX:", err);
        Swal.fire("Error", "Fallo en la conexión con el servidor", "error");
      })
      .finally(() => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalHTML;
      });
  }
});
