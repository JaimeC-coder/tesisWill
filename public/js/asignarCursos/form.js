const initialCheckboxValues = {};

function grabarAsignacion(docente){

    let newcheckboxes = handleCheckboxChange(docente);
    let oldcheckboxes = initialCheckboxValues[docente] || [];
    console.log(docente);
    console.log(handleCheckboxChange(docente));
    console.log("grabando");
    if(newcheckboxes.length === 0 || newcheckboxes.length === oldcheckboxes.length){
        alert("No ha seleccionado nuevos cursos a asignar.");
        return;
    }

}
function eliminarAsignacion(aux){
    fetch('/api/eliminarAsignacion', {
        method: "POST", // Método HTTP
        headers: {
            "Content-Type": "application/json", // Indica el tipo de contenido
        },
        body: JSON.stringify({
            docente: aux
         }), // Datos en formato JSON
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch((error) => {
        console.error( error);
    }
    );
    
}
function guardandoCursos(docente,curso){

    fetch('/api/asignarCurso', {
        method: "POST", // Método HTTP
        headers: {
            "Content-Type": "application/json", // Indica el tipo de contenido
        },
        body: JSON.stringify({
            docente: docente,
            curso: curso
         }), // Datos en formato JSON
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // if(data && data.per_dni){
        //     blockInput(true);
        //     per_id.value=data.per_id;
        //     nombre.value =data.per_nombres || "";
        //     apellido.value=data.per_apellidos || "";
        //     telefono.value=data.per_telefono || "";
        //     pais.value=data.per_pais || "";
        //     email.value=data.per_email || "";
        //     direccion.value=data.per_direccion;
        //     fecha.value=data.per_fecha_nacimiento;
        //     sexo.value=data.per_sexo;
        //     estadocivil.value=data.per_estado_civil;
        //     departamento.value=data.per_departamento;
        //     // Cargar provincias y seleccionar la correspondiente
        //     loadProvincias(data.per_departamento).then(() => {
        //         provincia.value = data.per_provincia;
        //         provincia.disabled = true;

        //         // Cargar distritos y seleccionar el correspondiente
        //         loadDistritos(data.per_provincia).then(() => {
        //             distrito.value = data.per_distrito;
        //             distrito.disabled = true;
        //         });
        //     });
        // }else{
        //     alert("No se encontraron datos");
        // }
    })
    .catch((error) => {
        console.error( error);
    });


    // console.log(docente);
    // console.log(curso);
    // console.log(handleCheckboxChange(docente));
}
function eliminarCurso(docente,curso){

    fetch('/api/eliminarCurso', {
        method: "POST", // Método HTTP
        headers: {
            "Content-Type": "application/json", // Indica el tipo de contenido
        },
        body: JSON.stringify({
            docente: docente,
            curso: curso
         }), // Datos en formato JSON
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);

    })
    .catch((error) => {
        console.error( error);
    }
    );


    // console.log(docente);
    // console.log(curso);
    // console.log(handleCheckboxChange(docente));
}


function handleCheckboxChange(docenteId) {
    const row = document.querySelector(`tr[data-docente-id="${docenteId}"]`);
    if (!row) return;
    const selectedCheckboxes = Array.from(row.querySelectorAll('input[type="checkbox"]:checked'))
        .map(checkbox => checkbox.value);
  return selectedCheckboxes;

}




function initializeCheckboxValues() {

    const rows = document.querySelectorAll('tr[data-docente-id]');

    rows.forEach(row => {
        const docenteId = row.getAttribute('data-docente-id');
        const selectedCheckboxes = Array.from(row.querySelectorAll('input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);
        initialCheckboxValues[docenteId] = selectedCheckboxes;
    });

    console.log("Valores iniciales cargados:", initialCheckboxValues);
    return initialCheckboxValues;
}


initializeCheckboxValues();




function grabar_asignacion(item) {
    if (this.asignar.curso.length === 0) {
        alert("No ha seleccionado nuevos cursos a asignar.");
        return
    }
    this.asignar.persona_id = item;
    axios.post("/api/agregar-asignacion", {
        params: {
            asignar: this.asignar,
        }
    }).then(() => {
        this.buscar_data();
        swal({
            title: "Datos Registrados !!",
            icon: "success",
        }).then(() => {
            this.asignar.curso = [];
        });
    }).catch(error => {
        console.log(error)
    })
}
function eliminar_asignacion(item) {
    swal({
        title: "Estas seguro de borrar los datos selecionados?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                axios.post("/api/eliminar-asignacion", {
                    params: {
                        asignar: item,
                    }
                })
                    .then(() => {
                        setTimeout(() => {
                            this.buscar_data();
                            swal({
                                title: "Datos Eliminados !!",
                                icon: "success",
                            }).then(() => {
                                this.asignar.curso = [];
                            });
                        }, 1000);
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        });
}
function agregar_asignar() {
    this.loading = true;
    axios.post("/api/agregar-asignares", {
        params: {
            asignar: this.asignar,
        }
    })
        .then(() => {
            setTimeout(() => {
                this.loading = false;
                swal({
                    title: "asignar Registrada !!",
                    icon: "success",
                }).then(() => {
                    this.limpiar_campos();
                    this.listar_asignares()
                    this.cancelar();
                });
            }, 1000);
        })
        .catch(error => {
            console.log(error)
        })

}
function eliminar_asignar(id) {
    swal({
        title: "Estas seguro de eliminar este asignar?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                axios.post("/api/eliminar-asignares", {
                    params: {
                        id_asignar: id
                    }
                })
                    .then(() => {
                        setTimeout(() => {
                            swal({
                                title: "asignar Eliminado !!",
                                icon: "success",
                            }).then(() => {
                                this.listar_asignares()
                            });
                        }, 1000);
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        });
}
function actualizar_asignar() {
    this.loading = true;
    axios.post("/api/actualizar-asignares", {
        params: {
            asignar: this.asignar_update,
        }
    })
        .then(() => {
            setTimeout(() => {
                this.loading = false;
                swal({
                    title: "asignar Actualizado !!",
                    icon: "success",
                }).then(() => {
                    this.limpiar_campos2();
                    this.listar_asignares()
                    this.cancelar2();
                });
            }, 1000);
        })
        .catch(error => {
            console.log(error)
        })
}
