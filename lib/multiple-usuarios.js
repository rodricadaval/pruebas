console.log('Entro a typeahead');


  var usuariosObj = Object();
  var users = new Bloodhound({
                datumTokenizer: function (d) {
                    return Bloodhound.tokenizers.whitespace(d.value,d.id);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: 'lib/usuarios.php?search_term=%QUERY',
                    filter: function (users) {
                      usuariosObj = $.map(users, function (user) {
                          return {
                              value: user.nombre_apellido + '  ' + user.area,
                              id: user.id_usuario
                            };
                        });
                      return $.map(users, function (user) {
                          return {
                              value: user.nombre_apellido + '  ' + user.area,
                              id: user.id_usuario
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
                source: users.ttAdapter(),
                templates: {
                    header: '<h3 class="league-name">Nombre | Area</h3>'
                }
            });



/*
        $('.typeahead').typeahead({
            source : function (query , process) {
              $.ajax({
                type : 'get' ,
                data : 'search_term=' + query ,
                url  : 'lib/usuarios.php' ,
                dataType : 'json' ,
                success : function (data) {
                  console.log(data);
                  process (data);
                }
              });
            } ,
            minLength : 3
      });
*/