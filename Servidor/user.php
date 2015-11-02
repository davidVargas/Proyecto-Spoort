<?php
  $valido = array(
      'mensaje' => "correcto"
  );
  
  $error = array(
      'mensaje' => "incorrecto"
  );
  

$user=json_decode(file_get_contents('php://input'));
if($user->usuario=='hola' && $user->password=='hola'){
    echo json_encode($valido);
}
else{
  echo json_encode($error);
}

?>