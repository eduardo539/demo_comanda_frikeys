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
    const contentArea = document.getElementById('content-area');
    
    fetch(`./views/modulos/${modulo}.php`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (response.status === 401) {
            window.location.href = RUTA_BASE; // Redirige la ventana completa
            return null;
        }
        return response.text();
    })
    .then(html => {
        if (html) {
            // Si por error el HTML trae la palabra "<!DOCTYPE html>", 
            // es que se está cargando la página completa y hay un error en el config.
            if (html.includes('<nav id="sidebar">')) {
                console.error("Error: Se intentó cargar el layout completo en un módulo.");
                return;
            }
            contentArea.innerHTML = html;
        }
    })
    .catch(err => console.error("Error crítico:", err));
}
});



const modalEditarMesa = document.getElementById('modalEditarMesa');

modalEditarMesa.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // Botón que activó el modal
    const uuid = button.getAttribute('data-uuid'); // Extraemos el UUID
    
    // Referencias a los elementos del modal
    const inputUuid = document.getElementById('input_uuid');
    const displayNombre = document.getElementById('display_nombre_mesa');
    const selectEstado = document.getElementById('select_estado');

    // 1. Limpieza inicial y asignación de ID
    inputUuid.value = uuid;
    displayNombre.innerHTML = '<span class="spinner-border spinner-border-sm text-primary"></span>';

    // 2. Petición al servidor
    fetch(`./views/mesas/get_mesa_info.php?uuid=${uuid}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // AQUÍ SE MUESTRA EL NOMBRE
                displayNombre.textContent = data.nombre_mesa; 
                // Seleccionamos el estado actual en el combo box
                selectEstado.value = data.estado;
            } else {
                displayNombre.textContent = "No encontrada";
            }
        })
        .catch(error => {
            displayNombre.textContent = "Error de red";
            console.error('Error:', error);
        });
});











