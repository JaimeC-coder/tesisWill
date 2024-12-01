const initialCheckboxValues = {};


const urlParams = new URLSearchParams(window.location.search);
const niv_id = urlParams.get('niv_id');


function asignacionMasivaCurso(docente){
    let newcheckboxes = handleCheckboxChange(docente);
    let oldcheckboxes = initialCheckboxValues[docente] || [];
    if(newcheckboxes.length === 0 || newcheckboxes.length === oldcheckboxes.length){
        alert("No ha seleccionado nuevos cursos a asignar.");
        return;
    }
    fetch('/api/asignacionMasivaCurso', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            docente: docente,
            cursos: newcheckboxes,
            niv_id: niv_id
            }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        console.log(data.status);
        if(data.status === 1){
            alert("Cursos asignados correctamente.");
            location.reload();
        }
    })
    .catch((error) => {
        console.error( error);
        alert("Error al asignar cursos.");
        location.reload();
    });
}
function eliminacionMasivaCurso(aux){
    fetch('/api/EliminacionMasivaCurso', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            docente: aux,
            niv_id: niv_id
         }),
    })
    .then(response => response.json())
    .then(data => {
      console.log(data);
        console.log(data.status);
        if(data.status === 1){
            alert("Cursos eliminados correctamente.");
            location.reload();
        }
    })
    .catch((error) => {
        console.error( error);
        alert("Error al eliminar cursos.");
        location.reload();
    }
    );

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




