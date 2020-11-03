<?php

//delete_task.php

include('config.php');

if($_POST["task_id"])
{
 $data = array(
  ':task_id'  => $_POST['task_id']
 );

 $query = "
 DELETE FROM tasks
 WHERE task_id = :task_id
 ";

 $statement = $connect->prepare($query);

 if($statement->execute($data))
 {
  echo 'done';
 }
}



?>
