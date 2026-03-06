/**
 * 1. FUNCIÓN GLOBAL: cargarContenido
 * Se define fuera para que sea accesible desde los clics del menú
 * y desde el éxito del formulario (AJAX).
 */
function cargarContenido(modulo) {
  const contentArea = document.getElementById("content-area");
  if (!contentArea) return;

  fetch(`./views/modulos/${modulo}.php`, {
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => {
      if (response.status === 401) {
        window.location.href =
          typeof RUTA_BASE !== "undefined" ? RUTA_BASE : "/";
        return null;
      }
      return response.text();
    })
    .then((html) => {
      if (html) {
        // Validación de seguridad para no cargar el layout entero
        if (html.includes('<nav id="sidebar">')) {
          console.error("Error: Se intentó cargar el layout completo.");
          return;
        }
        contentArea.innerHTML = html;
      }
    })
    .catch((err) => console.error("Error crítico al cargar módulo:", err));
}

/**
 * 2. EVENTOS INICIALES (DOM Cargado)
 */
document.addEventListener("DOMContentLoaded", function () {
  // --- SIDEBAR ---
  const sidebar = document.getElementById("sidebar");
  const btn = document.getElementById("sidebarCollapse");

  if (btn && sidebar) {
    btn.addEventListener("click", () => sidebar.classList.toggle("active"));
  }

  // --- LINKS AJAX DEL MENÚ ---
  const navLinks = document.querySelectorAll(".nav-link-ajax");
  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const modulo = this.getAttribute("data-modulo");
      if (!modulo) return;

      // Gestionar estado activo visualmente
      navLinks.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");

      cargarContenido(modulo);
    });
  });
});

/**
 * 3. DELEGACIÓN DE EVENTOS PARA EL MODAL (LECTURA)
 * Se usa delegación en el 'document' porque el modal puede ser cargado dinámicamente.
 */
document.addEventListener("show.bs.modal", function (event) {
  if (event.target && event.target.id === "modalEditarMesa") {
    const modal = event.target;
    const button = event.relatedTarget;
    const uuid = button.getAttribute("data-uuid");

    // Referencias internas
    const inputUuid = modal.querySelector("#input_uuid");
    const displayNombre = modal.querySelector("#display_nombre_mesa");
    const selectEstado = modal.querySelector("#select_estado");
    const imgElement = modal.querySelector("#display_imagen_mesa");
    const placeholder = modal.querySelector("#qr_placeholder");

    if (displayNombre) displayNombre.textContent = "Cargando...";

    fetch(`obtenerMesaSelect?uuid=${uuid}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 4) {
          // Llenar campos
          if (displayNombre) displayNombre.textContent = partes[0];
          if (selectEstado) selectEstado.value = partes[1];
          if (inputUuid) inputUuid.value = partes[2];

          // Lógica de limpieza de ruta para la imagen
          let rutaRaw = partes[3];
          let rutaLimpia = rutaRaw.replace(/^[\/\.\s]+/, "");

          if (rutaLimpia && rutaLimpia !== "null") {
            imgElement.src = rutaLimpia;
            imgElement.classList.remove("d-none");
            placeholder.classList.add("d-none");
          } else {
            imgElement.classList.add("d-none");
            placeholder.classList.remove("d-none");
          }
        }
      })
      .catch((err) => console.error("Error al obtener datos de mesa:", err));
  }
});

//Consulta para obtener solo el uuid y el nombre para la elimiacion de la mesa seleccionada
document.addEventListener("show.bs.modal", function (event) {
  if (event.target && event.target.id === "modalEliminarMesa") {
    const modal = event.target;
    const button = event.relatedTarget;
    const uuid = button.getAttribute("data-uuid");

    // Referencias internas
    const inputUuid = modal.querySelector("#input_uuid");
    const displayNombre = modal.querySelector("#display_nombre_mesa");

    if (displayNombre) displayNombre.textContent = "Cargando...";

    fetch(`obtenerMesaSelect?uuid=${uuid}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 4) {
          // Llenar campos
          if (displayNombre) displayNombre.textContent = partes[0];
          if (inputUuid) inputUuid.value = partes[2];
        }
      })
      .catch((err) => console.error("Error al obtener datos de mesa:", err));
  }
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
      mensaje = "No se pudo completar la acción en la base de datos ya que el registro cuenta con historial.";
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

