

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


document.addEventListener('DOMContentLoaded', function () {
    verififyHorario();
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    grado.addEventListener("change", handleGradoChange);
    seccionss.addEventListener("change", function () { btnHorario.disabled = !seccion.value; });
    btnHorario.addEventListener('click', () => loadHorarios());
    if (btnregister) {
        btnregister.addEventListener('click', registerHorario);
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

function loadCalendar(horario) {

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
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
        },//muestame de lunes a viernes nomas
        hiddenDays: [0, 6],
        initialView: 'timeGridWeek',
        allDaySlot: false,
        slotMinTime: "07:00:00",
        slotMaxTime: "13:00:00",
        slotDuration: "00:15",
        events: horario,
    });
    calendar.render();

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
        const grados = await fetchData('/api/anio/grado', { nivel_id: nivelId , user_id: userId });
        console.log(grados);
        updateSelectOptions(grado, grados, "gra_descripcion", "gra_id");
    } catch (error) {
        console.error("Error al cargar grados:", error);
    }
}

async function loadSecciones(gradoId, userId = user.value ) {
    try {
        const secciones = await fetchData('/api/anio/seccion', { grado_id: gradoId , user_id: userId });
        console.log(secciones);
        updateSelectOptions(seccion, secciones, "sec_descripcion", "sec_id");
    } catch (error) {
        console.error("Error al cargar secciones:", error);
    }
}
async function loadHorarios(seccionId =seccionss.value , anioId = anio.value, nivelId = nivel.value, gradoId = grado.value) {

    try {
        const horarios = await fetchData('/api/horario/search', { seccion_id: seccionId, anio_id: anioId, nivel_id: nivelId, grado_id: gradoId });

        mostrar_info.hidden = false;
        if (btnregister) {

            updateSelectOptions(cur_id, horarios.cursos, "cur_abreviatura", "cur_id");
            updateSelectOptions(SelectDia, horarios.dias, "name", "id");
        }
        loadCalendar(horarios.horarios);
    } catch (error) {
        console.error("Error al cargar horarios:", error);
    }

}

async function registerHorario() {
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
        const response = await fetchData('/api/horario/register', data);
        console.log(response);
        if (response.status === 1) {
            alert('Horario registrado correctamente');
        } else {
            alert('Error al registrar horario');
        }
    } catch (error) {
        console.error("Error al registrar horario:", error);
    }
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
            await loadNiveles(anioId);  // Cargar niveles basado en anio
            nivel.value = nivelId;
            await loadGrados(nivelId);  // Cargar grados basado en nivel
            grado.value = gradoId;
            await loadSecciones(gradoId);  // Cargar secciones basado en grado
            seccionss.value = seccionId;
            anio.disabled = true;
            nivel.disabled = true;
            grado.disabled = true;
            seccionss.disabled = true;
            btnHorario.disabled = true;

            if (btnregister) {
                btnregister.disabled = true;
            }
            loadHorarios(seccionId, anioId, nivelId, gradoId);
            return;
        }
        anio.disabled = false;
        console.log(horarios.data);
    } catch (error) {
        console.error(error);
        console.error("Error al verificar horarios:", error);
    }
}



async function fetchData(url, data, method = 'POST') {
    const response = await fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });
    return response.json();

}


