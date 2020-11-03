<?php

include('config.php');


if($_POST["task_name"])
{
 $data = array(
  ':user_id'  => $_SESSION['user_id'],
  ':task_details' => trim($_POST["task_name"]),
  ':task_status' => 0
 );

 $query = "
 INSERT INTO tasks
 (user_id, task_details, task_status)
 VALUES (:user_id, :task_details, :task_status)
 ";

 $statement = $connect->prepare($query);

 if($statement->execute($data))
 {
  $task_id = $connect->lastInsertId();

  echo '<a href="#" class="list-group-item" id="list-group-item-'.$task_id.'" data-id="'.$task_id.'">'.$_POST["task_name"].' <span class="badge" data-id="'.$task_id.'">X</span></a>';
 }
}


?>
