document.addEventListener('DOMContentLoaded', () => {
    const allCatButtons = document.querySelectorAll('.cat-btn');
    const productos = document.querySelectorAll('.producto-item');
    const btnAgregars = document.querySelectorAll('.btn-agregar');
    
    let items = 0;
    let total = 0;

    // Filtrado de Categorías
    allCatButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            allCatButtons.forEach(b => b.classList.remove('active'));
            const cat = btn.getAttribute('data-category');
            
            // Activar todos los botones con esa categoría (PC y Móvil)
            document.querySelectorAll(`[data-category="${cat}"]`).forEach(el => el.classList.add('active'));

            productos.forEach(prod => {
                if (cat === 'todos' || prod.getAttribute('data-cat') === cat) {
                    prod.classList.remove('d-none');
                } else {
                    prod.classList.add('d-none');
                }
            });
        });
    });

    // Carrito
    btnAgregars.forEach(btn => {
        btn.addEventListener('click', () => {
            const precio = parseFloat(btn.getAttribute('data-precio'));
            items++;
            total += precio;

            document.getElementById('cart-count').innerText = `${items} items`;
            document.getElementById('cart-total-amount').innerText = `$${total.toFixed(2)}`;
            document.getElementById('cart-floating').classList.remove('d-none');
            document.getElementById('cart-floating').classList.add('d-flex');
        });
    });
});



