const per_id_Alumno = document.getElementById('per_id_Alumno');
const per_id_Apoderado = document.getElementById('per_id_Apoderado');
// Información Básica - Alumno
const flexCheckDefaultAlumno = document.getElementById('flexCheckDefaultAlumno');
const per_dni_Alumno = document.getElementById('per_dni_Alumno');
const buscarDni = document.getElementById('buscarDni');
const per_nombres_Alumno = document.getElementById('per_nombres_Alumno');
const per_apellidos_Alumno = document.getElementById('per_apellidos_Alumno');
const per_sexo_Alumno = document.getElementById('per_sexo_Alumno');
const per_fecha_nacimiento_Alumno = document.getElementById('per_fecha_nacimiento_Alumno');
const per_estado_civil_Alumno = document.getElementById('per_estado_civil_Alumno');
const per_pais_Alumno = document.getElementById('per_pais_Alumno');
const per_departamento_Alumno = document.getElementById('per_departamento_Alumno');

const per_direccion_Alumno = document.getElementById('per_direccion_Alumno');
const per_celular_Alumno = document.getElementById('per_celular_Alumno');
const per_email_Alumno = document.getElementById('per_email_Alumno');

// Información Apoderado
const flexcheckApoderado = document.getElementById('flexcheckApoderado');
const per_dni_Apoderado = document.getElementById('per_dni_Apoderado');
const buscarDniApoderda = document.getElementById('buscarDniApoderda');
const per_nombres_Apoderado = document.getElementById('per_nombres_Apoderado');
const per_apellidos_Apoderado = document.getElementById('per_apellidos_Apoderado');
const per_sexo_Apoderado = document.getElementById('per_sexo_Apoderado');
const per_fecha_nacimiento_Apoderado = document.getElementById('per_fecha_nacimiento_Apoderado');
const per_estado_civil_Apoderado = document.getElementById('per_estado_civil_Apoderado');
const per_pais_Apoderado = document.getElementById('per_pais_Apoderado');
const per_departamento_Apoderado = document.getElementById('per_departamento_Apoderado');

const per_direccion_Apoderado = document.getElementById('per_direccion_Apoderado');
const per_celular_Apoderado = document.getElementById('per_celular_Apoderado');
const per_email_Apoderado = document.getElementById('per_email_Apoderado');
const per_parentesco_Apoderado = document.getElementById('per_parentesco_Apoderado');
const per_vive_con_estudiante_Apoderado = document.getElementById('per_vive_con_estudiante_Apoderado');


const arregloData = [per_id_Apoderado, per_id_Alumno,
    per_dni_Alumno, per_dni_Apoderado, per_direccion_Alumno, per_celular_Alumno, per_email_Alumno,
    per_direccion_Apoderado, per_celular_Apoderado, per_email_Apoderado, per_parentesco_Apoderado, per_vive_con_estudiante_Apoderado
];

function dniEvents() {

    per_dni_Alumno.addEventListener("input", function () {
        if (per_dni_Alumno.value.length >= 8) {
            buscarDni.disabled = false
        } else {
            buscarDni.disabled = true; // Deshabilitar el botón si la longitud es insuficiente
        }
    }
    );
    per_dni_Apoderado.addEventListener("input", function () {
        if (per_dni_Apoderado.value.length >= 8) {
            buscarDniApoderda.disabled = false
        } else {
            buscarDniApoderda.disabled = true; // Deshabilitar el botón si la longitud es insuficiente
        }
    }
    );
}

async function searchInformation(dniValue) {
    try {
        const response = await fetch('/api/personas', {
            method: "POST", // Método HTTP
            headers: {
                "Content-Type": "application/json", // Indica el tipo de contenido
            },
            body: JSON.stringify({ per_dni: dniValue }), // Datos en formato JSON
        });

        const data = await response.json();


        // Validación de la respuesta
        return data;
    } catch (error) {
        console.error(error);
        return null;
    }
}



document.addEventListener('DOMContentLoaded', function () {
    dniEvents();
    if (per_id_Apoderado.value && per_id_Alumno.value) {

        let departamento1 = document.getElementById(`per_departamento_Alumno`);
        let provincia1 = document.getElementById(`per_provincia_Alumno_hidden`);
        let distrito1 = document.getElementById(`per_distrito_Alumno_hidden`);

        let departamento2 = document.getElementById(`per_departamento_Apoderado`);
        let provincia2 = document.getElementById(`per_provincia_Apoderado_hidden`);
        let distrito2 = document.getElementById(`per_distrito_Apoderado_hidden`);

        aux(departamento1, provincia1, distrito1, "Alumno");
        aux(departamento2, provincia2, distrito2, "Apoderado");
    }

    buscarDniApoderda.addEventListener("click", function () {
        const dniValue = per_dni_Apoderado.value;
        searchInformation(dniValue)
            .then(data => {
                inputApoderado(data); // Pasa los datos resueltos a `inputApoderado`
            })
            .catch(error => {
                console.error("Error al buscar el DNI:", error);
            });
    });
    buscarDni.addEventListener("click", function () {
        const dniValue = per_dni_Alumno.value;
        searchInformation(dniValue)
            .then(data => {
                inputAlumno(data); // Pasa los datos resueltos a `inputApoderado`
            })
            .catch(error => {
                console.error("Error al buscar el DNI:", error);
            });
    });

    flexCheckDefaultAlumno.addEventListener("click", function () { checked(flexCheckDefaultAlumno, "Alumno"); });
    flexcheckApoderado.addEventListener("click", function () { checked(flexcheckApoderado, "Apoderado"); });
});


