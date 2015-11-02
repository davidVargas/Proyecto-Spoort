<?php


   $res1 = array('id'=>0,'nombre'=>"Real madrid",'descripcion'=>"Mejor equipo de españa",'direccion'=>"madrid", 'url'=> 'http://lorempixel.com/300/180/sports');
   $res2 = array('id'=>1,'nombre'=>"Barcelona",'descripcion'=>"segundo equipo de españa",'direccion'=>"barcelona", 'url'=> 'http://lorempixel.com/300/180/sports');
   $res3 = array('id'=>2,'nombre'=>"Saprissa",'descripcion'=>"Mejor equipo de costa rica",'direccion'=>"tibas", 'url'=> 'http://lorempixel.com/300/180/sports');
   
   $stuff = array(
      'tipo_mensaje' => "correcto",
    'parametros' => array()
  );
  
  array_push($stuff['parametros'], $res1);
  array_push($stuff['parametros'], $res2);
  array_push($stuff['parametros'], $res3);

  echo json_encode($stuff);
  
    

   //echo json_encode($res);
?>