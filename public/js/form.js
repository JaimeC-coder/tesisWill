document.addEventListener('DOMContentLoaded', function () {
     
    let anio = document.getElementById('anio');
    var d = new Date();
    var n = d.getFullYear() + 1;

    // Llenar el select con los años 2010 a n
    for (var i = n; i >= 2010; i--) {
        var opc = document.createElement("option");
        opc.text = i;
        opc.value = i;
        anio.add(opc);
    }

    // Establecer el valor seleccionado
    if (window.anioSeleccionado) {
        anio.value = window.anioSeleccionado;
    }
});
