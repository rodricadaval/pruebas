<div id="asignar_productos_a_compu" id_cpu="{id_cpu}">
 {Discos}
</div>

<script type="text/javascript">
$("#agregar_productos_a_compu").on('click',"#asignar_disco",function(){

        	var id_cpu = $("#tab_memorias_y_discos").attr("id_cpu");
        	var id_disco = $(this).attr("id_disco");
        	console.log("Voy a asignar el disco con id:"+id_disco+" al id_cpu:"+id_cpu);
            $.post( "controlador/DiscosController.php",
                    {
                        action : "asignar",
                        id_computadora: id_cpu,
                        id_disco: id_disco
                    }, function(data){
                    	$("#agregar_productos_a_compu").html("Lo asigne");
                        $("#boton_terminar").trigger("click");
                        alert("Los datos han sido actualizados correctamente");
                        $("#link_mis_productos").trigger("click");
                    }
            );
        });
</script>
