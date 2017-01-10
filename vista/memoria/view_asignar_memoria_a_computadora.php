<div id="agregar_productos_a_compu" id_cpu="{id_cpu}">
    {Memorias}
</div>

<script type="text/javascript">
$("#agregar_productos_a_compu").on('click',"#asignar_memoria",function(){


	    	console.log("asigno la memoria a un cpu");
        	//Le tengo que asignar cpu y cambiar de sector, lo mismo para disco y monitor
        	//hay que agregarlo a vinculos o ver si ya hay una funcion en creacionController que ya lo haga
        	var id_cpu = $("#tab_memorias_y_discos").attr("id_cpu");
        	var id_memoria = $(this).attr("id_memoria");
        	console.log("Voy a asignar la memoria con id:"+id_memoria+" al id_cpu:"+id_cpu);
            $.post( "controlador/MemoriasController.php",
                    {
                        action : "asignar",
                        id_computadora: id_cpu,
                        id_memoria: id_memoria
                    }, function(data){
                    	console.log("lo guarde");
                    	$("#boton_terminar").trigger("click");
                        alert("Los datos han sido actualizados correctamente");
                        $("#link_mis_productos").trigger("click");
                    }
            );
        });
</script>