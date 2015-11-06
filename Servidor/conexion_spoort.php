<?php  


class Conexion
{
    // Atributos
    private $nombreServidor = "localhost"; // NombreServidor\NombreInstancia
    private $user = "root";
    private $password = "root";
    private $db = "db-spoort";
    public $conn = null;
    //private $conn = null;
    //public $conn = sqlsrv_connect( $serverName, $connectionInfo);
	
    // Constructor
    public function Conexion(){}
    // Set y get
	
    // Metodos de CONEXION
	
    // Metodo para conectarse  a la base de datos en SQL Server 2012
    public function Conectar()  //$nombre_Servidor,$info_Conexion
    {  
            $this->conn = mysqli_connect( $this->nombreServidor, $this->user,$this->password, $this->db);
            
            if( $this->conn != NULL) 
            {
                     //echo "Conexion establecida!!!.<br />";
                     //return 1;
            }else
            {
                     //echo "Conexion no se pudo establecer.<br />";
                     die( "Conexion fallida: " . $this->conn->connect_error);
                     //return -1;
            }
		
    }
	
	
    // --------------------------------------------- STORED PROCEDURES ---------------------------------------------
    
    // GET Deportes

    // GET PROVINCIAS


	
	// ----------------------------------- LOGIN
	public function verificar_login($username, $password)
	{
		$this->Conectar();
		$varTemp = null;
		$res = array();

		$json = array(
			'tipo_mensaje' => "" ,
			'parametros' => array()
		);
		
		$sql = "select * from usuario where nombre= '$username'";
		$resultado = mysqli_query($this->conn, $sql);
		
		if (!$resultado) {
            printf("Error: %s\n", mysqli_error($this->conn));
            $json['tipo_mensaje'] = "incorrecto";
            echo json_encode($json);
            exit();
        }
		
		$usuario = array(); // Informacion del usuario en este array
		$contador = 0;
		
		$fila = mysqli_fetch_array($resultado);
		
		if($fila{'fk_tipoUsuario'} == '2') // Osea si es el ciente
		{
			if($fila{'contra'} == $password)
			{
				echo "Login exitoso" ;
				session_start();
				$id_usuario = $fila{'id_usuario'};
				//$_SESSION['sid']= session_id();
				$_SESSION['sid']= $fila{'id_usuario'};
				
				$res[0] = $fila{'nombre'};
				$res[1] = $fila{'id_usuario'};
				$json['tipo_mensaje'] = "correcto";
				array_push($json['parametros'], $res);
            	echo json_encode($json);
				//header("location:perfil_equipo_cliente.php");
				
				// return $res;
			}else
			{
				//echo "Error en contraseña..." ;
				$json['tipo_mensaje'] = "incorrecto";
            	echo json_encode($json);
				// return -1;
			}
		}else
		{
			if($fila{'contra'} == $password) // Osea si es el Administrador
			{
				echo "Login exitoso" ;
				
				session_start();
				// $_SESSION['sid'] = session_id();
				$_SESSION['sid'] = $fila{'id_usuario'};

				$res[0] = $fila{'nombre'};
				$res[1] = $fila{'id_usuario'};
				$json['tipo_mensaje'] = "correcto";
				array_push($json['parametros'], $res);
            	echo json_encode($json);

				//header("location:inicio_admin.php");
				
				//return 2;
			}else
			{
				$json['tipo_mensaje'] = "incorrecto";
            	echo json_encode($json);
				// echo "Error en contraseña..." ;
				
				//return -2; // Error de login administrador
			}
			
		}
		
		
	}
	
