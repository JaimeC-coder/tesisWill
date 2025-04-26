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
let emailcorreo = document.getElementById("email");
let nameUserhidden = document.getElementById("nameUserhidden");
let emailhidden = document.getElementById("emailhidden");
let apellidoshidden = document.getElementById("apellidoshidden");
let nombreshidden = document.getElementById("nombreshidden");
let paishidden = document.getElementById("paishidden");
let usuario = document.getElementById("usuario");

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
            apellidoshidden.value=apellidocompleto || "";
            dni.value =data.dni || "";
            pais.value="Perú";
            paishidden.value="Perú";
            email.value=data.nombres.charAt(0).toLowerCase() +data.apellidoPaterno.toLowerCase()+data.dni.substring(0,3)+"@colegio.com";
            emailhidden.value=data.nombres.charAt(0).toLowerCase() +data.apellidoPaterno.toLowerCase()+data.dni.substring(0,3)+"@colegio.com";
            email.disabled=true;
            emailcorreo.value=data.nombres.charAt(0).toLowerCase() +data.apellidoPaterno.toLowerCase()+data.dni.substring(0,3)+"@colegio.com";
            usuario.value=data.nombres.toLowerCase()+" "+apellidocompleto.toLowerCase();
            nameUserhidden.value=data.nombres.toLowerCase()+" "+apellidocompleto.toLowerCase();
            usuario.disabled=true;
            emailcorreo.disabled=true;
            alert("Datos encontrados");
            return;
        }else if(data && data.per_dni){
            // Si correo es igual a null, entonces no se encuentra en la base de datos
            if(data.per_email == null){

                email.value=data.per_nombres.charAt(0).toLowerCase() +data.per_apellidos.split(" ")[0].toLowerCase()+data.per_dni.substring(0,3)+"@colegio.com";
                email.disabled=true;
                emailcorreo.value=data.per_nombres.charAt(0).toLowerCase() +data.per_apellidos.split(" ")[0].toLowerCase()+data.per_dni.substring(0,3)+"@colegio.com";
                emailcorreo.disabled=true;
                emailhidden.value=data.per_nombres.charAt(0).toLowerCase() +data.per_apellidos.split(" ")[0].toLowerCase()+data.per_dni.substring(0,3)+"@colegio.com";
                per_id.value=data.per_id;
                nombre.value =data.per_nombres || "";
                apellido.value=data.per_apellidos || "";
                telefono.value=data.per_celular || "";
                pais.value=data.per_pais || "";
                direccion.value=data.per_direccion;
                fecha.value=data.per_fecha_nacimiento;
                sexo.value=data.per_sexo;
                estadocivil.value=data.per_estado_civil;
                departamento.value=data.per_departamento;
                usuario.value=data.per_nombres+" "+data.per_apellidos;
                paishidden.value=data.per_pais;
                apellidoshidden.value=data.per_apellidos;
                nombreshidden.value=data.per_nombres;
                nameUserhidden.value=data.per_nombres+" "+data.per_apellidos;
                usuario.disabled=true;

                //
                data.per_nombres==null ? nombre.disabled=false : nombre.disabled=true;
                data.per_apellidos==null ? apellido.disabled=false : apellido.disabled=true;
                nombre.addEventListener("input", syncUser);
                apellido.addEventListener("input", syncUser);



            }else{
                per_id.value=data.per_id;
                nombre.value =data.per_nombres || "";
                apellido.value=data.per_apellidos || "";
                telefono.value=data.per_celular || "";
                pais.value=data.per_pais || "";
                direccion.value=data.per_direccion;
                fecha.value=data.per_fecha_nacimiento;
                sexo.value=data.per_sexo;
                estadocivil.value=data.per_estado_civil;
                departamento.value=data.per_departamento;
                usuario.value=data.per_nombres+" "+data.per_apellidos;
                nameUserhidden.value=data.per_nombres+" "+data.per_apellidos;
                usuario.disabled=true;
                email.value=data.per_email || "";
                emailcorreo.value=data.per_email || "";
                emailhidden.value=data.per_email || "";
                paishidden.value=data.per_pais;
                nombreshidden.value=data.per_nombres;
                apellidoshidden.value=data.per_apellidos;
                emailcorreo.disabled=true;

                data.per_nombres==null ? nombre.disabled=false : nombre.disabled=true;
                data.per_apellidos==null ? apellido.disabled=false : apellido.disabled=true;
                nombre.addEventListener("input", syncUser);
                apellido.addEventListener("input", syncUser);
            }

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
            nombre.addEventListener("input", syncUser);
            apellido.addEventListener("input", syncUser);
            email.addEventListener("input", syncEmail);
           
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
        paishidden.value=pais.value;
        email.addEventListener("input", syncEmail);
        nombre.addEventListener("input", syncUser);
        apellido.addEventListener("input", syncUser);
        dni.addEventListener("input", dniValidation);
        emailcorreo.disabled = true;
        usuario.disabled = true;
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
    emailcorreo.disabled=status;
    direccion.disabled=status;
    fecha.disabled=status;
    sexo.disabled=status;
    estadocivil.disabled=status;
}

function syncEmail() {
    emailcorreo.value = email.value;
    emailhidden.value = email.value;
    emailcorreo.disabled = true;

    if (flexCheckDefault.checked && email.value.length > 5) {
        verificarEmailExistente(email.value);
    }
}
function syncUser() {
    usuario.value = nombre.value + " " + apellido.value;
    nameUserhidden.value = nombre.value + " " + apellido.value;
    nombreshidden.value = nombre.value;
    apellidoshidden.value = apellido.value;
}
function dniValidation() {

    if (dni.value.length >8) {
        //ya no puede escribir
        dni.value = dni.value.substring(0, 8);
    }
}


function verificarEmailExistente(correo) {
    fetch('/api/verificar-correo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: correo })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.existe) {
            alert("El correo ingresado ya está registrado en el sistema.");
        }
    })
    .catch(error => {
        console.error('Error al verificar el correo:', error);
    });
}

