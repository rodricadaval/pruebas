console.log('Entro a typeahead');
/*
  var users = new Bloodhound({
                datumTokenizer: function (d) {
                    return Bloodhound.tokenizers.whitespace(d.value,d.id);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: 'lib/usuarios.php?search_term=%QUERY',
                    filter: function (users) {
                      
                      return $.map(users, function (user) {
                        return {
                                  value: user
                           };
                      });
                    }
                }
            });
            // initialize the bloodhound suggestion engine
            users.initialize();
            // instantiate the typeahead UI
            $('.typeahead').typeahead(null, {
                displayKey: 'value',
                minLength : 2,
                source: users.ttAdapter(),
                templates: {
                    header: '<h3 class="titulo">Nombre | Area</h3>'
                }
            });

            console.log('Salgo del typeahead');
*/
        $('.typeahead').typeahead({
            source : function (query , process) {
              $.ajax({
                type : 'get' ,
                data : 'search_term=' + query ,
                url  : 'lib/usuarios.php' ,
                dataType : 'json' ,
                success : function (data) {
                     process (data);
                }
              });
            },
            minLength : 3
      });

/*
if(user.id_usuario == 1){
                            return {
                                  value: user.nombre_apellido,
                                  id: user.id_usuario
                            };
                        } 
                        else{
                          return {
                                  value: user.nombre_apellido + '  ' + user.area,
                                  id: user.id_usuario
                          };
                        }
*/

