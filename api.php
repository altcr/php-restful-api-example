<?php

  include("../Helpers/functions.php");
  include("../Helpers/connection.php");

  SetHeader($jsonArray["status"]);

  $jsonValues=array();
  $jsonArray=array();

  $requestMethod = $_SERVER["REQUEST_METHOD"];

  if($requestMethod === "GET"){
    $query = $db->from('tableName')->all();
    if(count($query) > 0){
      $jsonArray = transactions("false", 200, "", $query);
    }
    else{
      $jsonArray = transactions("true", 404, "Not Found.");
    }
  }
  else if($requestMethod === "POST"){
    $jsonValues = json_decode(file_get_contents("php://input"), true);

    if(empty($jsonArray)){
      $query = $db->insert('tableName')->set(array('code' => $jsonValues["code"]));
      if($query){
        $jsonArray = transactions("false", 200);
      }
      else {
        $jsonArray = transactions("true", 504, "Unknown Error.");
      }
    }
    else{
      $jsonArray = transactions("true", 404);
    }

  }
  elseif($requestMethod === "PUT"){
    $jsonValues = json_decode(file_get_contents("php://input"), true);
    $jsonArray = transactions("false", 200, "", "PUT işlemi. ID : ".$jsonValues["data"]["id"]);
  }
  elseif($requestMethod === "DELETE"){
    $jsonValues = json_decode(file_get_contents("php://input"), true);
    $row = $db->from("tableName")->where(id, $jsonValues["id"])->first();
    if(count($row) > 0){
      $query = $db->delete('tableName')->where('id', $jsonValues["id"])->done();
      if($query){
        $jsonArray = transactions("false", 200);
      }
      else{
        $jsonArray = transactions("true", 400);
      }
    }
    else{
      $jsonArray = transactions("true", 404, "ID Not Found.");
    }
  }
  else{
    $jsonArray = transactions("true", 403);
  }


echo json_encode($jsonArray, JSON_FORCE_OBJECT);