function aux(departamento1, provincia1, distrito1, group) {
    const provincia = document.getElementById(`per_provincia_${group}`);
    const distrito = document.getElementById(`per_distrito_${group}`);


    loadProvincias(departamento1.value, provincia).then(() => {
        provincia.value = provincia1.value;
        provincia.readonly = true;

        console.log(provincia);
        loadDistritos(provincia1.value, distrito).then(() => {
            console.log(distrito)
            distrito.value = distrito1.value;
            distrito.readonly = true;
        });
    });


}


function checked(flexCheckDefault, grupo) {
    let button = grupo === "Alumno" ? buscarDni : buscarDniApoderda;
    if (flexCheckDefault.checked) {
        console.log(flexCheckDefault);
        blockInput(false, grupo);
        clearInputs(grupo);
        button.hidden = true;
    } else {
        console.log("flexCheckDefault");
        console.log(flexCheckDefault);

        blockInput(true, grupo);
        button.hidden = false;
    }

}

function clearInputs(group) {
    const inputs = document.querySelectorAll(`[id^="per_"][id$="_${group}"]`);
    inputs.forEach(input => {
        if (input.tagName.toLowerCase() !== "select") {
            input.value = "";
        }

    });
}


function blockInput(status, group) {
    console.log(status);
    const inputs = document.querySelectorAll(`[id^="per_"][id$="_${group}"]`);
    inputs.forEach(input => {
        if (!arregloData.includes(input)) {
            //input.addClassName = "disabled";
            input.readOnly  = status;
        }

    });
    // Los elementos en arregloData se aseguran de no ser afectados
    arregloData.forEach(input => {
        input.readonly = false;
    });
    per_dni_Apoderado.readonly = false;
    per_dni_Alumno.readonly = false;

}

function inputAlumno(data) {

    if (data != null) {
        if (data.per_id != null) {
            per_id_Alumno.value = data.per_id || "";
            per_dni_Alumno.value = data.per_dni || "";
            per_nombres_Alumno.value = data.per_nombres || "";
            per_apellidos_Alumno.value = data.per_apellidos || "";
            per_sexo_Alumno.value = data.per_sexo || "";
            per_fecha_nacimiento_Alumno.value = data.per_fecha_nacimiento || "";
            per_estado_civil_Alumno.value = data.per_estado_civil || "";
            per_pais_Alumno.value = data.per_pais || "";
            per_direccion_Alumno.value = data.per_direccion || "";
            per_celular_Alumno.value = data.per_celular || "";
            per_email_Alumno.value = data.per_email || "";
            per_departamento_Alumno.value = data.per_departamento || "";
            preselectLocation(data, "Alumno");
        } else {
            let apellidos = data.apellidoPaterno + " " + data.apellidoMaterno;

            per_dni_Alumno.value = data.dni || "";
            per_nombres_Alumno.value = data.nombres || "";
            per_apellidos_Alumno.value = apellidos || "";
            per_pais_Alumno.value = "Perú";
            per_fecha_nacimiento_Alumno.readonly = false;
            return alert("El Alumno es nuevo por favor complete los datos");
        }
        return;
    }
    return alert("No se encontro el DNI");


}

function inputApoderado(data) {
    console.log(data);
    if (data != null) {
        if (data.per_id != null) {
            per_id_Apoderado.value = data.per_id || "";
            per_dni_Apoderado.value = data.per_dni || "";
            per_nombres_Apoderado.value = data.per_nombres || "";
            per_apellidos_Apoderado.value = data.per_apellidos || "";
            per_sexo_Apoderado.value = data.per_sexo || "";
            per_fecha_nacimiento_Apoderado.value = data.per_fecha_nacimiento || "";
            per_estado_civil_Apoderado.value = data.per_estado_civil || "";
            per_pais_Apoderado.value = data.per_pais || "";
            per_direccion_Apoderado.value = data.per_direccion || "";
            per_celular_Apoderado.value = data.per_celular || "";
            per_email_Apoderado.value = data.per_email || "";
            per_parentesco_Apoderado.value = data.per_parentesco || "";
            per_vive_con_estudiante_Apoderado.value = data.per_vive_con_estudiante || "";
            per_departamento_Apoderado.value = data.per_departamento || "";
            preselectLocation(data, "Apoderado");
            return;
        } else {
            let apellidos = data.apellidoPaterno + " " + data.apellidoMaterno;

            per_dni_Apoderado.value = data.dni || "";
            per_nombres_Apoderado.value = data.nombres || "";
            per_apellidos_Apoderado.value = apellidos || "";
            per_pais_Apoderado.value = "Perú";

            return alert("El Apoderado es nuevo por favor complete los datos");
        }
    }
    return alert("No se encontro el DNI");
}


function preselectLocation(data, group) {
    const provincia = document.getElementById(`per_provincia_${group}`);
    const distrito = document.getElementById(`per_distrito_${group}`);

    // Cargar provincias y seleccionar la correspondiente
    loadProvincias(data.per_departamento, provincia).then(() => {
        provincia.value = data.per_provincia;
        provincia.readonly = true;

        // Cargar distritos y seleccionar el correspondiente
        loadDistritos(data.per_provincia, distrito).then(() => {
            distrito.value = data.per_distrito;
            distrito.readonly = true;
        });
    });
}
