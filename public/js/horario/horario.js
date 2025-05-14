moment.locale('es');
const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const grado = document.getElementById('grado');
const seccionss = document.getElementById('seccion');
const btnHorario = document.getElementById('btnHorario');
const cur_id = document.getElementById('cur_id');
const SelectDia = document.getElementById('SelectDia');
const btnregister = document.getElementById('btnregister');
const color = document.getElementById('color');
const hora_inicio = document.getElementById('hora_inicio');
const hora_fin = document.getElementById('hora_fin');
const calendarEl = document.getElementById('calendar');
const mostrar_info = document.getElementById('mostrar-info');
const user = document.getElementById('xd');
let calendar; // Variable global para el calendario

document.addEventListener('DOMContentLoaded', function () {
    verififyHorario();
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    grado.addEventListener("change", handleGradoChange);
    seccionss.addEventListener("change", function () { btnHorario.disabled = !seccion.value; });
    btnHorario.addEventListener('click', (e) => {
        e.preventDefault(); // Evitar comportamiento por defecto
        loadHorarios();
    });

    if (btnregister) {
        btnregister.addEventListener('click', (e) => {
            e.preventDefault(); // Evitar comportamiento por defecto
            registerHorario();
        });
    }

    // Si existe un formulario, prevenir su envío por defecto
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault(); // Evitar envío del formulario
        });
    }
});

function handleAnioChange() {
    const anioId = anio.value;
    if (anioId) {
        loadNiveles(anioId);
        resetSelect(nivel);
        nivel.disabled = false;
    }
}

function handleNivelChange() {
    const nivelId = nivel.value;
    console.log(nivelId);
    if (nivelId) {
        loadGrados(nivelId);
        resetSelect(grado);
    }
}

function handleGradoChange() {
    const gradoId = grado.value;
    if (gradoId) {
        loadSecciones(gradoId);
    }
}

function updateSelectOptions(selectElement, options, textKey, valueKey) {
    console.log(options);
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
    options.forEach(option => {
        let opc = document.createElement("option");
        opc.text = option[textKey];
        opc.value = option[valueKey];
        selectElement.add(opc);
    });
    selectElement.disabled = options.length === 0;
}

function resetSelect(selectElement) {
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
    selectElement.disabled = true;
}

function initCalendar() {
    // Inicializamos el calendario solo una vez
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
        },
        hiddenDays: [0, 6], // Oculta domingo y sábado
        allDaySlot: false,
        slotMinTime: "07:00:00",
        slotMaxTime: "13:00:00",
        slotDuration: "00:15",
        events: [] // Inicializamos sin eventos
    });
    calendar.render();
    return calendar;
}

function updateCalendarEvents(events) {
    // Si el calendario no está inicializado, lo inicializamos
    if (!calendar) {
        calendar = initCalendar();
    }

    // Eliminamos todos los eventos actuales
    calendar.removeAllEvents();

    // Añadimos los nuevos eventos
    calendar.addEventSource(events);

    // Refrescamos la vista
    calendar.refetchEvents();
}

async function loadNiveles(anioId) {
    try {
        const niveles = await fetchData('/api/anio/nivel', { anio_id: anioId });
        console.log(niveles);
        updateSelectOptions(nivel, niveles, "niv_descripcion", "niv_id");
    } catch (error) {
        console.error("Error al cargar niveles:", error);
    }
}

async function loadGrados(nivelId, userId = user.value) {
    try {
        const grados = await fetchData('/api/anio/grado', { nivel_id: nivelId, user_id: userId });
        console.log(grados);
        updateSelectOptions(grado, grados, "gra_descripcion", "gra_id");
    } catch (error) {
        console.error("Error al cargar grados:", error);
    }
}

async function loadSecciones(gradoId, userId = user.value) {
    try {
        const secciones = await fetchData('/api/anio/seccion', { grado_id: gradoId, user_id: userId });
        console.log(secciones);
        updateSelectOptions(seccion, secciones, "sec_descripcion", "sec_id");
    } catch (error) {
        console.error("Error al cargar secciones:", error);
    }
}

async function loadHorarios(seccionId = seccionss.value, anioId = anio.value, nivelId = nivel.value, gradoId = grado.value, userId = user.value) {
    try {
        const horarios = await fetchData('/api/horario/search', {
            seccion_id: seccionId,
            anio_id: anioId,
            nivel_id: nivelId,
            grado_id: gradoId,
            user_id: userId
        });

        if (horarios.status === 400) {
            showNotification(horarios.mensaje, 'info');
            return;
        }

        mostrar_info.hidden = false;

        if (btnregister) {
            updateSelectOptions(cur_id, horarios.cursos, "cur_abreviatura", "cur_id");
            updateSelectOptions(SelectDia, horarios.dias, "name", "id");
        }

        // Actualizamos solo los eventos del calendario
        updateCalendarEvents(horarios.horarios);
    } catch (error) {
        console.error("Error al cargar horarios:", error);
        showNotification('Error al cargar horarios', 'error');
    }
}

