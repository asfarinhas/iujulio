
function nif(dni) {
    var numero
    var letr
    var letra
    var expresion_regular_dni

    expresion_regular_dni = /^\d{8}[a-zA-Z]$/;

    if (expresion_regular_dni.test(dni) == true) {
        numero = dni.substr(0, dni.length - 1);
        letr = dni.substr(dni.length - 1, 1);
        numero = numero % 23;
        letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
        letra = letra.substring(numero, numero + 1);
        if (letra != letr.toUpperCase()) {
            alert('Dni erróneo, la letra del NIF no se corresponde');
            return false;
        } else {

            return true;
        }
    } else {
        alert('Dni erroneo, formato no válido');
        return false;
    }
}
function validaCCC(val) {
    var banco = val.substring(0, 4);
    var sucursal = val.substring(4, 8);
    var dc = val.substring(8, 10);
    var cuenta = val.substring(10, 20);
    var CCC = banco + sucursal + dc + cuenta;
    if (!/^[0-9]{20}$/.test(banco + sucursal + dc + cuenta)) {
        return false;
    } else
    {
        valores = new Array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6);
        control = 0;
        for (i = 0; i <= 9; i++)
            control += parseInt(cuenta.charAt(i)) * valores[i];
        control = 11 - (control % 11);
        if (control == 11)
            control = 0;
        else if (control == 10)
            control = 1;
        if (control != parseInt(dc.charAt(1))) {
            return false;
        }
        control = 0;
        var zbs = "00" + banco + sucursal;
        for (i = 0; i <= 9; i++)
            control += parseInt(zbs.charAt(i)) * valores[i];
        control = 11 - (control % 11);
        if (control == 11)
            control = 0;
        else if (control == 10)
            control = 1;
        if (control != parseInt(dc.charAt(0))) {
            return false;
        }
        return true;
    }
}
function validarEmail(email) {
    expr = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    if (!expr.test(email)) {
        return false;
    } else {
        return true;
    }
}

function validarFechaMenorActual(date) {
    var x = new Date();
    var fecha = date.split("-");

    x.setFullYear(fecha[0], fecha[1] - 1, fecha[2]);

    var today = new Date();

    if (x >= today)
        return false;
    else
        return true;
}
function valida_envia2() {

    if (document.form.ROL_NOM.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.ROL_NOM.focus();
        return false;
    }
    if (document.form.ROL_NOM.value.length < 2) {
        alert("Nombre del rol demasiado corto (min 2 caracteres)");
        document.form.ROL_NOM.focus();
        return false;
    }
    if (document.form.ROL_NOM.value.length > 25) {
        alert("Nombre del rol demasiado largo (max 25 caracteres)");
        document.form.ROL_NOM.focus();
        return false;
    }
    return true;

}
function valida_envia3() {

    if (document.form.FUNCIONALIDAD_NOM.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.FUNCIONALIDAD_NOM.focus();
        return false;
    }
    if (document.form.FUNCIONALIDAD_NOM.value.length < 2) {
        alert("Nombre de la funcionalidad demasiado corto (min 2 caracteres)");
        document.form.FUNCIONALIDAD_NOM.focus();
        return false;
    }
    if (document.form.FUNCIONALIDAD_NOM.value.length > 50) {
        alert("Nombre de la funcionalidad demasiado largo (max 50 caracteres)");
        document.form.FUNCIONALIDAD_NOM.focus();
        return false;
    }
    return true;

}
function valida_envia4() {

    if (document.form.PAGINA_NOM.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.PAGINA_NOM.focus();
        return false;
    }
    if (document.form.PAGINA_NOM.value.length < 2) {
        alert("Nombre de la página  demasiado corto (min 2 caracteres)");
        document.form.PAGINA_NOM.focus();
        return false;
    }
    if (document.form.PAGINA_NOM.value.length > 25) {
        alert("Nombre de la página demasiado largo (max 25 caracteres)");
        document.form.PAGINA_NOM.focus();
        return false;
    }
    return true;

}

