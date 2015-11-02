<?php

// Funciones de la clase conexion_spoort

// Funcion: setea la variable global $_SESSION con el id del usuario global para controlar
// la sesion mientras este conectado
->verificar_login($username, $password)

// Funcion: Obtener la informacion general del equipo del cliente
	// Parametros: id de usuario
	// Retorna: id_equipo, nombre, descripcion, direccion, horario,deporte, provincia
->getInformacionEquipo($id_usuario)


// Funcion: Cambia la informacion general del perfil del cliente
// Parametros: $id_usuario, $var_nombre, $var_descripcion,
//			   $var_direccion,$var_id_provincia,$var_id_deporte, $var_horario,
//			   $var_genero
->editarInformacionPerfil($id_usuario, $var_nombre, $var_descripcion,
						$var_direccion,$var_id_provincia,$var_id_deporte, $var_horario,
						$var_genero)


// Funcion: Crea un contacto
// Parametros: $var_email ,$var_telefono, $var_nombre_usuario,	$var_apellido_usuario
->crearContacto($var_email, $var_telefono, $var_nombre_usuario,	$var_apellido_usuario)

// Funcion: Retorna la informacion de un contacto
// Retorna: id_contacto, email, telefono, nombre_usuario, apellido_usuario
->obtener_infoContacto($var_email)

// Funcion: Crea un usuario
// Parametros: email, contra , id_cliente
crearUsuario($var_email,$var_contra ,$var_id_cliente)

// Funcion: 
// Parametros: id_contacto, direccion, id_provincia, id_deporte, horario, genero
->crearEquipo($var_nombre_equipo, $var_descripcion, $var_id_contacto, $var_direccion,
		 $var_provincia, $var_deporte, $var_horario_final)

// Funcion: Buscar equipos por nombre
// Parametros: el nombre del equipo
->buscador($string_palabras)

// Funcion: Buscar equipos por descripcion
// Parametros: una palabra que pueda estae en una descripcion de algun equipo
->buscarDescripcion($string_palabras)

?>