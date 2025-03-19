

const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const grado = document.getElementById('grado');
const seccion = document.getElementById('seccion');
const btnHorario = document.getElementById('btnbuscar');
const btnregister = document.getElementById('dropdown');


document.addEventListener('DOMContentLoaded', function () {
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    grado.addEventListener("change", handleGradoChange);
    seccion.addEventListener("change", function () { btnHorario.disabled = !seccion.value;
        btnregister.disabled = !seccion.value; }
    );
    
}
);

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


// function handleSeccionChange() {
//     const gradoId = grado.value;
//     const nivelId = nivel.value;

//     // if (gradoId) {
//     //     loadCursos(gradoId, nivelId);
//     // }
// }

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

