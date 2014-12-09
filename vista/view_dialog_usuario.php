<form id="form">
    <div id="errores" class="error_dialog"></div>
    <table class="mytable">
        <tr>
          <td class="required">Nombre</td>
          <td><input type="text" name="nombre_apellido" id="nombre_apellido" value="{nombre_apellido}"></td>
        </tr>
        <tr>
          <td class="required">Usuario</td>
          <td><input type="text" name="usuario" id="usuario" value="{usuario}"></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email" id="email" value="{email}"></td>
        </tr>
        <tr type="hidden">
           <td><input type="hidden" name="password_orig" id="password_orig" value="{password}"></td>
        </tr>
        <tr>
          <td>Area</td>
          <td>{select_Areas}</td>
        </tr>
        <tr>
          <td>Permisos</td>
          <td>{select_Permisos}</td>
        </tr>
        <tr>
          <td>Interno</td>
          <td><input type="text" name="interno" id="interno" value="{interno}"></td>
        </tr>
        <tr>
          <td>ID</td>
          <td><input style="background-color:#D3D3D3" type="text" name="id_usuario" id="id_usuario" value="{id_usuario}" readonly></td>
        </tr>
        <tr id="vista_pass">
          <td></td>
          <td><input type="button" id="cambiar_pass" name="boton" value="Cambiar Contraseña"></td>
        </tr>
    </table>
</form>

<script>


$(document).ready(function(){

    var usuario = $('#usuario');
    var usuario_orig = '{usuario}';
    var password = $('#password');
    var nueva_password = $('#nueva_password');
    var conf_password = $('#conf_password');
    var estado = {nuevo};

    $('#select_areas').removeAttr('disabled');
    $("#select_areas option[value=1]").remove();

    if(estado == 1)
    {
        $.get("vista/agregar_datos_password_nueva.php",function(data){
            $("#vista_pass").replaceWith(data);
        });
    }

    $("#form").validate({
        errorLabelContainer : "#errores" ,
        wrapper : "li" ,
        rules : {
            nombre_apellido : {
              required : true ,
              minlength : 5,
              maxlength : 50
            } ,
            usuario : {
              required : true,
              minlength : 3,
              maxlength : 30,
              remote: {
                url: "checkDisponibilidad.php",
                type: "post",
                data: {
                  dato: function() {
                    if($( "#usuario" ).val() != usuario_orig){
                      return $( "#usuario" ).val();
                    }
                  },
                  action: function(){
                    return "chequeo";
                  },
                  tabla: function(){
                    return "Usuarios";
                  }
                }
              }
            },
            area : {
              required : true
            },
            permisos : {
              required : true
            },
            password :{
              required : true,
              equalTo: "#password_orig"
            },
            conf_password : {
              required : true,
              equalTo: "#nueva_password"
            },
            nueva_password : {
              required : true,
            },
            email :{
              required : false,
              email : true
            }
        } ,
        messages : {
            nombre_apellido : {
              required : 'El nombre es OBLIGATORIO',
              minlength : 'El nombre debe tener más de 4 caracteres',
              maxlength : 'El nombre debe tener menos de 50 caracteres'
            },
            usuario : {
              required : 'El usuario es OBLIGATORIO',
              minlength : 'El usuario debe tener más de 2 caracteres',
              maxlength : 'El usuario debe tener menos de 50 caracteres',
              remote : 'El nombre de usuario ya existe'
            },
            area : {
              required : 'El area es OBLIGATORIA'
            },
            permisos : {
              required : 'Los permisos son OBLIGATORIOS'
            },
            password : {
              required : 'La password no puede ser null'
            },
            conf_password : {
              required : 'La confirmarcion de password no puede ser null',
              equalTo: 'Las passwords ingresadas no son iguales'
            },
            nueva_password : {
              required : 'La nueva password es OBLIGATORIA',
              equalTo: "Las passwords ingresadas no son iguales"
            },
            email : {
              email : 'Por favor, ingresa un email con formato correcto'
            }
        } ,
        submitHandler : function (form) {
          console.log ("Formulario OK");
          var estado = {nuevo};

            console.log("Aca empieza el envio de datos de usuario");

            var UrlToPass;
            UrlToPass = $("#form").serialize();

            if(estado == 1){
              UrlToPass+="&action=crear";
            }
            else if(estado == 0){
              UrlToPass+="&action=modificar";
            }
            console.log(UrlToPass);

              $.ajax({
                    type : 'POST',
                    data : UrlToPass,
                    url  : 'controlador/UsuariosController.php',
                    success: function(responseText){ // Obtengo el resultado de exito
                        if(responseText == 0){
                          alert("No se pudieron plasmar los datos. Error de en la Base de datos.");
                        }
                        else if(responseText == 1){
                          console.log("Los datos han sido actualizados correctamente!");
                        }
                        else{
                          alert('Problema en la Sql query');
                        }
                        $("#dialogcontent").dialog("destroy").empty();
                        $("#contenedorPpal").remove();
                        jQuery('<div/>', {
                        id: 'contenedorPpal',
                        text: 'Texto por defecto!'
                        }).appendTo('.realBody');
                        $("#contenedorPpal").load("controlador/UsuariosController.php");
                    }
              });
        },
        invalidHandler : function (event , validator) {
          console.log(validator);
        }
    });
});

    $("#cambiar_pass").on('click',function(){
        $.post("vista/agregar_datos_password.php",function(data){

            $("#vista_pass").replaceWith(data);
        });
    });

</script>

