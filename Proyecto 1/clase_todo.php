<?php 
class Todo{
  private $db;

  const DATABASE = 'todolist';
  const USERNAME = 'root';
  const PASSWORD = '';

  public $root;

  /**
  * Class constructor
  * @param string $con
  */
  function __construct(){
    $this->db = mysqli_connect("localhost", self::USERNAME, self::PASSWORD, self::DATABASE);

    if(!$this->db ){
      exit;
    } 

    $this->root = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  }

  /**
  * Add new todo
  * @param string $task
  */
  public function install(){
    $query = "CREATE TABLE IF NOT EXISTS `todo` (`id` int(11) NOT NULL AUTO_INCREMENT, `todo` varchar(200) NOT NULL, `date` varchar(200) NOT NULL, `done` int(11) NOT NULL, PRIMARY KEY (`id`))";
    $run = mysqli_query($this->db, $query);
    if($run){
      //echo 'Listo<p><a href="'.$this->root_url().'">Regresar a pantalla principal</a></p>';
    }
  }

  /**
  * Add new todo
  * @param string $task
  */
  public function add_todo($task){
    $date = time();
    $query = "INSERT INTO todo (todo, date, done) VALUES ('$task', '$date', '0')";
    
    $this->run_query($query);
  }

  /**
  * Delete todo
  * @param int $id
  */
  public function delete_todo($id){
    $query = "DELETE FROM todo WHERE todo.id='$id'";
    $this->run_query($query);
  }

  /**
  * Return todo to undone
  * @param int $id
  */
  public function return_todo($id){
    $now = time();

    $data = [ 'done' => 0, 'date' => $now ];
    $where = [ 'id' => $id ];

    $this->update_sql_query($data, $where, $table='todo');
  }

  /**
  * Done todo
  * @param int $id
  */
  public function done_todo($id){
    $now = time();

    $data = [ 'done' => 1, 'date' => $now ];
    $where = [ 'id' => $id ];

    $this->update_sql_query($data, $where, $table='todo');
  }

  /**
  * Update todo
  * @param int $id
  * @param string $task
  */
  public function update_todo($id, $task){
    $task = $_POST['task'];

    $data = [ 'todo' => $task ];
    $where = [ 'id' => $id ];

    $this->update_sql_query($data, $where, $table='todo');
  }

  /**
  * Sql query and run for todo update
  * @param array $data
  * @param array $where
  * @param string $table
  */
  public function update_sql_query($data, $where, $table='todo'){
    $cols = [];
    foreach($data as $key=>$val) {
        $cols[] = $table.".$key = '$val'";
    }

    $wheres = [];
    foreach($where as $key=>$val) {
      $wheres[] = $table.".$key = '$val'";
    }

    $query = "UPDATE $table SET " . implode(', ', $cols) . " WHERE " . implode(', ', $wheres);
 
    $this->run_query($query);
  }

  /**
  * Run sql query and header to home
  * @param string $query
  */
  private function run_query($query){
    mysqli_query($this->db, $query);
    $this->redirect($_SERVER['REQUEST_URI']);
  }

  /**
  * Select todo
  * @param string $done
  */
  private function select_todo($done=0){
    $query = "SELECT * FROM todo WHERE todo.done='$done' ORDER BY `date` ASC";
    $run_select = $this->run_query_return($query);

    if(!$run_select) {
      exit;
    }
    return $run_select;
  }

  /**
  * Show todo
  * @param string $done
  */
  public function show_todo($done=0){
    $todos = $this->select_todo($done);
    
    echo '<table class="table table-striped">';
      echo '<thead>';
        echo '<tr>';
          echo '<th scope="col">#</th>';
          echo '<th scope="col">Tarea</th>';
          echo '<th scope="col">Fecha</th>';
          echo '<th scope="col">Acción</th>';
        echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
          $num = 1;
          while( $row = mysqli_fetch_array($todos) ):
            echo '<tr>';
              echo '<th scope="row">'.$num.'</th>';
              echo '<td>'.$row["todo"].'</td>';
              echo '<td>'.date('m/d/Y', $row["date"]).'</td>';
              echo '<td>';
                $name = ($done==0) ? 'Listo': 'Regresar';
                echo '<a href="?id='.$row["id"].'&action='.$name.'">'.$name.'</a>';
                echo ' &nbsp;<a href="?id='.$row["id"].'&action=edit&todo='.$row["todo"].'" class="text-success">Editar</a>';
                echo ' <a class="text-danger mx-2 d-inline-block" href="?id='.$row["id"].'&action=delete">Eliminar</a>';
              echo '</td>';
            echo '</tr>';
            $num++;
          endwhile;
        echo '</tbody>';
      echo '</table>';
  }
  
  /**
  * Run sql query and return result
  * @param string $query
  */
  private function run_query_return($query){
    return mysqli_query($this->db, $query);
  }

  /**
  * Root url
  * @param string $query
  */
  private function root_url(){
    $protocol = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/',$url);
    $dir = $_SERVER['SERVER_NAME'];
    for ($i = 0; $i < count($parts) - 1; $i++) {
      $dir .= $parts[$i] . "/";
    }
    return $protocol.$dir;
  }

  /**
	 * Redirect to home.
	 * @param string $url optional
	 */
	public function redirect(){
		header('Location: '.$this->root_url());
    exit;
	}
}

$todo = new Todo();
?>