/**
 * --- 3. ENVÍO DE FORMULARIO POR AJAX (ACTUALIZACIÓN) ---
 */
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarMesa form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("actualizaEstadoMesa", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarMesa");
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if (modalInstance) modalInstance.hide();

          // 2. CAMBIAR LA URL SIN RECARGAR LA PÁGINA
          const nuevaURL = window.location.pathname + "?success=ok";
          window.history.pushState({ path: nuevaURL }, "", nuevaURL);

          // 3. RECARGAR EL CONTENIDO (Mesas)
          const activeLink = document.querySelector(".nav-link-ajax.active");
          if (activeLink) {
            cargarContenido(activeLink.getAttribute("data-modulo"));
          }

          // 4. LANZAR LA FUNCIÓN GLOBAL
          verificarAlertasURL();
        } else {
          Swal.fire("Error", "No se pudo actualizar: " + data, "error");
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

/**
 * --- GESTIÓN DE ELIMINACIÓN DE MESA (SUBMIT DEL FORMULARIO) ---
 */
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEliminarMesa form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    // Feedback visual
    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Eliminando...';

    fetch("deleteMesa", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        // Obtenemos el texto y el status para decidir qué alerta mostrar
        return response.text().then((text) => ({
          status: response.status,
          body: text.trim(),
        }));
      })
      .then((res) => {
        // Cerramos el modal en cualquier caso (éxito o error controlado)
        const modalEl = document.getElementById("modalEliminarMesa");
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();

        if (res.body === "success") {
          // --- CASO ÉXITO ---
          const nuevaURL = window.location.pathname + "?success=ok";
          window.history.pushState({ path: nuevaURL }, "", nuevaURL);

          const activeLink = document.querySelector(".nav-link-ajax.active");
          if (activeLink) {
            cargarContenido(activeLink.getAttribute("data-modulo"));
          }
          verificarAlertasURL();

        } else {
          // --- CASO ERROR (Llaves foráneas o fallos) ---
          let errorTipo = "falla";
          if (res.body.includes("db_relacion")) {
            errorTipo = "db_relacion";
          }

          const nuevaURL = window.location.pathname + "?error=" + errorTipo;
          window.history.pushState({ path: nuevaURL }, "", nuevaURL);

          verificarAlertasURL();
        }
      })
      .catch((err) => {
        console.error("Error crítico:", err);
        Swal.fire("Error", "Fallo de conexión total", "error");
      })
      .finally(() => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalHTML;
      });
  }
});





////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Obtener datos modal estados generales
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;
  
  // Unificamos: Si el ID es el de editar O el de eliminar
  if (modal && (modal.id === "modalEditarEstadoGral" || modal.id === "modalEliminarEstadoGral")) {
    
    const button = event.relatedTarget;
    const id_estado_gen = button.getAttribute("data-id");

    // Referencias a los campos del modal (buscamos dentro del modal actual)
    const input_id_estado = modal.querySelector("#input_id_gen");
    const input_nombre_estado = modal.querySelector("#input_nombre_estado");
    const displayNombreHeader = modal.querySelector("#display_nombre_estado_titulo");

    // Feedback visual inicial
    if (displayNombreHeader) displayNombreHeader.textContent = "Cargando...";

    fetch(`obtenerEstadoGenSelect?idEstado=${id_estado_gen}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 2) {
          const id = partes[0];
          const nombre = partes[1];

          // 1. Asignar el ID al campo oculto
          if (input_id_estado) input_id_estado.value = id;

          // 2. Asignar el nombre al input (solo si existe en el modal)
          if (input_nombre_estado) input_nombre_estado.value = nombre;

          // 3. Actualizar el título o etiqueta del modal
          if (displayNombreHeader) displayNombreHeader.textContent = nombre;
        }
      })
      .catch((err) => {
        console.error("Error al obtener datos del estado:", err);
        if (displayNombreHeader) displayNombreHeader.textContent = "Error";
      });
  }
});


//Envio del mensaje AJAX para editar el nombre del estado
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarEstadoGral form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("updateEstadoGen", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarEstadoGral");
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if (modalInstance) modalInstance.hide();

          // 2. CAMBIAR LA URL SIN RECARGAR LA PÁGINA
          const nuevaURL = window.location.pathname + "?success=ok";
          window.history.pushState({ path: nuevaURL }, "", nuevaURL);

          // 3. RECARGAR EL CONTENIDO (Mesas)
          const activeLink = document.querySelector(".nav-link-ajax.active");
          if (activeLink) {
            cargarContenido(activeLink.getAttribute("data-modulo"));
          }

          // 4. LANZAR LA FUNCIÓN GLOBAL
          verificarAlertasURL();
        } else {
          Swal.fire("Error", "No se pudo actualizar: " + data, "error");
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



//Para el Modal de eliminar estado general
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal de estados
  if (e.target && e.target.closest("#modalEliminarEstadoGral form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("deleteEstadoGen", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();
        
        // Cerramos el modal
        const modalEl = document.getElementById("modalEliminarEstadoGral");
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();

        let nuevaURL = window.location.pathname;

        if (res === "success") {
          // ÉXITO
          nuevaURL += "?success=ok";
        } else {
          // ERROR (Detectamos si es por relación o falla general)
          let errorTipo = res.includes("db_relacion") ? "db_relacion" : "falla";
          nuevaURL += "?error=" + errorTipo;
        }

        // 1. Cambiamos la URL
        window.history.pushState({ path: nuevaURL }, "", nuevaURL);

        // 2. Recargamos la tabla (módulo actual)
        const activeLink = document.querySelector(".nav-link-ajax.active");
        if (activeLink) {
          cargarContenido(activeLink.getAttribute("data-modulo"));
        }

        // 3. Disparamos el SweetAlert
        verificarAlertasURL();
      })
      .catch((err) => {
        console.error("Error crítico:", err);
        Swal.fire("Error", "Fallo de conexión total", "error");
      })
      .finally(() => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalHTML;
      });
  }
});




////////////////////////////////////////////////////////////////////////////////////////////
//Obtener los datos del estado del platillo
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;
  
  // Unificamos: Si el ID es el de editar O el de eliminar
  if (modal && (modal.id === "modalEditarEstadoPlatillo" || modal.id === "modalEliminarEstadoPlatillo")) {
    
    const button = event.relatedTarget;
    const estado_id_platillo = button.getAttribute("data-platillo");

    // Referencias a los campos del modal (buscamos dentro del modal actual)
    const input_id_estado_platillo = modal.querySelector("#input_id_platillo");
    const input_nombre_estado_platillo = modal.querySelector("#input_nombre_platillo");
    const displayNombreHeaderPlatillo = modal.querySelector("#display_nombre_platillo_titulo");

    // Feedback visual inicial
    if (displayNombreHeaderPlatillo) displayNombreHeaderPlatillo.textContent = "Cargando...";

    fetch(`obtenerEstadoPlatilloSelect?idPlatillo=${estado_id_platillo}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 2) {
          const id = partes[0];
          const nombre = partes[1];

          // 1. Asignar el ID al campo oculto
          if (input_id_estado_platillo) input_id_estado_platillo.value = id;

          // 2. Asignar el nombre al input (solo si existe en el modal)
          if (input_nombre_estado_platillo) input_nombre_estado_platillo.value = nombre;

          // 3. Actualizar el título o etiqueta del modal
          if (displayNombreHeaderPlatillo) displayNombreHeaderPlatillo.textContent = nombre;
        }
      })
      .catch((err) => {
        console.error("Error al obtener datos del estado:", err);
        if (displayNombreHeaderPlatillo) displayNombreHeaderPlatillo.textContent = "Error";
      });
  }
});

