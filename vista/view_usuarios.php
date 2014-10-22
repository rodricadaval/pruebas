<table style="text-align:center" cellpadding="0" cellspacing="0" border="0" class="display" id="dataTable"></table>
<div id="dialogcontent" title="Modificar Usuario">

  <p>All form fields are required.</p>
  
</div>

<div id="modificacion_usuario"> </div>

<script type="text/javascript">

	$(document).ready(function(event){
		var url;
		$.get('logueo/check_priority.php', function(permisos) {
			     
			     if( permisos == 1 || permisos == 3) {
			       		$.ajax({
							url : 'metodos_ajax.php',
							method: 'post',
							data:{ clase: '{TABLA}',
									metodo: 'listarTodos'},
							dataType: 'json',
							success : function(data){
								$("#dataTable").dataTable({
				   			 		"destroy" : true,
				   			 		"bJQueryUI" : true,
									"aaData" : data,
									"aoColumns" :[
										{ "sTitle" : "ID" , "mData" : "id_usuario"},
										{ "sTitle" : "Usuario" , "mData" : "usuario"},
										{ "sTitle" : "Permisos_ID" , "mData" : "permisos"},
										{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
										{ "sTitle" : "Pass" , "mData" : "password"},
										{ "sTitle" : "Area_ID" , "mData" : "area"},
										{ "sTitle" : "Email" , "mData" : "email"},
										{ "sTitle": "Action", "mData" : "m" , "sDefaultContent":
										'<a class="ventana_usuario " href="#">Modificar</a>'}
					  					]
				    			})
							}

						});
			     } else if (permisos == 2) {
			     		$.ajax({
							url : 'metodos_ajax.php',
							method: 'post',
							data:{ clase: '{TABLA}',
									metodo: 'listarTodosGuest'},
							dataType: 'json',
							success : function(data){
								$("#dataTable").dataTable({
				   			 		"destroy" : true,
				   			 		"bJQueryUI" : true,
									"aaData" : data,
									"aoColumns" :[
										{ "sTitle" : "ID" , "mData" : "id_usuario"},
										{ "sTitle" : "Usuario" , "mData" : "usuario"},
										{ "sTitle" : "Permisos_ID" , "mData" : "permisos"},
										{ "sTitle" : "Nombre" , "mData" : "nombre_apellido"},
										{ "sTitle" : "Area_ID" , "mData" : "area"},
										{ "sTitle" : "Email" , "mData" : "email"}
									]
				    			})
							}

						});
			     }
			 });
		
	});

//$('#dataTable tbody tr').find('td:eq(7)').live('click', function () {
	$("#contenedorPpal").on('click' , '.modificar' , function(){

		console.log($(this).attr("id_usuario"));
  		
  		var id_usuario = $(this).attr('id_usuario');
  		var oTable = $('#dataTable').dataTable();
		var nTr = this.parentNode;
		var oData = oTable.fnGetData(nTr);

		$.post( "vista/dialog_content.php", 
			{ 
				TablaPpal : "Usuarios",
				ID : id_usuario,
				select_Areas : "Areas",	//Clase de la cual quiero obtener el select
				select_Permisos : "Permisos", //Clase de la cual quiero sacar el select
				queSos : "usuario" //a quien le voy a generar la vista

			}, function(data){
				$("#dialogcontent").html(data);
				$("#dialogcontent").dialog("open");
			});
});

$( "#dialogcontent" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000,
        width: 1000,
        modal:true
      },
      hide: {
        effect: "explode",
        duration: 200
      }
});
</script>
