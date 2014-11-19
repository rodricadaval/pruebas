<?php require_once "../ini.php";

$usuarios = new Usuarios();
$vector = $usuarios->dameUsuarios();
$array = array();

foreach ($vector as $key => $value) {
	array_push($array, $value['usuario']);
}

echo json_encode($array);

/*

$inst_table = BDD::getInstance()->query("select *, '<a href=\"#\" class=\"modificar_monitor\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted green small edit icon\"></i></a> <a href=\"#\" class=\"eliminar_monitor\"id_monitor=\"' || id_monitor || '\"><i class=\"circular inverted red small trash icon\"></i></a>' as m from system." . Monitores::claseMinus());

$todo = $inst_table->_fetchAll();
$total = $inst_table->get_count();

for ($i = 0; $i < $total; $i++) {
echo "</br>";
echo "POSICION";
echo "</br>";
echo "</br>" . $i . "</br>";

$data[$i] = $todo[$i];
echo "</br>";
echo "FILA:";
echo "</br>";
var_dump($data[$i]);
echo "</br>";

foreach ($data[$i] as $campo => $valor) {

if ($campo == "id_monitor_desc") {
$arrayAsoc_desc = Monitor_desc::dameDatos($valor);
echo "</br>";
echo "</br>";
echo "VALORES A ASOCIAR";
echo "</br>";
echo json_encode($arrayAsoc_desc);
echo "</br>";
echo "</br>";
foreach ($arrayAsoc_desc as $camp => $value) {
$data[$i][$camp] = $value;
}
echo "</br>";
echo "COMO QUEDA LA FILA";
echo "</br>";
echo json_encode($data[$i]);
echo "</br>";
echo "</br>";
}

if ($campo == "id_vinculo") {
$arrayAsoc_vinculo = Vinculos::dameDatos($valor);
echo "</br>";
echo "</br>";
echo "VALORES A ASOCIAR";
echo "</br>";
echo json_encode($arrayAsoc_vinculo);
echo "</br>";
echo "</br>";
foreach ($arrayAsoc_vinculo as $camp => $value) {
$data[$i][$camp] = $value;
}
echo "</br>";
echo "COMO QUEDA LA FILA";
echo "</br>";
echo json_encode($data[$i]);
echo "</br>";
echo "</br>";
}
}

echo "CODEADO";
echo "</br>";
echo json_encode($data);
echo "</br>";
echo "</br>";
}
 */

/* if ($campo == "id_monitor_desc") {
$arrayAsoc_desc = Monitor_desc::dameDatos($valor);
foreach ($arrayAsoc_desc as $campo => $value) {
$data[$i][$campo] = $value;
}
} elseif ($campo == "id_vinculo") {
$arrayAsoc_vinculo = Vinculos::dameDatos($valor);
foreach ($arrayAsoc_vinculo as $campo => $value) {
$data[$i][$campo] = $value;
}
} else {
$data[$i][$campo] = $valor;
}
 */
?>
