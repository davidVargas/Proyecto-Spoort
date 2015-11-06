<?php
include("conexion_spoort.php");
$conexion_spoort = new Conexion();

$conexion_spoort->Conectar();

$id_usuario = 2;
$email_contacto = "dvq_07@hotmail.com";

$var_nombre = 'kkk';
$var_descripcion = 'drdrdr';
$var_direccion = 'ffff';
$var_id_provincia = '';
$var_id_deporte = ''; 
$var_horario = '';
$var_genero = ''; // Masculino, Femenino o Mixto : asi va a estar guardado en la base de datos

//$pru = $conexion_spoort->buscador2();

echo "si lo llama...";

//$prueba = $conexion_spoort->getInformacionEquipo($id_usuario);

//$prueba = $conexion_spoort->crearContacto($email_contacto);

//$prueba = $conexion_spoort->obtenerIdContacto($email_contacto);
$palabras_prueba = "ddddddd";
$palabras_prueba2 = "Equipo 1";
//$pruebas = $conexion_spoort->buscarDescripcion($palabras_prueba);
//$pruebas = $conexion_spoort->buscador($palabras_prueba2);

//$pruebas = $conexion_spoort->editarInformacionPerfil($id_usuario, $var_nombre, $var_descripcion,
//						$var_direccion,$var_id_provincia,$var_id_deporte, $var_horario,
//						$var_genero);

//$nombre = "img1111";
//$url = "E:\MAMP\htdocs\proyecto2\Servidor\Fotos\dvq\img1.jpg";
//$ext = "png";
//$id_equipo = 2;

//$pruebas = $conexion_spoort->guardarFotoPerfil($url,$nombre,$ext, $id_equipo);

$pruebas = $conexion_spoort->crearCarpeta("prueba_crearCarpeta2");

?>