	// ----------------------------------- FIN LOGIN
	
    
	// PERFIL CLIENTE
	// Obtener la informacion general del equipo del cliente
	// Retorna: id_equipo, nombre, descripcion, direccion, id_provincia,id_deportes, horario
	public function getInformacionEquipo($id_usuario)
	{
		
		$this->Conectar();
		
		$json = array(
		  'tipo_mensaje' => "" ,
		  'parametros' => array()
		);


		$sql = "select Eq.id_equipo, Eq.nombre, Eq.descripcion , Eq.direccion , Eq.horario, Eq.id_provincia, Eq.id_deportes
		from equipo Eq
		where Eq.id_usuario = '$id_usuario'";
		
		$resultado = mysqli_query($this->conn, $sql);
		
		if (!$resultado) {
            printf("Error: %s\n", mysqli_error($this->conn));
            $json['tipo_mensaje'] = "incorrecto";
            echo json_encode($json);
            exit();
        }
		
		$fila = mysqli_fetch_array($resultado);
		
		$resultado_json = array('id_equipo' => $fila{'id_equipo'}, 'nombre' =>$fila{'nombre'}
		, 'descripcion' =>$fila{'descripcion'}, 'direccion' =>$fila['direccion']
		, 'horario' =>$fila{'horario'}
		, 'deporte' =>'', 'provincia' =>'' );

		$idd_provincia = $fila['id_provincia'];
		$idd_deporte = $fila['id_deportes'];

		// Provincia y equipo
		$sql2 = "select P.nombre
		from provincia P
		where P.id_provincia = '$idd_provincia'";
		$sql3 = "select D.nombre
		from deportes D
		where D.id_deportes = '$idd_deporte'";
		
		$resultado2 = mysqli_query($this->conn, $sql2);
		$resultado3 = mysqli_query($this->conn, $sql3);
		if (!$resultado2 or !$resultado3) {
            printf("Error: %s\n", mysqli_error($this->conn));
            $json['tipo_mensaje'] = "incorrecto";
            echo json_encode($json);
            exit();
        }
		
		$fila2 = mysqli_fetch_array($resultado2);
		$fila3 = mysqli_fetch_array($resultado3);

		$resultado_json['provincia'] = $fila2{'nombre'};
		$resultado_json['deporte'] = $fila3{'nombre'};
		echo "-----------------> ".$resultado_json['provincia'] ."++++++++++ ".$resultado_json['deporte'];


		mysqli_close($this->conn);
		
		array_push($json['parametros'], $resultado_json);
		echo json_encode($json);
		//return $fila;
		
	}
	
	// Cambia la informacion general del perfil del cliente
	public function editarInformacionPerfil($id_usuario, $var_nombre, $var_descripcion,
						$var_direccion,$var_id_provincia,$var_id_deporte, $var_horario,
						$var_genero)
    {
        $this->Conectar();
		
        $json = array(
		  'tipo_mensaje' => "" ,
		  'parametros' => array()
		);
        //$sql = "Update equipo E,lugarentrenamiento LE  
		//	set E.nombre = '$var_nombre' , 
		//		LE.fk_provincia = '1'
		//	where "; // San jose, hacer despues un selesctbox con las provincias
		
        
		$sql = "Update equipo E
		set E.nombre = '$var_nombre', E.descripcion = '$var_descripcion', E.direccion = '$var_direccion', E.id_provincia = '$var_id_provincia' ,
						E.id_deporte = '$var_id_deporte' , E.horario = '$var_horario', E.genero = '$var_genero'
		where E.id_usuario = '$id_usuario'";
		
        // Verificamos que se haya insertado la informacion
        if($this->conn->query($sql) == TRUE)
        {
            echo "Usuario actualizado con exito...";
            $json['tipo_mensaje'] = "correcto";
            echo json_encode($json);
			mysqli_close($this->conn);
			//return 1;
        }else
        {
            // echo "Eror: ". $this->conn->error;
            printf("Error: %s\n", mysqli_error($this->conn));
            $json['tipo_mensaje'] = "incorrecto";
            echo json_encode($json);
			mysqli_close($this->conn);
			//return -1;
        }

        
        //mysqli_close($this->conn);
    }
		
	// Fin PERFIL CLIENTE
	
	
	//   REGISTRAR EQUIPO *************
		// Se van a crear:
			//1. crearContacto($var_email,$var_cedula ,$var_telefono);
			//2. obtenerIdContacto($var_email);
			//3. crearUsuario($var_email,$var_contra ,$var_id_cliente,2);
			//4. crearEquipo($var_nombre_equipo, $var_descripcion, 
				// $var_id_contacto, $var_direccion, $var_provincia, $var_deporte, 1 , $var_horario_final);
	
	// CONTACTO
	//1. $conexion_spoort->crearContacto($var_email ,$var_telefono, $var_nombre_usuario,
	//		 $var_apellido_usuario);
	public function crearContacto($var_email, $var_telefono, $var_nombre_usuario, $var_apellido_usuario)
	{
		//$var_telefono = "-- --";
		//$var_ced = "-- --";
		
		$json = array(
			'tipo_mensaje' => '',
			'parametros' => array()
		);

        $this->Conectar();
        $sql = "Insert into contacto(email, telefono, nombre_usuario, apellido_usuario)
                values ('$var_email','$var_telefono', '$var_nombre_usuario', '$var_apellido_usuario')";
        
        // Verificamos que se haya insertado la informacion
        if($this->conn->query($sql) == TRUE)
        {
            //echo "Contacto creado con exito...";
            $json['tipo_mensaje'] = "correcto";
            echo json_encode($json);

        }else
        {
            // echo "Eror: ". $this->conn->error;
            printf("Error: %s\n", mysqli_error($this->conn));
            $json['tipo_mensaje'] = "incorrecto";
            echo json_encode($json);
        }
		
        mysqli_close($this->conn);
		
	}
	
