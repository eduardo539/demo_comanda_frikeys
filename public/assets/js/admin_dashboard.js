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
})

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
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Eliminando...';

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
          verificarAlertasURL()
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
})

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Obtener datos modal estados generales
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;

  // Unificamos: Si el ID es el de editar O el de eliminar
  if (
    modal &&
    (modal.id === "modalEditarEstadoGral" ||
      modal.id === "modalEliminarEstadoGral")
  ) {
    const button = event.relatedTarget;
    const id_estado_gen = button.getAttribute("data-id");

    // Referencias a los campos del modal (buscamos dentro del modal actual)
    const input_id_estado = modal.querySelector("#input_id_gen");
    const input_nombre_estado = modal.querySelector("#input_nombre_estado");
    const displayNombreHeader = modal.querySelector(
      "#display_nombre_estado_titulo",
    );

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
})

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
})

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
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

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
})

////////////////////////////////////////////////////////////////////////////////////////////
//Obtener los datos del estado del platillo
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;

  // Unificamos: Si el ID es el de editar O el de eliminar
  if (
    modal &&
    (modal.id === "modalEditarEstadoPlatillo" ||
      modal.id === "modalEliminarEstadoPlatillo")
  ) {
    const button = event.relatedTarget;
    const estado_id_platillo = button.getAttribute("data-platillo");

    // Referencias a los campos del modal (buscamos dentro del modal actual)
    const input_id_estado_platillo = modal.querySelector("#input_id_platillo");
    const input_nombre_estado_platillo = modal.querySelector(
      "#input_nombre_platillo",
    );
    const displayNombreHeaderPlatillo = modal.querySelector(
      "#display_nombre_platillo_titulo",
    );

    // Feedback visual inicial
    if (displayNombreHeaderPlatillo)
      displayNombreHeaderPlatillo.textContent = "Cargando...";

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
          if (input_nombre_estado_platillo)
            input_nombre_estado_platillo.value = nombre;

          // 3. Actualizar el título o etiqueta del modal
          if (displayNombreHeaderPlatillo)
            displayNombreHeaderPlatillo.textContent = nombre;
        }
      })
      .catch((err) => {
        console.error("Error al obtener datos del estado:", err);
        if (displayNombreHeaderPlatillo)
          displayNombreHeaderPlatillo.textContent = "Error";
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
})

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
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

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




///////////////////////////////////////////////////////////////////////////////////
//Obtener los datos del producto
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;

  if (modal && (modal.id === "modalEditarProducto" || modal.id === "modalEliminarProducto")) {
    const button = event.relatedTarget;
    const id_Producto = button.getAttribute("data-product"); 

    // Referencias corregidas
    const input_id = modal.querySelector("#input_id_producto");
    const input_nombre = modal.querySelector("#input_nombre_producto");
    const input_descripcion = modal.querySelector("#input_descripcion_producto");
    const input_precio = modal.querySelector("#input_precio_producto");
    const selectNomCategoria = modal.querySelector("#select_cat");
    const selectNomEstado = modal.querySelector("#select_est");
    
    // ESTOS DOS SON LA CLAVE PARA LA FOTO
    const img_previsualizacion = modal.querySelector("#img_producto_edit");
    const placeholder = modal.querySelector("#placeholder_img_edit");
    
    const displayNombre = modal.querySelector("#display_nombre_producto");

    if (displayNombre) displayNombre.textContent = "Cargando...";

    fetch(`obtenerProductoSelect?idProducto=${id_Producto}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 9) {
          if (input_id) input_id.value = partes[2].trim();
          if (input_nombre) input_nombre.value = partes[3].trim();
          if (displayNombre) displayNombre.textContent = partes[3].trim();
          if (input_descripcion) input_descripcion.value = partes[4].trim();
          if (input_precio) input_precio.value = partes[5].trim();

          // Asignar valores a los Selects (usando el ID que viene de la BD)
          if (selectNomCategoria) selectNomCategoria.value = partes[0].trim();
          if (selectNomEstado) selectNomEstado.value = partes[7].trim();

          // --- LÓGICA DE IMAGEN CORREGIDA ---
          if (img_previsualizacion && placeholder) {
            let rutaRaw = partes[6] ? partes[6].trim() : "";
            // Quitamos /../ public / etc. para que sea relativa a la raíz
            let rutaLimpia = rutaRaw.replace(/^(\.\.\/|\/)+/, "");

            if (rutaLimpia && rutaLimpia !== "null" && rutaLimpia !== "undefined") {
              img_previsualizacion.src = rutaLimpia;
              img_previsualizacion.classList.remove("d-none");
              placeholder.classList.add("d-none");
            } else {
              img_previsualizacion.src = "";
              img_previsualizacion.classList.add("d-none");
              placeholder.classList.remove("d-none");
            }
          }
        }
      })
      .catch((err) => {
        console.error("Error al cargar producto:", err);
      });
  }
});


//Actualizacion para el modal de editar el producto
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarProducto form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("actualizaProducto", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarProducto");
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
})

//Para el Modal de eliminar el producto seleccionado
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal de estados
  if (e.target && e.target.closest("#modalEliminarProducto form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("deleteProducto", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();

        // Cerramos el modal
        const modalEl = document.getElementById("modalEliminarProducto");
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







///////////////////////////////////////////////////////////////////////////////////
//Obtener los datos de la categoria
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;

  if (modal && (modal.id === "modalEditarCategoria" || modal.id === "modalEliminarCategoria")) {
    const button = event.relatedTarget;
    const id_categoria = button.getAttribute("data-categoria"); 

    // Referencias corregidas
    const input_id = modal.querySelector("#input_categoria_id");
    const input_nombre = modal.querySelector("#edit_nombre_categoria");
    const displayNombre = modal.querySelector("#display_nombre_categoria");

    if (displayNombre) displayNombre.textContent = "Cargando...";

    fetch(`obtenerCategoriaSelect?idCategoria=${id_categoria}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 2) {
          if (input_id) input_id.value = partes[0].trim();
          if (input_nombre) input_nombre.value = partes[1].trim();
          if (displayNombre) displayNombre.textContent = partes[1].trim();


        }
      })
      .catch((err) => {
        console.error("Error al cargar la categoria:", err);
      });
  }
});