//----------------------------------------------------------
function valida_envia_PAGO() {

    if (!nif(document.form.CLIENTE_DNI.value)) {
        document.form.CLIENTE_DNI.focus();
        return false;
    }

    if (document.form.PAGO_CONCEPTO.value.length == 0) {
        alert("Introduzca un valor para el concepto");
        document.form.PAGO_CONCEPTO.focus();
        return false;
    }
    if (document.form.PAGO_CONCEPTO.value.length > 200) {
        alert("Concepto de pago demasiado largo (max 200 caracteres)");
        document.form.PAGO_CONCEPTO.focus();
        return false;
    }
    if (document.form.PAGO_IMPORTE.value.length == 0) {
        alert("Introduzca un valor para el importe");
        document.form.PAGO_IMPORTE.focus();
        return false;
    }
    if (document.form.PAGO_IMPORTE.value.length > 10) {
        alert("Importe demasiado largo (max 10 caracteres)");
        document.form.PAGO_IMPORTE.focus();
        return false;
    }
    return true;
}
//----------------------------------------------------------

//Nombramos la función
function valida_envia() {
    if (document.form.EMP_USER.value.length == 0) {
        alert("Introduzca un valor para el usuario");
        document.form.EMP_USER.focus();
        return false;
    }
    if (document.form.EMP_USER.value.length < 2) {
        alert("Nombre de usuario demasiado corto (mínimo 2 caracteres)");
        document.form.EMP_USER.focus();
        return false;
    }
    if (document.form.EMP_USER.value.length > 25) {
        alert("Nombre de usuario demasiado largo (máximo 25 caracteres)");
        document.form.EMP_USER.focus();
        return false;
    }

    if (document.form.EMP_PASSWORD.value.length == 0) {
        alert("Introduzca un valor para la contraseña");
        document.form.EMP_PASSWORD.focus();
        return false;
    }

    if (document.form.EMP_PASSWORD.value.length < 2) {
        alert("Contraseña demasiado corta (mínimo 2 caracteres)");
        document.form.EMP_PASSWORD.focus();
        return false;
    }

    if (document.form.EMP_PASSWORD.value.length > 32) {
        alert("Contraseña demasiado larga (máximo 32 caracteres)");
        document.form.EMP_PASSWORD.focus();
        return false;
    }

//Validamos un campo o área de texto, por ejemplo el campo nombre
    if (document.form.EMP_NOMBRE.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.EMP_NOMBRE.focus();
        return false;
    }
    if (document.form.EMP_NOMBRE.value.length < 2) {
        alert("Nombre demasiado corto (mínimo 2 caracteres)");
        document.form.EMP_NOMBRE.focus();
        return false;
    }
    if (document.form.EMP_NOMBRE.value.length > 25) {
        alert("Nombre demasiado largo (máximo 25 caracteres)");
        document.form.EMP_NOMBRE.focus();
        return false;
    }


    if (document.form.EMP_APELLIDO.value.length == 0) {
        alert("Introduzca un valor para el apellido");
        document.form.EMP_APELLIDO.focus();
        return false;
    }
    if (document.form.EMP_APELLIDO.value.length < 2) {
        alert("Apellido demasiado corto (mínimo 2 caracteres)");
        document.form.EMP_APELLIDO.focus();
        return false;
    }
    if (document.form.EMP_APELLIDO.value.length > 50) {
        alert("Apellido demasiado largo (máximo 50 caracteres)");
        document.form.EMP_APELLIDO.focus();
        return false;
    }



    if (!nif(document.form.EMP_DNI.value)) {
        document.form.EMP_DNI.focus();
        return false;
    }

    if (document.form.EMP_FECH_NAC.value == false) {
        alert("Introduzca un valor  para la fecha de nacimiento");
        document.form.EMP_FECH_NAC.focus();
        return false;
    }
    if (!validarFechaMenorActual(document.form.EMP_FECH_NAC.value)) {
        alert("¿Viene del futuro?");
        document.form.EMP_FECH_NAC.focus();
        return false;
    }
    if (((document.form.EMP_EMAIL.value.length == 0) || !validarEmail(document.form.EMP_EMAIL.value))) {
        alert("Introduzca una dirección de email válida");
        document.form.EMP_EMAIL.focus();
        return false;
    }
    valor = document.form.EMP_TELEFONO.value;
    if (!(/^\d{9}$/.test(valor))) {
        alert("Tiene que escribir un teléfono de 9 dígitos");
        document.form.EMP_TELEFONO.focus();
        return false;
    }

    if (document.form.EMP_CUENTA.value.length == 0 || !validaCCC(document.form.EMP_CUENTA.value)) {
        alert("Introduzca un valor correcto para el numero de CCC(sin espacios)");
        document.form.EMP_CUENTA.focus();
        return false;
    }




    if (document.form.EMP_DIRECCION.value.length == 0) {
        alert("Introduzca dirección");
        document.form.EMP_DIRECCION.focus();
        return false;
    }




    return true;

}



