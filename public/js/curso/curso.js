document.addEventListener("DOMContentLoaded", function () {
    const inputCapacidad = document.getElementById("input-capacidad");
    const btnAgregarCapacidad = document.getElementById("btn-agregar-capacidad");
    const detalleCapacidades = document.getElementById("detalleCapacidades2");
    const tbodyCapacidades = detalleCapacidades.querySelector("tbody");
    const capacidadesInput = document.getElementById("capacidades");
    const formAllRequest = document.getElementById('form-all-request');
    // Inicializar el array de capacidades
    let capacidades = [];

    // Si hay capacidades existentes (en modo edición), cargarlas
    if (capacidadesInput.value) {
        try {
            const datosCapacidades = JSON.parse(capacidadesInput.value);

            // Procesamos los datos para extraer solo las descripciones
            if (Array.isArray(datosCapacidades)) {
                capacidades = datosCapacidades.map(cap => {
                    // Si la descripción contiene JSON string, lo parseamos
                    let descripcion = cap.cap_descripcion;
                    try {
                        // Removemos los caracteres extra si existen
                        descripcion = descripcion.replace(/^\["|"\]$/g, '');
                        // Decodificamos caracteres especiales
                        descripcion = descripcion.replace(/\\u([0-9a-fA-F]{4})/g, (match, chars) => {
                            return String.fromCharCode(parseInt(chars, 16));
                        });
                    } catch (e) {
                        console.error("Error al procesar descripción:", e);
                    }
                    return descripcion;
                });
            }

            if (capacidades.length > 0) {
                actualizarTabla();
            }
        } catch (e) {
            console.error("Error al parsear capacidades:", e);
        }
    }

    formAllRequest.addEventListener('submit', function (event) {
        // Validar si hay al menos una capacidad
        if (capacidades.length === 0) {
            event.preventDefault();
            alert("Por favor, agrega al menos una capacidad antes de enviar el formulario.");
        }
    }
    );


    btnAgregarCapacidad.addEventListener("click", function () {
        const capacidadTexto = inputCapacidad.value.trim();
        if (capacidadTexto === "") return;

        capacidades.push(capacidadTexto);
        actualizarTabla();
        actualizarInputHidden();
        inputCapacidad.value = "";
    });

    function actualizarTabla() {
        tbodyCapacidades.innerHTML = "";

        if (capacidades.length > 0) {
            detalleCapacidades.classList.remove("d-none");
        }

        capacidades.forEach((capacidad, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <th scope="row">${index + 1}</th>
                <td>${capacidad}</td>
                <td>
                    <button type="button" class="btn btn-icon btn-sm btn-delete" data-index="${index}">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </button>
                </td>
            `;
            tbodyCapacidades.appendChild(row);
        });

        // Agregar event listeners a los botones de eliminar
        document.querySelectorAll(".btn-delete").forEach(btn => {
            btn.addEventListener("click", function () {
                const index = parseInt(this.getAttribute("data-index"));
                capacidades.splice(index, 1);
                actualizarTabla();
                actualizarInputHidden();

                if (capacidades.length === 0) {
                    detalleCapacidades.classList.add("d-none");
                }
            });
        });
    }

    function actualizarInputHidden() {
        // Guardamos solo el array de strings
        capacidadesInput.value = JSON.stringify(capacidades);
    }
});

