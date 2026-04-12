// Función para abrir el modal y cargar los datos del producto
function prepararEdicion(id, valor, estatus) {
    // Llenamos los campos del modal con los datos de la fila
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_id_label').innerText = id;
    document.getElementById('edit_valor').value = valor;
    document.getElementById('edit_estatus').value = estatus;
    
    // Mostramos el modal usando la API de Bootstrap 5
    var modalElement = document.getElementById('modalEditar');
    var modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
    modalInstance.show();
}

// Función para eliminar vía AJAX
function eliminarProducto(id, boton) {
    if (confirm("¿Seguro que deseas eliminar el producto #" + id + "?")) {
        // Usamos FormData para enviar el ID a PHP
        const datos = new URLSearchParams();
        datos.append('id', id);

        fetch('eliminar_producto.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "ok") {
                // Si el PHP responde "ok", quitamos la fila de la tabla con efecto visual
                const fila = boton.closest('tr');
                fila.style.opacity = '0';
                setTimeout(() => fila.remove(), 300);
            } else {
                alert("Error al eliminar: " + data);
            }
        })
        .catch(err => console.error("Error:", err));
    }
}