function valida_envia_CLIENTE() {

    if (!nif(document.form.CLIENTE_DNI.value)) {
        document.form.CLIENTE_DNI.focus();
        return false;
    }

//Validamos un campo o área de texto, por ejemplo el campo nombre
    if (document.form.CLIENTE_NOMBRE.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.CLIENTE_NOMBRE.focus();
        return false;
    }
    if (document.form.CLIENTE_NOMBRE.value.length < 2) {
        alert("Nombre demasiado corto (mínimo 2 caracteres)");
        document.form.CLIENTE_NOMBRE.focus();
        return false;
    }
    if (document.form.CLIENTE_NOMBRE.value.length > 25) {
        alert("Nombre demasiado largo (máximo 25 caracteres)");
        document.form.CLIENTE_NOMBRE.focus();
        return false;
    }


    if (document.form.CLIENTE_APELLIDOS.value.length == 0) {
        alert("Introduzca un valor para el apellido");
        document.form.CLIENTE_APELLIDOS.focus();
        return false;
    }
    if (document.form.CLIENTE_APELLIDOS.value.length < 2) {
        alert("Apellido demasiado corto (mínimo 2 caracteres)");
        document.form.CLIENTE_APELLIDOS.focus();
        return false;
    }
    if (document.form.CLIENTE_APELLIDOS.value.length > 50) {
        alert("Apellido demasiado largo (máximo 50 caracteres)");
        document.form.CLIENTE_APELLIDOS.focus();
        return false;
    }


    if (document.form.CLIENTE_FECH_NAC.value < '1900-01-01') {
        alert("Introduzca una fecha posterior al 1/1/1900");
        document.form.EMP_FECH_NAC.focus();
        return false;
    }
    if (!validarFechaMenorActual(document.form.CLIENTE_FECH_NAC.value)) {
        alert("¿Viene del futuro?");
        document.form.CLIENTE_FECH_NAC.focus();
        return false;
    }
    if (((document.form.CLIENTE_CORREO.value.length == 0) || !validarEmail(document.form.CLIENTE_CORREO.value))) {
        alert("Introduzca una dirección de email válida");
        document.form.CLIENTE_CORREO.focus();
        return false;
    }
    if (document.form.CLIENTE_PROFESION.value.length == 0) {
        alert("Introduzca profesión");
        document.form.CLIENTE_PROFESION.focus();
        return false;
    }
    if (document.form.CLIENTE_PROFESION.value.length > 50) {
        alert("Profesion demasiado larga (máximo 50 caracteres)");
        document.form.CLIENTE_PROFESION.focus();
        return false;
    }
    valor = document.form.CLIENTE_TELEFONO1.value;

    if (!(/^\d{9}$/.test(valor))) {
        alert("Tiene que escribir un teléfono de 9 dígitos");
        document.form.CLIENTE_TELEFONO1.focus();
        return false;
    }
    if (document.form.CLIENTE_TELEFONO2.value.length !== 0) {
        valor = document.form.CLIENTE_TELEFONO2.value;
        if (!(/^\d{9}$/.test(valor))) {
            alert("Tiene que escribir un teléfono de 9 dígitos");
            document.form.CLIENTE_TELEFONO2.focus();
            return false;
        }
    }
    if (document.form.CLIENTE_TELEFONO3.value.length !== 0) {
        valor = document.form.CLIENTE_TELEFONO3.value;
        if (!(/^\d{9}$/.test(valor))) {
            alert("Tiene que escribir un teléfono de 9 dígitos");
            document.form.CLIENTE_TELEFONO3.focus();
            return false;
        }
    }



    if (document.form.CLIENTE_DIRECCION.value.length == 0) {
        alert("Introduzca dirección");
        document.form.CLIENTE_DIRECCION.focus();
        return false;
    }
    if (document.form.CLIENTE_DIRECCION.value.length > 50) {
        alert("Dirección demasiado larga (máximo 50 caracteres)");
        document.form.CLIENTE_DIRECCION.focus();
        return false;
    }
    if (document.form.CLIENTE_COMENTARIOS.value.length > 50) {
        alert("Comentario demasiado largo (máximo 50 caracteres)");
        document.form.CLIENTE_COMENTARIOS.focus();
        return false;
    }


    return true;

}
function valida_envia_CLIENTEEXT() {
    if (!nif(document.form.CLIENTE_DNI.value)) {
        document.form.CLIENTE_DNI.focus();
        return false;
    }
    if (document.form.CLIENTE_NOMBRE.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.CLIENTE_NOMBRE.focus();
        return false;
    }
    if (document.form.CLIENTE_NOMBRE.value.length < 2) {
        alert("Nombree demasiado corto (mínimo 2 caracteres)");
        document.form.CLIENTE_NOMBRE.focus();
        return false;
    }
    if (document.form.CLIENTE_NOMBRE.value.length > 25) {
        alert("Nombree demasiado largo (máximo 25 caracteres)");
        document.form.CLIENTE_NOMBRE.focus();
        return false;
    }
    if (document.form.CLIENTE_APELLIDOS.value.length == 0) {
        alert("Introduzca un valor para el apellido");
        document.form.CLIENTE_APELLIDOS.focus();
        return false;
    }
    if (document.form.CLIENTE_APELLIDOS.value.length < 2) {
        alert("Apellido demasiado corto (mínimo 2 caracteres)");
        document.form.CLIENTE_APELLIDOS.focus();
        return false;
    }
    if (document.form.CLIENTE_APELLIDOS.value.length > 50) {
        alert("Apellido demasiado largo (máximo 50 caracteres)");
        document.form.CLIENTE_APELLIDOS.focus();
        return false;
    }
    valor = document.form.CLIENTE_TELEFONO1.value;
    if (!(/^\d{9}$/.test(valor))) {
        alert("Tienes que escribir un teléfono de 9 dígitos");
        document.form.CLIENTE_TELEFONO1.focus();
        return false;
    }
    if (((document.form.CLIENTE_CORREO.value.length == 0) || !validarEmail(document.form.CLIENTE_CORREO.value))) {
        alert("Introduzca unha dirección de email válida");
        document.form.CLIENTE_CORREO.focus();
        return false;
    }
    if (document.form.CLIENTE_DIRECCION.value.length == 0) {
        alert("Introduzca dirección");
        document.form.CLIENTE_DIRECCION.focus();
        return false;
    }
    if (document.form.CLIENTE_DIRECCION.value.length > 50) {
        alert("Dirección demasiado larga (máximo 50 caracteres)");
        document.form.CLIENTE_DIRECCION.focus();
        return false;
    }
    if (document.form.CLIENTE_COMENTARIOS.value.length > 50) {
        alert("Comentario demasiado largo (máximo 50 caracteres)");
        document.form.CLIENTE_COMENTARIOS.focus();
        return false;
    }

return true;
}
function valida_envia_HORARIO() {
    if (document.form.HORARIO_NOMBRE.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.HORARIO_NOMBRE.focus();
        return false;
    }
    if (document.form.HORARIO_NOMBRE.value.length < 2) {
        alert("Nombre demasiado corto (mínimo 2 caracteres)");
        document.form.HORARIO_NOMBRE.focus();
        return false;
    }
    if (document.form.HORARIO_NOMBRE.value.length > 25) {
        alert("Nombre demasiado largo (máximo 25 caracteres)");
        document.form.HORARIO_NOMBRE.focus();
        return false;
    }
    if (document.form.HORARIO_FECHAI.value < '1900-01-01') {
        alert("Introduzca una fecha posterior al 1/1/1900");
        document.form.HORARIO_FECHAI.focus();
        return false;
    }
    if (document.form.HORARIO_FECHAI.value > '2029-12-31') {
        alert("Introduzca una fecha anterior al 2029/12/31");
        document.form.HORARIO_FECHAI.focus();
        return false;
    }
    if (document.form.HORARIO_FECHAF.value < '1900-01-01') {
        alert("Introduzca una fecha posterior al 1/1/1900");
        document.form.HORARIO_FECHAF.focus();
        return false;
    }
    if (document.form.HORARIO_FECHAF.value > '2029-12-31') {
        alert("Introduzca una fecha anterior al 2029/12/31");
        document.form.HORARIO_FECHAF.focus();
        return false;
    }
    if (document.form.HORARIO_FECHAI.value > document.form.HORARIO_FECHAF.value) {
        alert("La fecha final debe de ser posterior que la fecha inicial");
        document.form.HORARIO_FECHAF.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO1I.value.substr(3,2)!=='00') {
        alert("El centro abre a horas en punto");
        document.form.HORARIO_RANGO1I.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO1F.value.substr(3,2)!=='00') {
        alert("El centro cierra a horas en punto");
        document.form.HORARIO_RANGO1F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO1I.value >= document.form.HORARIO_RANGO1F.value) {
        alert("La hora de cierre debe de ser posterior a la hora de apertura");
        document.form.HORARIO_RANGO1F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO2I.value.substr(3,2)!=='00') {
        alert("El centro abre a horas en punto");
        document.form.HORARIO_RANGO2I.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO2F.value.substr(3,2)!=='00') {
        alert("El centro cierra a horas en punto");
        document.form.HORARIO_RANGO2F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO2I.value >= document.form.HORARIO_RANGO2F.value) {
        alert("La hora de cierre debe de ser posterior a la hora de apertura");
        document.form.HORARIO_RANGO2F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO3I.value.substr(3,2)!=='00') {
        alert("El centro abre a horas en punto");
        document.form.HORARIO_RANGO3I.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO3F.value.substr(3,2)!=='00') {
        alert("El centro cierra  a horas en punto");
        document.form.HORARIO_RANGO3F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO3I.value >= document.form.HORARIO_RANGO3F.value) {
        alert("La hora de cierre debe de ser posterior a la hora de apertura");
        document.form.HORARIO_RANGO3F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO4I.value.substr(3,2)!=='00') {
        alert("El centro abre a horas en punto");
        document.form.HORARIO_RANGO4I.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO4F.value.substr(3,2)!=='00') {
        alert("El  centro cierra  a horas en punto");
        document.form.HORARIO_RANGO4F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO4I.value >= document.form.HORARIO_RANGO4F.value) {
        alert("La hora de cierre debe de ser posterior a la hora de apertura");
        document.form.HORARIO_RANGO4F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO5I.value.substr(3,2)!=='00') {
        alert("El centro abre a horas en punto");
        document.form.HORARIO_RANGO5I.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO5F.value.substr(3,2)!=='00') {
        alert("El centro cierra a horas en punto");
        document.form.HORARIO_RANGO5F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO5I.value >= document.form.HORARIO_RANGO5F.value) {
        alert("La hora de cierre debe de ser posterior a la hora de apertura");
        document.form.HORARIO_RANGO5F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO6I.value.substr(3,2)!=='00') {
        alert("El centro abre a horas en punto");
        document.form.HORARIO_RANGO6I.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO6F.value.substr(3,2)!=='00') {
        alert("El centro cierra a horas en punto");
        document.form.HORARIO_RANGO6F.focus();
        return false;
    }
    if (document.form.HORARIO_RANGO6I.value >= document.form.HORARIO_RANGO6F.value) {
        alert("La hora de cierre debe de ser posterior a la hora de apertura");
        document.form.HORARIO_RANGO6F.focus();
        return false;
    }
    return true;
}
//Recibe fecha en formato DD/MM/YYYY
function dia_semana(fecha) {
    fecha = fecha.split('-');
    if (fecha.length != 3) {
        return null;
    }
    //Vector para calcular día de la semana de un año regular.
    var regular = [0, 3, 3, 6, 1, 4, 6, 2, 5, 0, 3, 5];
    //Vector para calcular día de la semana de un año bisiesto.
    var bisiesto = [0, 3, 4, 0, 2, 5, 0, 3, 6, 1, 4, 6];
    //Vector para hacer la traducción de resultado en día de la semana.
    var semana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    //Día especificado en la fecha recibida por parametro.
    var dia = fecha[2];
    //Módulo acumulado del mes especificado en la fecha recibida por parametro.
    var mes = fecha[1] - 1;
    //Año especificado por la fecha recibida por parametros.
    var anno = fecha[0];
    //Comparación para saber si el año recibido es bisiesto.
    if ((anno % 4 == 0) && !(anno % 100 == 0 && anno % 400 != 0))
        mes = bisiesto[mes];
    else
        mes = regular[mes];
    //Se retorna el resultado del calculo del día de la semana.
    return semana[Math.ceil(Math.ceil(Math.ceil((anno - 1) % 7) + Math.ceil((Math.floor((anno - 1) / 4) - Math.floor((3 * (Math.floor((anno - 1) / 100) + 1)) / 4)) % 7) + mes + dia % 7) % 7)];
}

