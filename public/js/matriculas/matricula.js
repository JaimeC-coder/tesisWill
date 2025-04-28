let dni = document.getElementById("dni");
let nombre = document.getElementById("nombre");
let btnbuscar = document.getElementById("btndni");
let apoderado = document.getElementById("apoderado");
let parentesco = document.getElementById("parentesco");
let vive_con_estudiante = document.getElementById("vive_con_estudiante");
let fechamatricula = document.getElementById("fechamatricula");
let per_id = document.getElementById("per_id");
let niv_id = document.getElementById("niv_id");
let gra_id = document.getElementById("gra_id");
let sec_id = document.getElementById("sec_id");
let ala_id = document.getElementById("ala_id");
let alu_id = document.getElementById("alu_id");
let aula = document.getElementById("aula");
let vacantes = document.getElementById("vacantes");
let matricula_info1 = document.getElementById("matricula_info1");
let matricula_info2 = document.getElementById("matricula_info2");
let matricula_info3 = document.getElementById("matricula_info3");

document.addEventListener("DOMContentLoaded", function () {
    // Obtener la fecha actual
    const fechaActual = new Date();
    // Formatear la fecha en el formato deseado (DD/MM/YYYY)
    const dia = String(fechaActual.getDate()).padStart(2, '0');
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexed
    const anio = fechaActual.getFullYear();
    const fechaFormateada = `${anio}-${mes}-${dia}`;
    // Asignar la fecha formateada al campo de fecha
    fechamatricula.value = fechaFormateada;

        dni.addEventListener("input", async function () {
            if (dni.value.length == 8) {
                btnbuscar.disabled = false;
            }
        });

    btnbuscar.addEventListener("click", async function () {
        if (dni.value.length == 8) {
            const infouser1 = await fetchData("/api/alumnoMatricula",{ dni: dni.value });
            console.log(infouser1);
            if (infouser1.alumno == 1) {
                mostrar(false);
                alert(infouser1.data);
                return;
            }


            alu_id.value = infouser1.data.alu_id;
            nombre.value = infouser1.data.per_nombre_completo == null ? infouser1.data.per_nombres + "  "+infouser1.data.per_apellidos  : infouser1.data.per_nombre_completo;
            apoderado.value = infouser1.data.apo_nombre_completo;
            parentesco.value = infouser1.data.apo_parentesco;
            vive_con_estudiante.value = infouser1.data.apo_vive_con_estudiante =1 ? "Si" : "No";
            mostrar(true);
        } else {
            mostrar(false);
            alert("Ingrese un DNI válido");
        }
    });
    per_id.addEventListener("change", async function () {
        niv_id.disabled = false;
    }
    );
    niv_id.addEventListener("change", async function () {
        const grados = await fetchData("/api/showGrados", {
            niv_id: niv_id.value,
            alu_id:alu_id.value
        });
        gra_id.disabled = false;
        gra_id.innerHTML = "";

        grados.forEach((grado) => {
            gra_id.innerHTML += `<option value="${grado.gra_id}">${grado.gra_descripcion}</option>`;
        });
    });
    gra_id.addEventListener("change", async function () {

        const secciones = await fetchData("/api/showSecciones", { gra_id: gra_id.value });
        sec_id.disabled = false;
        sec_id.innerHTML = "";
        sec_id.innerHTML = `<option value="">Seleccione una sección</option>`;
        secciones.forEach((seccion) => {
            sec_id.innerHTML += `<option value="${seccion.sec_id}">${seccion.sec_descripcion}</option>`;
        });
    });
    sec_id.addEventListener("change", async function () {

        const secciones = await fetchData("/api/infoSecciones", { seccion: sec_id.value });
        console.log(secciones);
        aula.value = secciones.aula;
        vacantes.value = secciones.sec_vacantes;
        ala_id.value = secciones.ala_id;
    });

});




// Función genérica para realizar solicitudes fetch
async function fetchData(url, body) {
    console.log(url);
    console.log(body);
    const response = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
    });
    console.log(response);
    if (!response.ok) {
        throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    return await response.json();
}

function mostrar(value) {
    if(value){
        matricula_info1.classList.remove("d-none");
        matricula_info2.classList.remove("d-none");
        matricula_info3.classList.remove("d-none");
        return;
    }else{
        matricula_info1.classList.add("d-none");
        matricula_info2.classList.add("d-none");
        matricula_info3.classList.add("d-none");
        return;
    }
}