	//2. obtenerIdContacto($var_email);
	// Retorna: id_contacto, email, telefono, nombre_usuario, apellido_usuario
	public function obtener_infoContacto($var_email)
	{
		$this->Conectar();
        $varTemp = null;
		
        $json = array(
        	'tipo_mensaje' => '',
        	'parametros' => array()
        );

        $sql = "Select id_contacto, email, telefono, nombre_usuario, apellido_usuario from contacto Con
		where Con.email = '$var_email'";
        $resultado = mysqli_query($this->conn, $sql);
        
        if (!$resultado)
		{
            printf("Error: %s\n", mysqli_error($this->conn));
            $json['tipo_mensaje'] = "incorrecto";
            echo json_decode($json);
            exit();
        }
		
		// $datos_usuario = array();
		$fila = mysqli_fetch_array($resultado);
		$datos_usuario = array('id_contacto' => $fila{'id_contacto'}, 'email' => $fila{'email'},'telefono' => $fila{'telefono'}
			,'nombre' => $fila{'nombre_usuario'},'apellido' => $fila{'apellido_usuario'});
            //echo "Id de contacto: " . $fila{'id_contacto'} . " Email: " . $fila{'email'} . "<br />";
			//$datos_usuario[0] = $fila{'id_contacto'};
			//$datos_usuario[1] = $fila{'email'};
        echo "<br/>";
        //echo "Datos de usuario...."."<br/>";
		$json['tipo_mensaje'] = "correcto";
		array_push($json['parametros'],$datos_usuario);
        echo json_encode($json);
        
        // Cerrar la conexion con la base
        mysqli_close( $this->conn );
		
		//return $datos_usuario;
	}
	
	//3. crearUsuario($var_email,$var_contra ,$var_id_cliente,2);
	// Paramatros: email,contra ,id_cliente
	public function crearUsuario($var_email,$var_contra ,$var_id_cliente)
	{

		$var_fk_tipo_usuario = 2;
		// echo "Se quiere insertar: ".$var_email." ". $var_contra. " ". $var_id_cliente;
		
		$this->Conectar();
        $sql = "Insert into usuario(nombre, contra, fk_contacto, fk_tipoUsuario)
                values ('$var_email','$var_contra','$var_id_cliente','2')";
        
        // Verificamos que se haya insertado la informacion
        if($this->conn->query($sql) == TRUE)
        {
            echo "Usuario creado con exito...";

            // Hay que hacer la carpeta del usuario para guardar las fotos
            if(is_dir("directory"))
			{
				echo "directory exists!";
			}else
			{
				mkdir("/Servidor/fotos/".$var_email);
				mkdir("/fotos/".$var_email);
				echo "Carpeta creada...";

			} // Fin crear carpeta usuario

        }else
        {
            // echo "Error: ". $this->conn->error;
            printf("Error: %s", mysqli_error($this->conn));
        }
		
        mysqli_close($this->conn);
	}
	
	//4. nombre_equipo, descripcion, 
				// id_contacto, direccion, id_provincia, id_deporte, horario, genero
	public function crearEquipo($var_nombre_equipo, $var_descripcion, 
				 $var_id_contacto, $var_direccion, $var_id_provincia, $var_id_deporte, $var_horario, $var_genero)
	{
		// campos de la base:
		// nombre, descripcion, id_usuario, direccion, id_provincia, id_deportes,  horario
		
		
		$this->Conectar();
        $sql = "Insert into equipo(nombre, descripcion, id_usuario, direccion, id_provincia, id_deportes, horario )
                values ('$var_nombre_equipo','$var_descripcion','$var_id_contacto','$var_direccion', 
                			'$var_id_provincia', '$var_id_deporte','$var_horario', '$var_genero')";
        
        // Verificamos que se haya insertado la informacion
        if($this->conn->query($sql) == TRUE)
        {
            echo "Equipo creado con exito...";
        }else
        {
            // echo "Eror: ". $this->conn->error;
            printf("Error: %s\n", mysqli_error($this->conn));
        }
		
        mysqli_close($this->conn);
		
	}
	
	
	// Fin REGISTRAR EQUIPO *************
    
	
	
	
	
