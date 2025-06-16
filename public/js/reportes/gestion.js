

const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const grado = document.getElementById('grado');
const seccion = document.getElementById('seccion');
const btnHorario = document.getElementById('btnbuscar');
const btnregister = document.getElementById('btnDatosprueba');


document.addEventListener('DOMContentLoaded',async  function () {
    activateButton();
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    grado.addEventListener("change", handleGradoChange);
    seccion.addEventListener("change", function () { btnHorario.disabled = !seccion.value;
        btnregister.disabled = seccion.value; }
    );
    await selectFromURL();
}
);

function activateButton(){
    let urlParams = new URLSearchParams(window.location.search);


    let seccion = urlParams.get('seccion');
    //Si no hay seccion seleccionada, deshabilitar el boton
    if (seccion || seccion === null || seccion === undefined || seccion === "" ) {
        btnHorario.disabled = false;
        btnregister.disabled = false;
    } else {
        btnHorario.disabled = true;
        btnregister.disabled = true;
    }
}

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


async function selectFromURL() {
    let urlParams = new URLSearchParams(window.location.search);

    const anioParam = urlParams.get('anio');
    const nivelParam = urlParams.get('nivel');
    const gradoParam = urlParams.get('grado');
    const seccionParam = urlParams.get('seccion');

    if (anioParam) {
        anio.value = anioParam;
        await loadNiveles(anioParam);
        if (nivelParam) {
            nivel.value = nivelParam;
            nivel.disabled = false;
            await loadGrados(nivelParam);
            if (gradoParam) {
                grado.value = gradoParam;
                grado.disabled = false;
                await loadSecciones(gradoParam);
                if (seccionParam) {
                    seccion.value = seccionParam;
                    seccion.disabled = false;
                    btnHorario.disabled = false;
                    btnregister.disabled = false;
                }
            }
        }
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

