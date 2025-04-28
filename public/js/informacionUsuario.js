let dni= document.getElementById("per_dni");
let per_id = document.getElementById("per_id");
let nombre=document.getElementById("per_nombres");
let apellido =document.getElementById("per_apellidos");
let telefono =document.getElementById("per_celular");
let email=document.getElementById("per_email");
let pais =document.getElementById("per_pais");
let direccion=document.getElementById("per_direccion");
let fecha=document.getElementById("per_fecha_nacimiento");
let sexo =document.getElementById("per_sexo");
let estadocivil =document.getElementById("per_estado_civil");
let btnDni = document.getElementById("buscarDni");
let flexCheckDefault = document.getElementById("flexCheckDefault");
let flexCheckDefaultlabel = document.getElementById("flexCheckDefaultlabel");

function dniEvents(){

    dni.addEventListener("input", function () {
        if (dni.value.length >=8) {
            btnDni.disabled = false
        }else {
            btnDni.disabled = true; // Deshabilitar el botón si la longitud es insuficiente
        }
    }
    );
}

function searchInformation(){


    let dniValue = dni.value;

    fetch('/api/personas', {
        method: "POST", // Método HTTP
        headers: {
            "Content-Type": "application/json", // Indica el tipo de contenido
        },
        body: JSON.stringify({ per_dni: dniValue }), // Datos en formato JSON
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if(data && data.success){
            let apellidocompleto =data.apellidoMaterno+" "+data.apellidoPaterno;
            nombre.value =data.nombres || "";
            apellido.value=apellidocompleto || "";
            dni.value =data.dni || "";
            pais.value="Perú";
            alert("Datos encontrados");
            return;
        }else if(data && data.per_dni){

            per_id.value=data.per_id;
            nombre.value =data.per_nombres || "";
            apellido.value=data.per_apellidos || "";
            telefono.value=data.per_celular || "";
            pais.value=data.per_pais || "";
            email.value=data.per_email || "";
            direccion.value=data.per_direccion;
            fecha.value=data.per_fecha_nacimiento;
            sexo.value=data.per_sexo;
            estadocivil.value=data.per_estado_civil;
            departamento.value=data.per_departamento;
            // Cargar provincias y seleccionar la correspondiente
            loadProvincias(data.per_departamento).then(() => {
                provincia.value = data.per_provincia;
                provincia.disabled = true;

                // Cargar distritos y seleccionar el correspondiente
                loadDistritos(data.per_provincia).then(() => {
                    distrito.value = data.per_distrito;
                    distrito.disabled = true;
                });
            });
            return;
        }else{
            alert("No se encontraron datos con el DNI ingresado");
            blockInput(false);
            return;
        }
    })
    .catch((error) => {
        console.error( error);
    });
}


document.addEventListener('DOMContentLoaded', function () {
dniEvents();
if(per_id.value){
    btnDni.hidden=true;
    flexCheckDefault.hidden=true;
    flexCheckDefaultlabel.hidden=true;
    dni.disabled=true;
    departamento.disabled=true;
    provincia.disabled=true;
    distrito.disabled=true;
    searchInformation();
}


btnDni.addEventListener("click", function(){searchInformation();});
flexCheckDefault.addEventListener("click", function(){checked();});
});

function checked(){
    if(flexCheckDefault.checked){
        clearInput();
        blockInput(false);
        btnDni.hidden=true;

    }else{
        blockInput(true);
        btnDni.hidden=false;
    }

}

function clearInput(){
    dni.value="";
    nombre.value="";
    apellido.value="";
    telefono.value="";
    pais.value="";
    email.value="";
    direccion.value="";
    fecha.value="";
    sexo.value="";
    estadocivil.value="";
}
function blockInput(status){
    nombre.disabled=status;
    apellido.disabled=status;
    telefono.disabled=status;
    pais.disabled=status;
    email.disabled=status;
    direccion.disabled=status;
    fecha.disabled=status;
    sexo.disabled=status;
    estadocivil.disabled=status;
}
