document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los inputs, selects y campos date que tienen atributos data-type o data-required
    const formElements = document.querySelectorAll('input[data-type], input[data-required], select[data-required], input[type="date"][data-required], input[type="email"]');

    // Crear el contenedor de errores una sola vez
    const errorContainer = document.getElementById('error-messages') || createErrorContainer();

    formElements.forEach(element => {
        const maxLength = parseInt(element.dataset.length) || null;
        const elementType = element.tagName.toLowerCase();

        // Crear un contenedor específico para los errores de cada campo
        let fieldErrorContainer = document.createElement('div');
        fieldErrorContainer.classList.add('field-error-container');
        fieldErrorContainer.id = `error-${element.id}`;
        fieldErrorContainer.style.display = 'none';

        // Colocar el contenedor de error después del elemento o su grupo padre
        const elementGroup = element.closest('.input-group') || element.parentElement;
        elementGroup.insertAdjacentElement('afterend', fieldErrorContainer);

        // Validar dependiendo del tipo de elemento
        if (elementType === 'select') {
            // Para elementos select
            element.addEventListener('change', function() {
                validarElemento(element);
                if (element.dataset.unic === 'true') {
                    verificarUnicidadContraTodos(element);
                }
            });

            element.addEventListener('blur', function() {
                validarElemento(element);
                if (element.dataset.unic === 'true') {
                    verificarUnicidadContraTodos(element);
                }
            });
        } else {
            // Para elementos input (text, number, date, email, etc.)
            element.addEventListener('input', function() {
                validarElemento(element);
                if (element.dataset.unic === 'true') {
                    verificarUnicidadContraTodos(element);
                }
                // Para emails, validamos también cuando cambia el valor
                if (element.type === 'email') {
                    validarElemento(element);
                } else {
                    validarElemento(element);
                }
            });

            element.addEventListener('blur', function() {
                validarElemento(element);
                if (element.dataset.unic === 'true') {
                    verificarUnicidadContraTodos(element);
                }

                // Si es un campo email con validación de correo existente
                if (element.type === 'email' && element.dataset.validateCorreo === 'true' &&
                    element.value.trim() !== '' && validarFormatoEmail(element.value)) {
                    verificarEmailExistente(element);
                }
            });

            // Si es un input de texto o número, aplicamos validaciones adicionales
            if (element.type !== 'date' && element.type !== 'email') {
                // Bloquear escritura en tiempo real para inputs de texto/número
                element.addEventListener('keypress', function(e) {
                    const char = String.fromCharCode(e.which);
                    if (element.dataset.type === 'numbers' && !/^\d$/.test(char)) e.preventDefault();
                    if (element.dataset.type === 'letters' && !/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]$/.test(char)) e.preventDefault();
                    if (maxLength && element.value.length >= maxLength) e.preventDefault();
                });

                // Prevenir errores al pegar
                element.addEventListener('paste', function(e) {
                    const pasted = (e.clipboardData || window.clipboardData).getData('text');
                    if ((element.dataset.type === 'numbers' && /\D/.test(pasted)) ||
                        (element.dataset.type === 'letters' && /[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/.test(pasted)) ||
                        (maxLength && pasted.length > maxLength)) {
                        e.preventDefault();
                    }
                });
            }
        }
    });

    function validarElemento(element) {
        const maxLength = parseInt(element.dataset.length) || null;
        const fieldErrorContainer = document.getElementById(`error-${element.id}`);
        const elementType = element.tagName.toLowerCase();

        // Limpiar errores anteriores
        limpiarErrores(element);

        // Validación común para todos los elementos: campo requerido
        if (element.dataset.required === 'true') {
            if (elementType === 'select' && (element.value === '' || element.value === '0')) {
                mostrarError(element, fieldErrorContainer, 'Debe seleccionar una opción');
                return false;
            } else if (elementType === 'input' && element.value.trim() === '') {
                mostrarError(element, fieldErrorContainer, 'Este campo es obligatorio');
                return false;
            }
        }

        // Validaciones específicas para inputs
        if (elementType === 'input') {
            // Validación para campos de fecha
            if (element.type === 'date' && element.dataset.required === 'true' && element.value === '') {
                mostrarError(element, fieldErrorContainer, 'Debe seleccionar una fecha');
                return false;
            }

            // Validación para campos de fecha min y max
            if (element.type === 'date' && element.value !== '') {
                const selectedDate = new Date(element.value);

                if (element.min && new Date(element.min) > selectedDate) {
                    mostrarError(element, fieldErrorContainer, `La fecha debe ser posterior a ${formatDate(new Date(element.min))}`);
                    return false;
                }

                if (element.max && new Date(element.max) < selectedDate) {
                    mostrarError(element, fieldErrorContainer, `La fecha debe ser anterior a ${formatDate(new Date(element.max))}`);
                    return false;
                }
            }

            // Validación para email
            if (element.type === 'email' && element.value.trim() !== '') {
                // Validar formato de email
                if (!validarFormatoEmail(element.value)) {
                    mostrarError(element, fieldErrorContainer, 'Ingrese un correo electrónico válido');
                    return false;
                }
            }

            // Validaciones para tipos específicos de inputs
            if (element.dataset.type === 'numbers' && /\D/.test(element.value)) {
                mostrarError(element, fieldErrorContainer, 'Solo se permiten números');
                return false;
            }

            if (element.dataset.type === 'letters' && /[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/.test(element.value)) {
                mostrarError(element, fieldErrorContainer, 'Solo se permiten letras');
                return false;
            }

            if (maxLength && element.value.length !== maxLength && element.value.trim() !== '') {
                mostrarError(element, fieldErrorContainer, `Debe tener exactamente ${maxLength} caracteres`);
                return false;
            }
        }

        return true;
    }

    function verificarUnicidadContraTodos(element) {
        const form = element.closest('form');
        if (form) {
            const allFormElements = form.querySelectorAll('input, select, textarea'); // Selecciona todos los elementos de formulario
            const fieldErrorContainer = document.getElementById(`error-${element.id}`);
            let duplicateFound = false;

            allFormElements.forEach(otherElement => {
                if (otherElement !== element &&
                    otherElement.value !== undefined &&
                    otherElement.value.trim() !== '' &&
                    element.value.trim().toLowerCase() === otherElement.value.trim().toLowerCase() &&
                    element.dataset.omit !== 'true' && otherElement.dataset.omit !== 'true') {
                    duplicateFound = true;
                }
            });

            if (duplicateFound) {
                mostrarError(element, fieldErrorContainer, 'Esta información ya está siendo usada');
                return false;
            } else {
                // Si no hay duplicados, limpiamos cualquier error de unicidad anterior
                const errorElement = fieldErrorContainer.querySelector('.js-error');
                if (errorElement && errorElement.textContent === 'Esta información ya está siendo usada') {
                    limpiarErrores(element);
                }
            }
        }
        return true;
    }

    // Función auxiliar para formatear fechas
    function formatDate(date) {
        return date.toLocaleDateString();
    }

    // Función para validar formato de email
    function validarFormatoEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Función para verificar si un email ya existe en el sistema
    function verificarEmailExistente(element) {
        const email = element.value;
        const fieldErrorContainer = document.getElementById(`error-${element.id}`);

        // Mostrar indicador de carga
        element.classList.add('validating');
        const loadingSpan = document.createElement('span');
        loadingSpan.className = 'validating-email';
        loadingSpan.textContent = ' Verificando correo...';
        loadingSpan.style.fontSize = '12px';
        loadingSpan.style.color = '#666';

        // Limpiar errores previos
        limpiarErrores(element);
        fieldErrorContainer.style.display = 'block';
        fieldErrorContainer.innerHTML = '';
        fieldErrorContainer.appendChild(loadingSpan);

        fetch('/api/verificar-correo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            element.classList.remove('validating');
            fieldErrorContainer.innerHTML = '';

            if (data.existe) {
                mostrarError(element, fieldErrorContainer, 'El correo ingresado ya está registrado en el sistema');
                return false;
            } else {
                // Si el correo no existe, limpiamos cualquier error
                limpiarErrores(element);

                // Opcionalmente mostrar mensaje de éxito
                const successSpan = document.createElement('span');
                successSpan.className = 'email-valid';
                successSpan.textContent = '✓ Correo disponible';
                successSpan.style.fontSize = '12px';
                successSpan.style.color = 'green';
                fieldErrorContainer.style.display = 'block';
                fieldErrorContainer.appendChild(successSpan);

                // Ocultar el mensaje de éxito después de 3 segundos
                setTimeout(() => {
                    if (fieldErrorContainer.contains(successSpan)) {
                        fieldErrorContainer.style.display = 'none';
                        fieldErrorContainer.innerHTML = '';
                    }
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error al verificar el correo:', error);
            element.classList.remove('validating');
            fieldErrorContainer.innerHTML = '';

            // Mostrar mensaje de error de conexión
            const errorSpan = document.createElement('span');
            errorSpan.className = 'email-error';
            errorSpan.textContent = 'Error al verificar el correo. Intente nuevamente.';
            errorSpan.style.fontSize = '12px';
            errorSpan.style.color = 'orange';
            fieldErrorContainer.style.display = 'block';
            fieldErrorContainer.appendChild(errorSpan);
        });
    }

    function mostrarError(element, container, message) {
        // Limpiar cualquier error anterior
        container.innerHTML = '';

        // Crear el elemento del error
        let errorEl = document.createElement("span");
        errorEl.textContent = message;
        errorEl.className = "js-error";
        errorEl.style.color = "red";
        errorEl.style.fontSize = "12px";
        errorEl.style.display = "block";
        errorEl.style.marginTop = "4px";

        // Mostrar el contenedor de error
        container.style.display = 'block';

        // Añadir el mensaje de error al contenedor
        container.appendChild(errorEl);

        // Estilo del elemento con error
        element.style.border = "1px solid red";
        element.style.backgroundColor = "#ffe6e6";
    }

    function limpiarErrores(element) {
        // Restaurar estilos del elemento
        element.style.border = "";
        element.style.backgroundColor = "";

        // Ocultar y limpiar el contenedor de errores específico de este campo
        const fieldErrorContainer = document.getElementById(`error-${element.id}`);
        if (fieldErrorContainer) {
            fieldErrorContainer.style.display = 'none';
            fieldErrorContainer.innerHTML = '';
        }
    }

    function createErrorContainer() {
        const container = document.createElement('div');
        container.id = 'error-messages';
        container.style.marginTop = '10px';
        document.body.appendChild(container);
        return container;
    }

    // Validar específicamente el formulario con ID form-all-request
    const formAllRequest = document.getElementById('form-all-request');

    if (formAllRequest) {
        formAllRequest.addEventListener('submit', function(e) {
            let formValid = true;
            const requiredElements = formAllRequest.querySelectorAll('input[data-required="true"], select[data-required="true"], input[type="date"][data-required="true"], input[type="email"][data-required="true"]');
            const unicElements = formAllRequest.querySelectorAll('[data-unic="true"]');

            requiredElements.forEach(element => {
                if (!validarElemento(element)) {
                    formValid = false;
                }
            });

            unicElements.forEach(element => {
                if (element.dataset.unic === 'true' && !verificarUnicidadContraTodos(element)) {
                    formValid = false;
                }
            });

            // Verificar si hay algún error visible en el formulario
            const errorsVisible = formAllRequest.querySelectorAll('.field-error-container[style*="display: block"]');
            if (errorsVisible.length > 0) {
                formValid = false;
            }

            if (!formValid) {
                e.preventDefault();
            }
        });
    } else {
        console.warn('El formulario con ID "form-all-request" no fue encontrado en el documento.');
    }
});
