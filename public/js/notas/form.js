
moment.locale('es');
const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const docente = document.getElementById('docente');
const grado = document.getElementById('grado');
const seccion = document.getElementById('seccion');
const cursoId = document.getElementById('cursoId');
const btnNota = document.getElementById('btnActualizar');
const user = document.getElementById('xd');




document.addEventListener('DOMContentLoaded', function () {
    anio.addEventListener("change", handleAnioChange);
    nivel.addEventListener("change", handleNivelChange);
    docente.addEventListener("change", handleDocenteChange);
    grado.addEventListener("change", handleGradoChange);
    seccion.addEventListener("change", handleSeccionChange);
    btnNota.addEventListener("click", actualizarNota);

});

function actualizarNota() {

    //necesesito obtener el cursoid que esta en la url
    const urlParams = new URLSearchParams(window.location.search);
    const cursoId1 = urlParams.get('cursoId');
    const docente = urlParams.get('docente');

    //const cursoId = document.getElementById('cursoId').value;
    console.log(cursoId1);


    var formNota = document.getElementById("formNota");

    var idAlumno = document.getElementById("idAlumno").value;
    var idCapacidad = document.getElementById("idCapacidad").value;
    var idPeriodo = document.getElementById("idPeriodo").value;
    var notaSeleccionada = document.getElementById("selectNota").value;
    var sga = document.getElementById("sgaId").value;
    var notaId = document.getElementById("idNota").value;
    var periodoId = document.getElementById("periodoId").value;
    var bimestre = document.getElementById("bimestre").value;

    var error = document.getElementById("error");

    if (notaSeleccionada == 0) {

        error.hidden = false;
    } else {
        error.hidden = true;
        updateNota(idAlumno, idCapacidad, idPeriodo, notaSeleccionada, cursoId1, docente , sga, notaId, periodoId,bimestre);
    }

    console.log("ID Alumno:", idAlumno);
    console.log("ID Capacidad:", idCapacidad);
    console.log("ID Periodo:", idPeriodo);
    console.log("Nota Seleccionada:", notaSeleccionada);
    console.log("Curso ID:", cursoId1);
    console.log("Docente:", docente);
    console.log("SGA ID:", sga);
    console.log("Nota ID:", notaId);
    console.log("Periodo ID:", periodoId);



    // Aquí puedes enviar los datos a un servidor usando fetch o AJAX
    // Ejemplo con fetch (descomenta si lo necesitas)
    /*
    fetch("tu-api-url", {
      method: "POST",
      body: JSON.stringify({ idAlumno, idCapacidad, idPeriodo, nota: notaSeleccionada }),
      headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => console.log("Respuesta del servidor:", data))
    .catch(error => console.error("Error:", error));
    */


}

// function registrarNota(value1 ,value2, value3) {
//     console.log(value1);
//     console.log(value2);
//     console.log(value3);


//     // Aquí puedes realizar la lógica para registrar la nota utilizando los valores obtenidos
//     // Por ejemplo, enviar una solicitud AJAX al servidor para guardar la nota en la base de datos

// }




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
        const docentes = await fetchData('/api/nota/docente', { nivel: nivel ,user: user.value});
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
        const secciones = await fetchData('/api/nota/curso', { grado_id: gradoId, teacherId: teacherId, nivelId: nivelId });
        console.log(secciones);
        updateSelectOptions(cursoId, secciones.cursos, "cur_nombre", "cur_id");
    } catch (error) {
        console.error("Error al cargar secciones:", error);
    }
}


async function updateNota(idAlumno, idCapacidad, idPeriodo, notaSeleccionada, cursoId1 , docente, sga, notaId, periodoId ,bimestre) {
    try {
        const response = await fetchData('/api/nota/capacidad/actualizar', {
            idAlumno: idAlumno,
            idCapacidad: idCapacidad,
            idPeriodo: idPeriodo,
            notaSeleccionada: notaSeleccionada,
            cursoId: cursoId1,
            personalAcademico: docente,
            agsId: sga,
            idNota: notaId,
            periodoId: periodoId,
            bimestre: bimestre

        });
        console.log(response);
        if (response.status === 200) {

             mostrarAlerta({
                titulo: "Nota actualizada correctamente",
                icono: "success"
            });
            //recargar la pagina
            window.location.reload();

        } else {
             mostrarAlerta({
                titulo: "Ocurrio un problema al actualizar la nota",
                icono: "warning"
            });
            console.log(response);
        }
    } catch (error) {
        console.error("Error al actualizar la nota:", error);
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



function mostrarAlerta({
  titulo = "Operación realizada",
  icono = "success",
  posicion = "top-end",
  mostrarBoton = false,
  tiempo = 1500
} = {}) {
  Swal.fire({
    position: posicion,
    icon: icono,
    title: titulo,
    showConfirmButton: mostrarBoton,
    timer: tiempo
  });
}
