<?php
  include("clase_todo.php");
  include("controller.php");
  $todo->install();
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>PHP Todo-list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" />
  </head>
  <body>
  <div class="col-md-5 p-5 rounded bg-white mx-auto mt-5">
    <form action="" class="input-group-append" method="post" autocomplete="off">
      <div class="input-group mb-3">
      <input required type="text" value="<?= isset($_GET['action']) && $_GET['action'] == 'edit' ? $_GET['todo'] : ''; ?>" name="task" class="form-control" name id="task" placeholder="Escribir tarea...">
        <div class="input-group-append">
        <input name="<?= isset($_GET['action']) && $_GET['action'] == 'edit' ? 'updateLast' : 'addNew'; ?>" type="submit" value="<?php echo isset($_GET['action']) && $_GET['action'] == 'edit' ? 'Editar' : 'Agregar'; ?>" class="btn btn-primary" />
        <input type="hidden" type="submit" value="<?= isset($_GET['action']) && $_GET['action'] == 'edit' ? $_GET['id'] : ''; ?>" name="task_id"/>
        </div>
      </div>
    </form>
    <center><h3 class="mt-4">To Do</h3></center>
    <?php $todo->show_todo(); ?>
    <br/>
    <center><h3>Listo</h3></center>
    <?php $todo->show_todo(1); ?>
  </div>
  </body>
  <footer>
    <center style="color: white; margin-top: 50px;">
    <h3>
      <b> Integrantes: </b>
      <br>Giovanni Buglione
      <br>Eric Montilla
      <br>Feliciano Jimenez
      <br><br>
      <b> Proyecto N°1 </b>
      <br>To Do List con PHP
    </h3>
    </center>
  </footer>
</html>