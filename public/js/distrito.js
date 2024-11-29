// Elementos del DOM
let departamento = document.getElementById("departamento");
let provincia = document.getElementById("provincia");
let distrito = document.getElementById("distrito");

// Función para inicializar los eventos
document.addEventListener('DOMContentLoaded', function () {
    initializeEvents();
});

// Función para inicializar los eventos de los select
function initializeEvents() {
    departamento.addEventListener("change", handleDepartamentoChange);
    provincia.addEventListener("change", handleProvinciaChange);
}

// Manejar cambio en el select de departamento
function handleDepartamentoChange() {
    const depId = departamento.value;
    if (depId) {
        loadProvincias(depId);
        resetSelect(distrito); // Limpia los distritos porque dependen de la provincia
    }
}

// Manejar cambio en el select de provincia
function handleProvinciaChange() {
    const provId = provincia.value;
    if (provId) {
        loadDistritos(provId);
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
async function loadProvincias(depId) {
    try {
        const provincias = await fetchData('/api/provincia', { dep_id: depId });
        updateSelectOptions(provincia, provincias, "provincia", "idProv");
    } catch (error) {
        console.error("Error al cargar provincias:", error);
    }
}

// Cargar distritos desde el servidor
async function loadDistritos(provId) {
    try {
        const distritos = await fetchData('/api/distrito', { prov_id: provId });
        updateSelectOptions(distrito, distritos, "distrito", "idDist");
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
