const anio = document.getElementById('anio');
const nivel = document.getElementById('nivel');
const btnReporte = document.getElementById('btnReporte');


btnReporte.addEventListener('click', showReport);


function showReport() {
    loadMatricula();
    loadPersonal();
    loadSexo();
    loadPersonalAcargo();
    loadVacantes();
}


async function loadMatricula() {
    try {
        const horarios = await fetchData('/api/reporte/matricula', { anio_id: anio.value, nivel_id: nivel.value });
        grafico1(horarios.grados,horarios.totales)
    } catch (error) {
        console.error("Error al cargar horarios:", error);
    }

}
async function loadPersonal() {
    try {
        const horarios = await fetchData('/api/reporte/personal', { anio_id: anio.value, nivel_id: nivel.value });
        grafico2(horarios.cargos,horarios.total,horarios.totales)
    } catch (error) {
        console.error("Error al cargar horarios:", error);
    }

}
async function loadSexo() {
    try {
        const horarios = await fetchData('/api/reporte/sexo', { anio_id: anio.value, nivel_id: nivel.value });
        grafico3(horarios.totales,horarios.sexos)
    } catch (error) {
        console.error("Error al cargar horarios:", error);
    }

}
async function loadPersonalAcargo() {
    try {
        const horarios = await fetchData('/api/reporte/countPersonal', { anio_id: anio.value, nivel_id: nivel.value });
        console.log(horarios);
        grafico4(horarios.cursos,horarios.totales);
    } catch (error) {
        console.error("Error al cargar horarios:", error);
    }

}
async function loadVacantes() {
    try {
        const horarios = await fetchData('/api/reporte/vacante', { anio_id: anio.value, nivel_id: nivel.value });
        console.log(horarios);
        grafico5(horarios.seccion,horarios.totales)
    } catch (error) {
        console.error("Error al cargar horarios:", error);
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




//------------------------------------------------------------
function grafico1(grados,totales) {

    $('#grafico1').remove();
    $('#div_grafico1').append('<canvas id="grafico1"><canvas>');

    var ctx = document.getElementById('grafico1');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grados,
            datasets: [{
                label: 'Alumnos'/* (this.data.nivel == 1 ? 'Nivel Primaria' : 'Nivel Secundaria') */,
                data: totales,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


function grafico2(cargos,total,totales) {
    $('#grafico2').remove();
    $('#div_grafico2').append('<canvas id="grafico2"><canvas>');

    var ctx = document.getElementById('grafico2');

    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: cargos,
            datasets: [{
                label: 'Total',
                data: totales,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 191, 191)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)',
                ],
                hoverOffset: total
            }]
        }
    });
}
function grafico3(totales,sexos) {

    $('#grafico3').remove();
    $('#div_grafico3').append('<canvas id="grafico3"><canvas>');

    var ctx = document.getElementById('grafico3');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: sexos,
            datasets: [{
                label: 'Alumnos'/* (this.data.nivel == 1 ? 'Nivel Primaria' : 'Nivel Secundaria') */,
                data: totales,
                backgroundColor: [
                    'rgb(5, 155, 255)',
                    'rgb(255, 64, 105)'

                ],
                hoverOffset: 2
            }]
        },
    });
}



function grafico4(cursos,totales) {
    $('#grafico4').remove();
    $('#div_grafico4').append('<canvas id="grafico4"><canvas>');

    var ctx = document.getElementById('grafico4');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: cursos,
            datasets: [{
                label: 'Docentes',
                data: totales,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                ],
                borderColor: [
                    'rgb(75, 192, 192)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
function grafico5(seccion,totales) {
    $('#grafico5').remove();
    $('#div_grafico5').append('<canvas id="grafico5"><canvas>');

    var ctx = document.getElementById('grafico5');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: seccion,
            datasets: [{
                axis: 'y',
                label: 'Vacantes',
                data: totales,
                fill: false,
                backgroundColor: [
                    'rgba(255, 159, 64, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 159, 64)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
        }
    });
}




