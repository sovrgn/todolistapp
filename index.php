
<?php

  include('config.php');

  if (isset($_SESSION['user_id'])) {
    $query = "
     SELECT * FROM tasks
     WHERE user_id = '".$_SESSION['user_id']."'
     ORDER BY task_id DESC
    ";
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


  <?php if (!isset($SESSION['username'])) { ?>


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
            $style = '';
            if($row["task_status"] == 1) {
              $style = 'text-decoration: line-through';
            } elseif ($row["task_status"] == 0) {
              $style = 'text-decoration: none';
            }
            echo
            '<a href=""
              style="'.$style.'"
              class="list-group-item"
              id="list-group-item-'.$row["task_id"].'"
              data-id="'.$row["task_id"].'">'.$row["task_details"].'
              <span class="badge" data-id="'.$row["task_id"].'">X</span>
            </a>';
          }
        ?>
      </div>


    </div>
  </div>

  <?php } else { header('Location: login.php');} ?>
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

  $(document).on('click', '.list-group-item', function(){
    var task_id = $(this).data('id');

      $.ajax({
        url:"update_task.php",
        method:"POST",
        data:{task_id:task_id},
        success:function(data)
        {
         $('#list-group-item-'+task_id).css('text-decoration', 'line-through');
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