async function registerHorario() {
    // Validar campos del formulario antes de enviar
    if (!validateHorarioForm()) {
        return;
    }

    try {
        const data = {
            cur_id: cur_id.value,
            anio_id: anio.value,
            dia_id: SelectDia.value,
            color: color.value,
            hora_inicio: moment(hora_inicio.value, ["h:mm A"]).format("HH:mm:ss"),
            hora_fin: moment(hora_fin.value, ["h:mm A"]).format("HH:mm:ss"),
            seccion_id: seccion.value,
            nivel_id: nivel.value,
            grado_id: grado.value,
        };

        console.log(data);

        // Mostrar indicador de carga
        btnregister.disabled = true;
        btnregister.textContent = 'Registrando...';

        const response = await fetchData('/api/horario/register', data);

        console.log(response);

        // Restaurar el botón
        btnregister.disabled = false;
        btnregister.textContent = 'Registrar';

        if (response.status === 1) {
            showNotification('Horario registrado correctamente', 'success');

            // Limpiar campos del formulario excepto los de selección de curso
            resetHorarioForm();

            // Recargar solo el calendario
            loadHorarios();
        } else {
            showNotification('Error al registrar horario: ' + (response.message || 'Verifique los campos'), 'error');
        }
    } catch (error) {
        console.error("Error al registrar horario:", error);
        btnregister.disabled = false;
        btnregister.textContent = 'Registrar';
        showNotification('Error al procesar la solicitud', 'error');
    }
}

function validateHorarioForm() {
    let isValid = true;

    // Validación básica de campos requeridos
    if (!cur_id.value) {
        highlightField(cur_id, 'Seleccione un curso');
        isValid = false;
    }

    if (!SelectDia.value) {
        highlightField(SelectDia, 'Seleccione un día');
        isValid = false;
    }

    if (!hora_inicio.value) {
        highlightField(hora_inicio, 'Seleccione hora de inicio');
        isValid = false;
    }

    if (!hora_fin.value) {
        highlightField(hora_fin, 'Seleccione hora de fin');
        isValid = false;
    }

    // Validar que hora fin sea mayor que hora inicio
    if (hora_inicio.value && hora_fin.value) {
        const inicio = moment(hora_inicio.value, ["h:mm A"]);
        const fin = moment(hora_fin.value, ["h:mm A"]);

        if (!fin.isAfter(inicio)) {
            highlightField(hora_fin, 'La hora de fin debe ser posterior a la hora de inicio');
            isValid = false;
        }
    }

    return isValid;
}

function highlightField(element, message) {
    // Crear o encontrar el contenedor de error
    let errorDiv = element.parentElement.querySelector('.validation-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'validation-error';
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '4px';
        element.parentElement.appendChild(errorDiv);
    }

    // Mostrar mensaje de error
    errorDiv.textContent = message;

    // Resaltar el campo
    element.style.border = '1px solid red';

    // Eliminar el error cuando el usuario cambie el valor
    element.addEventListener('change', function onChangeHandler() {
        element.style.border = '';
        if (errorDiv) {
            errorDiv.textContent = '';
        }
        element.removeEventListener('change', onChangeHandler);
    }, { once: true });
}

function resetHorarioForm() {
    // Mantener los campos de selección de curso y sólo limpiar campos de horario
    hora_inicio.value = '';
    hora_fin.value = '';

    // Eliminar mensajes de error
    document.querySelectorAll('.validation-error').forEach(el => {
        el.textContent = '';
    });

    // Eliminar estilos de error
    document.querySelectorAll('select, input').forEach(el => {
        el.style.border = '';
    });
}

function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    // Estilos
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.padding = '15px 20px';
    notification.style.borderRadius = '5px';
    notification.style.zIndex = '9999';
    notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
    notification.style.opacity = '0';
    notification.style.transition = 'opacity 0.3s ease-in-out';

    // Establecer color según el tipo
    if (type === 'success') {
        notification.style.backgroundColor = '#4CAF50';
        notification.style.color = 'white';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#F44336';
        notification.style.color = 'white';
    } else {
        notification.style.backgroundColor = '#2196F3';
        notification.style.color = 'white';
    }

    // Añadir al DOM
    document.body.appendChild(notification);

    // Mostrar animación
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 10);

    // Ocultar después de 4 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 4000);
}

async function verififyHorario() {
    try {
        const horarios = await fetchData('/api/horario/verifyAlumno', { user: user.value });
        console.log(horarios);

        if (horarios.alumno === 1) {
            const anioId = horarios.data.anio;
            const nivelId = horarios.data.nivel;
            const gradoId = horarios.data.grado;
            const seccionId = horarios.data.seccion;

            anio.value = anioId;
            await loadNiveles(anioId);
            nivel.value = nivelId;
            await loadGrados(nivelId);
            grado.value = gradoId;
            await loadSecciones(gradoId);
            seccionss.value = seccionId;

            anio.disabled = true;
            nivel.disabled = true;
            grado.disabled = true;
            seccionss.disabled = true;
            btnHorario.disabled = true;

            if (btnregister) {
                btnregister.disabled = true;
            }

            loadHorarios(seccionId, anioId, nivelId, gradoId, user.value);
            return;
        } else if (horarios.alumno === 2) {
            showNotification(horarios.data, 'info');
            // Redirigir a la página de home
            setTimeout(() => {
                window.location.href = '/home';
            }, 2000);
            return;
        }

        anio.disabled = false;
        console.log(horarios.data);
    } catch (error) {
        console.error("Error al verificar horarios:", error);
        showNotification('Error al verificar información del alumno', 'error');
    }
}

async function fetchData(url, data, method = 'POST') {
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        return response.json();
    } catch (error) {
        console.error(`Error en la petición a ${url}:`, error);
        throw error;
    }
}
