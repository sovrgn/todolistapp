<?php


include('config.php');

if($_POST["task_id"])
{

  $data = array(
  ':task_status'  => 1,
  ':task_id'  => $_POST["task_id"]
  );

  $query = "
  UPDATE tasks
  SET task_status = :task_status
  WHERE task_id = :task_id
  ";

  $statement = $connect->prepare($query);

  if($statement->execute($data))
  {
  echo 'done';
  }
}

?>
