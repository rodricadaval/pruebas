<div id="agregar_productos_a_compu" id_cpu="{id_cpu}">
    {Memorias}
</div>

<script type="text/javascript">
$("#agregar_productos_a_compu").on('click',"#asignar_memoria",function(){


	    	console.log("asigno la memoria a un cpu");
        	//Le tengo que asignar cpu y cambiar de sector, lo mismo para disco y monitor
        	//hay que agregarlo a vinculos o ver si ya hay una funcion en creacionController que ya lo haga
            $.post( "controlador/MemoriasController.php",
                    {
                        action : "modificar",
                        asing_cpu: "yes",
                        id_computadora: $("#agregar_productos_a_compu").attr("id_cpu")
                    }, function(data){

                    }
            );
        });
</script>