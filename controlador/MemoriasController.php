<?php
require_once "../ini.php";

if (isset($_POST['action'])) {
    $inst_memoria = new Memorias();
    $inst_vinc    = new Vinculos();

    switch ($_POST['action'])
    {
    case 'modificar':
        unset($_POST['action']);
        $_POST['id_usuario'] = Usuarios::getIdByNombre($_POST['nombre_usuario']);
        if (isset($_POST['asing_usr']) && $_POST['asing_usr'] == "yes") {
            $_POST['id_cpu'] = $_POST['id_computadora'];
            unset($_POST['id_computadora']);
            unset($_POST['asing_usr']);
            echo $inst_vinc->modificarDatos($_POST);
        }
        else if (isset($_POST['asing_cpu']) && $_POST['asing_cpu'] == "yes") {
              $_POST['id_cpu'] = $_POST['id_computadora'];
              unset($_POST['id_computadora']);
              unset($_POST['asing_cpu']);
              echo $inst_vinc->cambiarCpu($_POST);
        }
        else if (isset($_POST['asing_sector']) && $_POST['asing_sector'] == "yes") {
              unset($_POST['asing_sector']);
              $_POST['id_sector'] = $_POST['area'];
              unset($_POST['area']);
              echo $inst_vinc->cambiarSector($_POST);
        }
        else
        {
              $_POST['id_cpu'] = Computadoras::getIdBySerie($_POST['cpu_serie']);
              unset($_POST['cpu_serie']);
              unset($_POST['nombre_usuario']);
              echo $inst_vinc->modificarDatos($_POST);
        }
        break;
    case 'asignar':
        unset($_POST['action']);
        $datosComputadora = Computadoras::getConSectorById($_POST['id_computadora']);    
        $aux = Memorias::dameDatos($_POST['id_memoria']);
        $id_vinculo = $aux['id_vinculo'];
        //die("<pre>". json_encode($id_vinculo,JSON_PRETTY_PRINT) . "</pre>");			
        $datos = array(
        "id_vinculo" => $id_vinculo,//El de la memoria
        "id_usuario" => $datosComputadora['id_usuario'],
        "id_sector" => $datosComputadora['id_sector']);
        $inst_vinc->cambiarUsuarioYSector($datos);
        $otrosDatos = array(
        'id_vinculo' => $id_vinculo,
        'id_cpu' => $_POST['id_computadora']);
        $inst_vinc->cambiarCpu($otrosDatos);
        //echo "Inserto el id_cpu:"+$_POST['id_computadora']+"en el id vinculo:"+$id_vinculo;
        break;

    case 'eliminar':
        unset($_POST['action']);
        echo $inst_memoria->eliminarLogico($_POST);
        break;

    case 'liberar':
        unset($_POST['action']);
        echo $inst_memoria->liberar($_POST['id_memoria']);
        break;

    default:
        // code...
        break;
    }
}
else
{
    $archivos   = array("vista/memoria/view_memorias.php");
    $parametros = array("TABLA" => "Memorias", "");
    echo Disenio::HTML($archivos, $parametros);
}
?>