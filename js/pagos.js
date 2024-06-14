document.addEventListener('DOMContentLoaded', function() {
    // Obtener el elemento select de productos
    const selectProducto = document.getElementById('producto');

    // Realizar una solicitud AJAX para obtener los productos desde la base de datos
    fetch('php/obtener_productos.php')
        .then(response => response.json())
        .then(productos => {
            // Generar las opciones del select con los productos obtenidos
            productos.forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = `${producto.nombre} - $${producto.precio}`;
                selectProducto.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al obtener los productos:', error);
        });

    // Array para almacenar los productos seleccionados
    const productosSeleccionados = [];

    // Evento click del botón "Agregar Producto"
    document.getElementById('agregar-producto').addEventListener('click', function() {
        const productoId = selectProducto.value;
        const cantidad = document.getElementById('cantidad').value;

        // Obtener el producto seleccionado
        const productoSeleccionado = Array.from(selectProducto.options).find(option => option.value === productoId);

        if (productoSeleccionado && cantidad > 0) {
            // Crear un objeto con los detalles del producto seleccionado
            const producto = {
                id: productoId,
                nombre: productoSeleccionado.textContent.split(' - ')[0],
                precio: parseFloat(productoSeleccionado.textContent.split(' - ')[1].replace('$', '')),
                cantidad: parseInt(cantidad)
            };

            // Agregar el producto al array de productos seleccionados
            productosSeleccionados.push(producto);

            // Actualizar la tabla de productos seleccionados
            actualizarTablaProductos();

            // Limpiar los campos de selección
            selectProducto.value = '';
            document.getElementById('cantidad').value = '';
        }
    });

    // Función para actualizar la tabla de productos seleccionados
function actualizarTablaProductos() {
    const tableBody = document.getElementById('productos-seleccionados');
    tableBody.innerHTML = '';

    productosSeleccionados.forEach(producto => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${producto.nombre}</td>
            <td>$${producto.precio.toFixed(2)}</td>
            <td>${producto.cantidad}</td>
            <td>$${(producto.precio * producto.cantidad).toFixed(2)}</td>
        `;
        tableBody.appendChild(row);
    });

    // Calcular el total a pagar
    const total = productosSeleccionados.reduce((sum, producto) => sum + producto.precio * producto.cantidad, 0);
    const totalRow = document.createElement('tr');
    totalRow.innerHTML = `
        <td colspan="3" class="text-end"><strong>Total:</strong></td>
        <td>$${total.toFixed(2)}</td>
    `;
    tableBody.appendChild(totalRow);
}

    // Evento submit del formulario de pago
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const email = document.getElementById('email').value;

        // Crear un objeto con los datos del pago
        const pago = {
            nombre: nombre,
            email: email,
            productos: productosSeleccionados
        };

        // Enviar los datos del pago al servidor mediante una solicitud AJAX
        fetch('php/pagos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pago)
        })
        .then(response => {
            if (response.ok) {
                // Redirigir a la página de confirmación de pago
                window.location.href = '/proyecto/confirmacion_pago.html';
            } else {
                console.error('Error al procesar el pago');
            }
        })
        .catch(error => {
            console.error('Error al enviar los datos del pago:', error);
        });
    });
});