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
    const mensaje = nuevoEstado == "2" ? "¿Comenzar a preparar?" : "¿Marcar como terminado?";
    
    if (confirm(mensaje)) {
      // 3. Enviamos los datos por POST a tu RewriteRule
      const formData = new FormData();
      formData.append('folio', folio);
      formData.append('estado_id', nuevoEstado);

      fetch('updateEstadoPlatillo', { // Aquí usamos tu RewriteRule
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          // Cerramos modal y recargamos para ver los cambios en la tabla principal
          location.reload(); 
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch(err => console.error("Error en la petición:", err));
    }
  }
});