//Actualizacion para el modal de editar la categoria
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarCategoria form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("actualizaCategoria", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarCategoria");
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
})

//Para el Modal de eliminar la categoria
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal de estados
  if (e.target && e.target.closest("#modalEliminarCategoria form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("deleteCategoria", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();

        // Cerramos el modal
        const modalEl = document.getElementById("modalEliminarCategoria");
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







///////////////////////////////////////////////////////////////////////////////////
//Obtener los datos del usuario
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;

  if (modal && (modal.id === "modalEditarUsuario" || modal.id === "modalEliminarUsuario" || modal.id === "modalRestablecerPass")) {
    const button = event.relatedTarget;
    const id_usuario = button.getAttribute("data-usuario"); 

    // Referencias corregidas
    const input_id = modal.querySelector("#input_usuario_id");
    const input_nombre = modal.querySelector("#view_nombre");
    const displayNombre = modal.querySelector("#display_view_nombre");
    const input_apellidos = modal.querySelector("#view_apellidos");
    const input_telefono = modal.querySelector("#view_telefono");
    const input_usuario = modal.querySelector("#view_username");
    const input_rol = modal.querySelector("#view_rol");
    const input_estado = modal.querySelector("#edit_estado_user");

    if (displayNombre) displayNombre.textContent = "Cargando...";

    fetch(`obtenerDataUsuario?idUser=${id_usuario}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 9) {
          if (input_id) input_id.value = partes[0].trim();
          if (input_nombre) input_nombre.value = partes[1].trim();
          if (displayNombre) displayNombre.textContent = partes[1].trim();
          if (input_apellidos) input_apellidos.value = partes[2].trim();
          if (input_telefono) input_telefono.value = partes[3].trim();
          if (input_usuario) input_usuario.value = partes[4].trim();
          if (input_rol) input_rol.value = partes[6].trim();
          if (input_estado) input_estado.value = partes[7].trim();

        }
      })
      .catch((err) => {
        console.error("Error al cargar la categoria:", err);
      });
  }
});


//Actualizacion para el modal de editar usuario
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarUsuario form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("actualizarEstadoUser", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarUsuario");
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
})

//Para el Modal de eliminar el usuario seleccionado
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal
  if (e.target && e.target.closest("#modalEliminarUsuario form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("deleteUsuario", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();

        // Cerramos el modal
        const modalEl = document.getElementById("modalEliminarUsuario");
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


//Para el Modal de restablecer el usuario seleccionado
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal
  if (e.target && e.target.closest("#modalRestablecerPass form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("restablecerPassUser", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();

        // Cerramos el modal
        const modalEl = document.getElementById("modalRestablecerPass");
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







///////////////////////////////////////////////////////////////////////////////////
//Obtener los datos de los roles
document.addEventListener("show.bs.modal", function (event) {
  const modal = event.target;

  if (modal && (modal.id === "modalEditarRol" || modal.id === "modalEliminarRol")) {
    const button = event.relatedTarget;
    const id_rol = button.getAttribute("data-rol"); 

    // Referencias corregidas
    const input_id = modal.querySelector("#input_rol_id");
    const input_nombre = modal.querySelector("#edit_nombre_rol");
    const displayNombre = modal.querySelector("#display_nombre_rol");

    if (displayNombre) displayNombre.textContent = "Cargando...";

    fetch(`obtenerDatosRoles?idRol=${id_rol}`)
      .then((response) => response.text())
      .then((data) => {
        const partes = data.trim().split("|");

        if (partes.length >= 2) {
          if (input_id) input_id.value = partes[0].trim();
          if (input_nombre) input_nombre.value = partes[1].trim();
          if (displayNombre) displayNombre.textContent = partes[1].trim();


        }
      })
      .catch((err) => {
        console.error("Error al cargar la categoria:", err);
      });
  }
});


//Actualizacion para el modal de editar usuario
document.addEventListener("submit", function (e) {
  if (e.target && e.target.closest("#modalEditarRol form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    fetch("actualizarRol", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          // 1. Cerrar el modal
          const modalEl = document.getElementById("modalEditarRol");
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
})

//Para el Modal de eliminar el usuario seleccionado
document.addEventListener("submit", function (e) {
  // Ajustado al ID de tu modal
  if (e.target && e.target.closest("#modalEliminarRol form")) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');

    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    fetch("eliminarRol", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        const res = data.trim();

        // Cerramos el modal
        const modalEl = document.getElementById("modalEliminarRol");
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