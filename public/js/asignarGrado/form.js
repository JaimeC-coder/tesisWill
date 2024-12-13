const initialCheckboxValues = {};


const urlParams = new URLSearchParams(window.location.search);
const niv_id = urlParams.get('niv_id');

const inputState = document.getElementById('inputState');
const cursoList = document.getElementById('curso');



document.addEventListener('DOMContentLoaded', function () {
    inputState.addEventListener("change", handleNivel);
});


function handleNivel() {
    const nivel = inputState.value;
    console.log(nivel);
    if (nivel !== "0") {
        searchCursos(nivel);
        return;
    }
    console.log(nivel);
}

async function searchCursos(nivelId) {
    try {
        const cursos = await fetchData('/api/asignacion/listCurso', { nivel_id: nivelId });
        updateSelectOptions(cursoList, cursos.cursos, "cur_nombre", "cur_nombre");
    } catch (error) {
        console.error("Error al cargar grados:", error);
    }
}

function resetSelect(selectElement) {
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
    selectElement.disabled = true;
}

function updateSelectOptions(selectElement, options, textKey, valueKey) {
    console.log(options);
    console.log(textKey);
    console.log(valueKey);
    console.log(selectElement);
    selectElement.innerHTML = '<option value="">Seleccione una opción</option> <option value="-1" selected>Todos </option>';
    options.forEach(option => {
        let opc = document.createElement("option");
        opc.text = option[textKey];
        opc.value = option[valueKey];
        selectElement.add(opc);
    });
    selectElement.disabled = options.length === 0;
}


async function asignacionMasiva(valor) {
    console.log("asignacionMasivaCurso");
    const td = valor.closest('tr');
    console.log(td);

    const paId = td.getAttribute('data-pa-id');
    const curso = td.getAttribute('data-curso');
    const asignaciones = td.getAttribute('data-asignaciones') ? JSON.parse(td.getAttribute('data-asignaciones')) : [];

    body = {
        nivel: niv_id,
        grado: asignaciones,
        curso: curso,
        persona_id: paId,
    };

    const response = await fetchData('/api/asignacion/masiva/grado', body);
    console.log(response);
    if (response.response === 1) {

        location.reload();
    }
}
async function eliminacionMasiva(valor) {

    const td = valor.closest('tr');
    const paId = td.getAttribute('data-pa-id');
    const curso = td.getAttribute('data-curso');

    body = {
        nivel: niv_id,
        curso: curso,
        persona_id: paId,
    };

    const response = await fetchData('/api/asignacion/eliminacion/masiva/grado', body);
    console.log(response);
    if (response.response === 1) {
        location.reload();
    }
}

function addCheckBoxAsignacion(valor) {
    const td = valor.closest('tr');
    let asignaciones = td.getAttribute('data-asignaciones') ? JSON.parse(td.getAttribute('data-asignaciones')) : [];

    if (valor.checked) {
        if (!asignaciones.includes(valor.value)) {
            asignaciones.push(valor.value);
        }
    } else {
        const index = asignaciones.indexOf(valor.value);
        if (index > -1) {
            asignaciones.splice(index, 1);
        }
    }

    td.setAttribute('data-asignaciones', JSON.stringify(asignaciones));
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