//Actualizacion para el model de editar estado del platillo
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarEstadoPlatillo form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("updateEstadoPlatillo", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarEstadoPlatillo");
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if (modalInstance) modalInstance.hide();

          // 2. CAMBIAR LA URL SIN RECARGAR LA PÁGINA
          const nuevaURL = window.location.pathname + "?success=ok";
          window.history.pushState({ path: nuevaURL }, "", nuevaURL);

          // 3. RECARGAR EL CONTENIDO (Mesas)
          const activeLink = document.querySelector(".nav-link-ajax.active");
          if (activeLink) {
            cargarContenido(activeLink.getAttribute("data-modulo"));
          }

          // 4. LANZAR LA FUNCIÓN GLOBAL
          verificarAlertasURL();
        } else {
          Swal.fire("Error", "No se pudo actualizar: " + data, "error");
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


//Para el Modal de eliminar estado del platillo
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal de estados
  if (e.target && e.target.closest("#modalEliminarEstadoPlatillo form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("deleteEstadoPlatillo", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();
        
        // Cerramos el modal
        const modalEl = document.getElementById("modalEliminarEstadoPlatillo");
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();

        let nuevaURL = window.location.pathname;

        if (res === "success") {
          // ÉXITO
          nuevaURL += "?success=ok";
        } else {
          // ERROR (Detectamos si es por relación o falla general)
          let errorTipo = res.includes("db_relacion") ? "db_relacion" : "falla";
          nuevaURL += "?error=" + errorTipo;
        }

        // 1. Cambiamos la URL
        window.history.pushState({ path: nuevaURL }, "", nuevaURL);

        // 2. Recargamos la tabla (módulo actual)
        const activeLink = document.querySelector(".nav-link-ajax.active");
        if (activeLink) {
          cargarContenido(activeLink.getAttribute("data-modulo"));
        }

        // 3. Disparamos el SweetAlert
        verificarAlertasURL();
      })
      .catch((err) => {
        console.error("Error crítico:", err);
        Swal.fire("Error", "Fallo de conexión total", "error");
      })
      .finally(() => {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalHTML;
      });
  }
});