function valida_envia_BLOQUE() {



    if (document.form.BLOQUE_HORAI.value >= document.form.BLOQUE_HORAF.value) {
        alert("La hora final debe de ser posterior que la hora inicial");
        document.form.BLOQUE_HORAF.focus();
        return false;
    }

    if (document.form.BLOQUE_HORAI.value.substr(3,2)!=='00') {
        alert("Las horas utilizadas por el centro son horas en punto");
        document.form.BLOQUE_HORAI.focus();
        return false;
    }
    if (document.form.BLOQUE_HORAF.value.substr(3,2)!=='00') {
        alert("Las horas utilizadas por el centro son horas en punto");
        document.form.BLOQUE_HORAF.focus();
        return false;
    }
    if(document.form.BLOQUE_HORAF.value.substr(0,2)- document.form.BLOQUE_HORAI.value.substr(0,2)!==1){
        alert("Las horas disponibles han de durar una hora");

        document.form.BLOQUE_HORAF.focus();
        return false;
    }


    return true;
}


function valida_envia_LESION() {

    if (document.form.LESION_NOM.value.length == 0) {
        alert("Introduzca un valor para el nombre");
        document.form.LESION_NOM.focus();
        return false;
    }
    if (document.form.LESION_NOM.value.length > 50) {
        alert("Nombre demasiado largo (máximo 100 caracteres)");
        document.form.LESION_NOM.focus();
        return false;
    }

    if (document.form.LESION_DESC.value.length > 200) {
        alert("Comentario demasiado largo (máximo 200 caracteres)");
        document.form.LESION_DESC.focus();
        return false;
    }
    return true;
}


