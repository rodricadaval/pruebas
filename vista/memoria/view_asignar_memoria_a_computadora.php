<div id="agregar_productos_a_compu">
<legend>Memorias disponibles para esta computadora</legend>
<div id="asignar_productos">

</div>
</div>

<script type="text/javascript">

    console.log("Estoy en el script de asignar");  

    $("#asignar_productos").replaceWith($_POST['Memorias']);  
/*
    $.post('controlador/ProductosController.php',
            {
                value_marca : $('#form_agregar_memoria_a_compu #select_marcas_memorias option:selected').val(),
                value_tipo : $('#form_agregar_memoria_a_compu #select_tipos_memorias option:selected').val(),
                tipo : "sel_velocidades",
                action : "agregar_memoria"

            }, function(data) {
            $("#form_agregar_memoria_a_compu #select_velocidades").replaceWith(data);
            });


    $("#asignar_productos").on('change',function(){

        console.log("Evento de seleccion de tipos");

        $.post('controlador/ProductosController.php',
            {
                value_marca : $('#form_agregar_memoria_a_compu #select_marcas_memorias option:selected').val(),
                value_tipo : $('#form_agregar_memoria_a_compu #select_tipos_memorias option:selected').val(),
                tipo : "sel_velocidades",
                action : "agregar_memoria"

            }, function(data) {
            $("#form_agregar_memoria_a_compu #select_velocidades").replaceWith(data);
            });
    });*/

</script>