var usuarios = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: 'lib/usuarios.php'
});

var nombres = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: 'lib/nombres_usuarios.php'
});

usuarios.initialize();
nombres.initialize();

$('#multiple-datasets .typeahead').typeahead({
  highlight: true
},
{
  name: 'nba-teams',
  displayKey: 'team',
  source: usuarios.ttAdapter(),
  templates: {
    header: '<h3 class="league-name">NBA Teams</h3>'
  }
},
{
  name: 'nhl-teams',
  displayKey: 'team',
  source: nombres.ttAdapter(),
  templates: {
    header: '<h3 class="league-name">NHL Teams</h3>'
  }
});