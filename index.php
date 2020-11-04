
<?php

  include('config.php');

  if (isset($_SESSION['user_id'])) {
    $query = "
     SELECT * FROM tasks
     WHERE user_id = '".$_SESSION['user_id']."'
     ORDER BY task_id DESC
    ";
  } else {
    header('Location: login.php');
  }

  $statement = $connect->prepare($query);

  $statement->execute();

  $result = $statement->fetchAll();

?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo ($_SESSION['username']) ?>'s To-Do List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>


  <div class="wrapper">

    <a class='logout-button' href="logout.php">Log out</a>

    <div class='inner-cont'>


      <h1 class="title--alt"><?php echo ($_SESSION['username']) ?>'s todo list</h1>

      <form method="post" id="to_do_form" class="form">
        <span id="message"></span>
        <div class="form">
          <input type="text" name="task_name" id="task_name" class="input--big" autocomplete="off" placeholder="Write a task..." />
          <div class="input-btn">
            <button type="submit" name="submit" id="submit" class="input-button">+</button>
          </div>
        </div>
      </form>

      <div class="list-group">
        <?php
          foreach($result as $row)
          {
            $classCompleted = '';
            if($row["task_status"] == 1) {
              $classCompleted = ' task-completed';
            }


            echo
            '<a href=""
              class="list-group-item'.$classCompleted.'"
              id="list-group-item-'.$row["task_id"].'"
              data-id="'.$row["task_id"].'">'.$row["task_details"].'
              <span class="badge" data-id="'.$row["task_id"].'">X</span>
            </a>';
          }
        ?>
      </div>


    </div>
  </div>


  </body>
</html>

<script>

$(document).ready(function(){

  $(document).on('submit', '#to_do_form', function(event){
    event.preventDefault();

    if($('#task_name').val() == '')
    {
      $('#message').html('<div class="alert alert-danger">Enter Task Details</div>');
      return false;
    } else {
      $('#submit').attr('disabled', 'disabled');
      $.ajax({
        url:"add_task.php",
        method:"POST",
        data:$(this).serialize(),
        success:function(data)
        {
          $('#submit').attr('disabled', false);
          $('#to_do_form')[0].reset();
          $('.list-group').prepend(data);
        }
      })
    }
  });

  $(document).on('click', '.list-group-item', function(e){
    e.preventDefault();

    var task_id = $(this).data('id');
    let taskNewStatus = $(this).hasClass('task-completed') ? 0 : 1;

      $.ajax({
        url:"update_task.php",
        method:"POST",
        data:{task_id:task_id, task_status: taskNewStatus},
        success:function(data)
        {
          if (taskNewStatus == 1) {
            $('#list-group-item-'+task_id).addClass('task-completed');
          } else {
            $('#list-group-item-'+task_id).removeClass('task-completed');
          }
        }
      })
  });

  $(document).on('click', '.badge', function(){
    var task_id = $(this).data('id');
    $.ajax({
      url:"delete_task.php",
      method:"POST",
      data:{task_id:task_id},
      success:function(data)
      {
        $('#list-group-item-'+task_id).fadeOut('slow');
      }
    })
  });

});
</script>