function valida_envia_EMAIL() {

    if (((document.form.NOTIFICACION_REMITENTE.value.length == 0) || !validarEmail(document.form.NOTIFICACION_REMITENTE.value))) {
        alert("Introduzca una dirección de gmail válida");
        document.form.NOTIFICACION_REMITENTE.focus();
        return false;
    }

    if (document.form.NOTIFICACION_PASSWORD.value.length == 0) {
        alert("Introduzca un valor para la contraseña");
        document.form.NOTIFICACION_PASSWORD.focus();
        return false;
    }

    if (document.form.NOTIFICACION_PASSWORD.value.length < 8) {
        alert("Contraseña demasiado corta (mínimo 8 caracteres)");
        document.form.NOTIFICACION_PASSWORD.focus();
        return false;
    }

    if (document.form.NOTIFICACION_CUERPO.value.length == 0) {
        alert("No se pueden mandar emails vacios, redacte su email");
        document.form.NOTIFICACION_CUERPO.focus();
        return false;
    }

    return true;
}




//function validar(){
//    var isValid = false;
//
//    for(i=0; i< document.form1.elements['email[]'].length; i++){
//        if(document.form1.elements['email[]'][i].checked){
//            isValid = true
//        }
//    }
//    if (isValid == false){
//        alert("No has marcado nada");
//        return false;
//    } else 
//        return true
//}
//
//function valida_fecha() {
//
//    if ((document.form.NOTIFICACION_FECHAHORA1.value == false) && (document.form.NOTIFICACION_FECHAHORA2.value != false)) {
//        alert("Si establece una Fecha Fin debe establecer una Fecha Inicio");
//        document.form.NOTIFICACION_FECHAHORA1.focus();
//        return false;
//    }
//    if ((document.form.NOTIFICACION_FECHAHORA1.value != false) && (document.form.NOTIFICACION_FECHAHORA2.value == false)) {
//        alert("Si establece una Fecha Inicio debe establecer una Fecha Fin");
//        document.form.NOTIFICACION_FECHAHORA2.focus();
//        return false;
//    }
//    
//    return true;
//
//}
