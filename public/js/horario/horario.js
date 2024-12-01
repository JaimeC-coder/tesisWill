

moment.locale('es');
const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const grado = document.getElementById('grado');
const seccion = document.getElementById('seccion');
const btnHorario = document.getElementById('btnHorario');
const cur_id = document.getElementById('cur_id');
const SelectDia = document.getElementById('SelectDia');
const btnregister = document.getElementById('btnregister');
const color = document.getElementById('color');
const hora_inicio = document.getElementById('hora_inicio');
const hora_fin = document.getElementById('hora_fin');
const calendarEl = document.getElementById('calendar');
const mostrar_info = document.getElementById('mostrar-info');


document.addEventListener('DOMContentLoaded', function () {
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    grado.addEventListener("change", handleGradoChange);
    seccion.addEventListener("change", function () { btnHorario.disabled = !seccion.value; });
    btnHorario.addEventListener('click', loadHorarios);
    btnregister.addEventListener('click', registerHorario);

});

function handleAnioChange() {
    const anioId = anio.value;
    if (anioId) {
        loadNiveles(anioId);
        resetSelect(nivel);
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

        updateSelectOptions(nivel, niveles, "niv_descripcion", "niv_id");
    } catch (error) {
        console.error("Error al cargar niveles:", error);
    }
}

async function loadGrados(nivelId) {
    try {
        const grados = await fetchData('/api/anio/grado', { nivel_id: nivelId });
        console.log(grados);
        updateSelectOptions(grado, grados, "gra_descripcion", "gra_id");
    } catch (error) {
        console.error("Error al cargar grados:", error);
    }
}

async function loadSecciones(gradoId) {
    try {
        const secciones = await fetchData('/api/anio/seccion', { grado_id: gradoId });
        console.log(secciones);
        updateSelectOptions(seccion, secciones, "sec_descripcion", "sec_id");
    } catch (error) {
        console.error("Error al cargar secciones:", error);
    }
}
async function loadHorarios() {
    try {
        const seccionId = seccion.value;
        const horarios = await fetchData('/api/horario/search', { seccion_id: seccionId, anio_id: anio.value, nivel_id: nivel.value, grado_id: grado.value });
        console.log(horarios.cursos);
        console.log(horarios.dias);
        console.log(horarios.horarios);
        mostrar_info.hidden = false;
        updateSelectOptions(cur_id, horarios.cursos, "cur_abreviatura", "cur_id");
        updateSelectOptions(SelectDia, horarios.dias, "name", "id");
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