	// +++++++++++++ BUSCADOR +++++++++++++++++++
	
	
	public function buscador($string_palabras)
	{
		$this->Conectar();
		
		$nombre_equipo = $string_palabras;

		// Variables
		$lista_equipos = array();
		 $json = array(
        	'tipo_mensaje' => '',
        	'parametros' => array()
        );
		
		// 1 - Separar palabas por espacios en blanco
		
		
		// 2 - hacer busqueda en base segun datos en el "$string_palabras" y los tag de cada equipo
		
		$sql = "Select * from equipo E
		where E.nombre like '%$nombre_equipo%'";
        $resultado = mysqli_query($this->conn, $sql);
        
        if (!$resultado)
		{
            printf("Error: %s\n", mysqli_error($this->conn));
            exit();
        }

		
		$equipos = array();
		$contador = 0;
		while( $fila = mysqli_fetch_array($resultado) ) {
			  //echo $row['Nombre']."<br />";
			$datos_usuario = array('id_equipo' => $fila{'id_equipo'}, 'nombre' => $fila{'nombre'}
				,'descripcion' => $fila{'descripcion'},'id_usuario' => $fila{'id_usuario'}
				,'direccion' => $fila{'direccion'},'id_provincia' => $fila{'id_provincia'}
				,'id_deportes' => $fila{'id_deportes'} ,'horario' => $fila{'horario'});
			 /*
			  echo "ID Equipo: ". $fila{'id_equipo'}." Nombre: " . $fila{'nombre'} . " Descripcion: " . $fila{'descripcion'}. " Direccion: " . $fila{'direccion'} . "<br />";
			  $equipos[$contador][0] = $fila['nombre'];
			  $equipos[$contador][1] = $fila['descripcion'];
			  $equipos[$contador][2] = $fila['direccion'];
			  $contador ++;
			  */
			  array_push($json['parametros'], $datos_usuario);
		}



		// echo "Lo que lleva php como resultado: ".$equipos[0][0]. "2- ". $equipos[0][1];
		//$lar = count($equipos);
		//echo "<br/>" . "Se encontraron: " . $lar . " equipos";

		// 3 - Hacer lista con los equipos encontrados y hacer el return de esa lista
		
		
		mysqli_close($this->conn);

		echo json_encode($json);
		 //return $equipos;
	}
	
	
	// buscar por descripcion
	public function buscarDescripcion($string_palabras)
	{
		$this->Conectar();
		
		$palabras = $string_palabras;

		// Variables
		$lista_equipos = array();
		
		
		// 1 - Separar palabas por espacios en blanco
		
		
		// 2 - hacer busqueda en base segun datos en el "$string_palabras" y los tag de cada equipo
		
		$sql = "Select * from equipo E
		where E.descripcion like '%$palabras%'";
        $resultado = mysqli_query($this->conn, $sql);
        
        if (!$resultado)
		{
            printf("Error: %s\n", mysqli_error($this->conn));
            exit();
        }

		
		$equipos = array();
		$contador = 0;
		while( $fila = mysqli_fetch_array($resultado) ){
			  //echo $row['Nombre']."<br />";


			$equipos['parametros'][] = $fila;
			 /*
			  echo "ID Equipo: ". $fila{'id_equipo'}." Nombre: " . $fila{'nombre'} . " Descripcion: " . $fila{'descripcion'}. " Direccion: " . $fila{'direccion'} . "<br />";
			  $equipos[$contador][0] = $fila['nombre'];
			  $equipos[$contador][1] = $fila['descripcion'];
			  $equipos[$contador][2] = $fila['direccion'];
			  $contador ++;
			  */
		}


		// echo "Lo que lleva php como resultado: ".$equipos[0][0]. "2- ". $equipos[0][1];
		//$lar = count($equipos);
		//echo "<br/>" . "Se encontraron: " . $lar . " equipos";

		// 3 - Hacer lista con los equipos encontrados y hacer el return de esa lista
		
		
		mysqli_close($this->conn);

		echo json_encode($equipos);
		 //return $equipos;
	}

	// +++++++++++++ FIN BUSCADOR +++++++++++++++++++
	

// IMAGENES

public function crearCarpeta($var_email)
{
	if(is_dir("directory"))
	{
		echo "directory exists!";
	}else
	{
		mkdir("fotos/".$var_email);
		echo "Carpeta creada correctamente...";

	} // Fin crear carpeta usuario


}

// Funcion: guarda los urls de las imagenes subidas
// Parametros: URL, nombre, extension
public function guardarFotoPerfil($url,$nombre,$extension, $id_equipo)
{

	$this->Conectar();

	 $json = array(
    	'tipo_mensaje' => '',
    	'parametros' => array()
    );

	$sql = "insert into imagen(url, nombre, extension,id_equipo)
		values('$url','$nombre','$extension','$id_equipo')";

	$resultado = mysqli_query($this->conn, $sql);

	if (!$resultado)
	{
        printf("Error: %s\n", mysqli_error($this->conn));
        $json['tipo_mensaje'] = "incorrecto";
        echo json_decode($json);
        exit();
    }

    $json['tipo_mensaje'] = "correcto";

    mysqli_close($this->conn);

	echo json_encode($json);

}

/*public function guardarFotos($lista_urls,$nombre,$extension)
{

}
*/
//public function obtenerFotos($username){}

//public function buscarFoto(){}


// FIN IMAGENES



}  
		
?>










