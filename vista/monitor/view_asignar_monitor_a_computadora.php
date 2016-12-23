<div id="asignar_productos_a_compu" id_cpu="{id_cpu}">
    {Monitores}
</div>

<script type="text/javascript">
    $("#agregar_productos_a_compu").on('click',"#asignar_monitor",function(){

        var id_monitor = $(this).attr("id_monitor");
        var id_cpu = $("#tab_memorias_y_discos").attr("id_cpu");	    	
        $.post( "controlador/MonitoresController.php",
        {
            action : "asignar",
            id_computadora: id_cpu,
            id_monitor: id_monitor
        }, function(data){
            console.log("Se asigno el monitor con id:"+id_monitor+" al cpu con id:"+id_cpu);
            $("#boton_terminar").trigger("click");
            alert("Los datos han sido actualizados correctamente");
            $("#link_mis_productos").trigger("click");
        }
        );
    });
</script>