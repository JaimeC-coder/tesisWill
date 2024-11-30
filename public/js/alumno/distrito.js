document.addEventListener('DOMContentLoaded', function () {
    // Inicializa eventos para ambos grupos
    initializeEvents('Alumno');
    initializeEvents('Apoderado');
});

// Inicializa eventos dinámicamente para un grupo
function initializeEvents(group) {
    const departamento = document.getElementById(`per_departamento_${group}`);
    const provincia = document.getElementById(`per_provincia_${group}`);
    const distrito = document.getElementById(`per_distrito_${group}`);

    departamento.addEventListener("change", () => handleDepartamentoChange(group));
    provincia.addEventListener("change", () => handleProvinciaChange(group));
}


function handleDepartamentoChange(group) {
    const departamento = document.getElementById(`per_departamento_${group}`);
    const provincia = document.getElementById(`per_provincia_${group}`);
    const distrito = document.getElementById(`per_distrito_${group}`);

    const depId = departamento.value;
    if (depId) {
        loadProvincias(depId, provincia);
        resetSelect(distrito); // Limpia los distritos porque dependen de la provincia
    }
}


function handleProvinciaChange(group) {
    const provincia = document.getElementById(`per_provincia_${group}`);
    const distrito = document.getElementById(`per_distrito_${group}`);

    const provId = provincia.value;
    if (provId) {
        loadDistritos(provId, distrito);
    }
}

// Actualizar opciones de un select
function updateSelectOptions(selectElement, options, textKey, valueKey) {
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>'; // Opción por defecto
    options.forEach(option => {
        let opc = document.createElement("option");
        opc.text = option[textKey];
        opc.value = option[valueKey];
        selectElement.add(opc);
    });
    selectElement.disabled = options.length === 0; // Deshabilitar si no hay opciones
}

// Limpiar un select
function resetSelect(selectElement) {
    selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
    selectElement.disabled = true;
}

// Cargar provincias desde el servidor
async function loadProvincias(depId, provinciaElement) {
    try {
        const provincias = await fetchData('/api/provincia', { dep_id: depId });
        updateSelectOptions(provinciaElement, provincias, "provincia", "idProv");
    } catch (error) {
        console.error("Error al cargar provincias:", error);
    }
}

// Cargar distritos desde el servidor
async function loadDistritos(provId, distritoElement) {
    try {
        const distritos = await fetchData('/api/distrito', { prov_id: provId });
        updateSelectOptions(distritoElement, distritos, "distrito", "idDist");
    } catch (error) {
        console.error("Error al cargar distritos:", error);
    }
}

// Función genérica para realizar solicitudes fetch
async function fetchData(url, body) {
    const response = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
    });
    if (!response.ok) {
        throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    return await response.json();
}
