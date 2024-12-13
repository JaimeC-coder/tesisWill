

moment.locale('es');
const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const docente = document.getElementById('docente');
const grado = document.getElementById('grado');
const seccion = document.getElementById('seccion');
const cursoId = document.getElementById('cursoId');

// const btnHorario = document.getElementById('btnHorario');
// const cur_id = document.getElementById('cur_id');
// const SelectDia = document.getElementById('SelectDia');
// const btnregister = document.getElementById('btnregister');
// const color = document.getElementById('color');
// const hora_inicio = document.getElementById('hora_inicio');
// const hora_fin = document.getElementById('hora_fin');
// const calendarEl = document.getElementById('calendar');
// const mostrar_info = document.getElementById('mostrar-info');


document.addEventListener('DOMContentLoaded', function () {
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    docente.addEventListener("change", handleDocenteChange);
    grado.addEventListener("change", handleGradoChange);
    seccion.addEventListener("change", handleSeccionChange);
   // cursoId.addEventListener("change", handleCursoChange);


});

function handleAnioChange() {
    const anioId = anio.value;
    if (anioId) {
        loadNiveles(anioId);
        // resetSelect(nivel);
    }
}

function handleNivelChange() {
    const nivelId = nivel.value;
    console.log(nivelId);
    if (nivelId) {
        loadDocente(nivelId);
        //resetSelect(grado);
    }
}

function handleDocenteChange() {
    const docenteId = docente.value;
    if (docenteId) {
        loadGrados(docenteId);
        //resetSelect(seccion);
    }
}

function handleGradoChange() {
    const gradoId = grado.value;
    if (gradoId) {
        loadSecciones(gradoId);
    }
}
function handleSeccionChange() {
    const gradoId = grado.value;
    const teacherId = docente.value;
    const nivelId = nivel.value;

    if (gradoId) {
        loadCursos(gradoId, teacherId, nivelId);
    }
}




function updateSelectOptions(selectElement, options, textKeyFunc, valueKey) {
    console.log(options);
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
    options.forEach(option => {
        let opc = document.createElement("option");
        opc.text = typeof textKeyFunc === "function" ? textKeyFunc(option) : option[textKeyFunc];
        opc.value = option[valueKey];
        selectElement.add(opc);
    });
    //selectElement.disabled = options.length === 0;
}



function resetSelect(selectElement) {
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
    //selectElement.disabled = true;
}



async function loadNiveles(anioId) {
    try {
        const niveles = await fetchData('/api/anio/nivel', { anio_id: anioId });
        updateSelectOptions(nivel, niveles, "niv_descripcion", "niv_id");
    } catch (error) {
        console.error("Error al cargar niveles:", error);
    }
}
async function loadDocente(nivel) {
    console.log(nivel);
    try {
        const docentes = await fetchData('/api/nota/docente', { nivel: nivel });
        console.log(docentes);
        updateSelectOptions(
            docente,
            docentes.docente,
            option => `${option.nombres} ${option.apellidos}`, // Concatenar nombres y apellidos
            "pa_id"
        );
    } catch (error) {
        console.error("Error al cargar niveles:", error);
    }
}

async function loadGrados(docente) {
    try {
        const grados = await fetchData('/api/nota/grado', { docente: docente });
        console.log(grados);
        updateSelectOptions(grado, grados.grados, "gra_descripcion", "gra_id");
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
async function loadCursos(gradoId, teacherId, nivelId) {
    try {
        const secciones = await fetchData('/api/nota/curso', { grado_id: gradoId , teacherId: teacherId, nivelId: nivelId });
        console.log(secciones);
        updateSelectOptions(cursoId, secciones.cursos, "cur_nombre", "cur_id");
    } catch (error) {
        console.error("Error al cargar secciones:", error);
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


