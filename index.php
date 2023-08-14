<?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $database = "notes";
      $insert = false;
      $update = false;
      $delete = false;
      $conn = mysqli_connect($servername, $username, $password,$database);
      if ($conn === false){
      die("Could not connect: " . mysql_connect_error());
      }
      if(isset($_GET['delete'])){
        $sno = $_GET['delete'];
        $delete = true;
        $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
        $result = mysqli_query($conn, $sql);
      }
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['snoEdit'])){
          $sno = $_POST["snoEdit"];
          $title = $_POST["titleEdit"];
          $description = $_POST["descEdit"];
          $sql = "UPDATE `notes` SET `title` = '$title', `descrp` = '$description' WHERE `notes`.`sno` = $sno";
          $result = mysqli_query($conn, $sql);
          if ($result){
            $update = true;
          }
          else{
           echo "Could not update the note";
          }
        }
        else{
          $title = $_POST['title'];
        $desc = $_POST['desc'];
        $sql = "INSERT INTO `notes`(`title`,`descrp`) values ('$title','$desc')";
        $result = mysqli_query($conn,$sql);
     
      if($result){
        $insert = true;
      }
      else{
           echo "The record has not been inserted successfully due to an error " . mysqli_error($conn);
      }
        }  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" class="css">
    <title>PHP CRUD</title>
</head>
<body>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit This note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/CRUD/index.php" method = "post">
          <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="desc">Notes Description</label>
                <textarea class="form-control"  id="descEdit" name="descEdit" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Note</button>
        </form>
      </div>
    </div>
  </div>
</div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid ">
          <a class="navbar-brand" href="#">phpCRUD</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
              </li>
            </ul>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
      <?php
      if($insert == true){
        echo "<div class='alert alert-success' role='alert'>
        Note saved successfully 
      </div>";
      }
      if($update == true){
        echo "<div class='alert alert-success' role='alert'>
        Note updated successfully 
      </div>";
      }
      if($delete == true){
        echo "<div class='alert alert-danger' role='alert'>
        Note deleted successfully 
      </div>";
      }
      ?>
      <div class="container mt-3">
        <h2>Add a Note</h2>
        <form action="/CRUD/index.php" method = "post">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="desc">Notes Description</label>
                <textarea class="form-control"  id="desc" name="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
      </div>
      <div class="container my-3">
       
        <table class="table " id="myTable">
  <thead>
    <tr>
      <th scope="col">Sno</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  
    <?php
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn,$sql);
        $sno = 0;
        while($row = mysqli_fetch_array($result)){
          $sno = $sno + 1;
          echo "<tr>
          <th scope='row'>" . $sno . "</th>
          <td>". $row['title'] ."</td>
          <td>"  . $row['descrp'] . "</td>
          <td>  <button class='edit btn btn-sm btn-primary' id=" . $row['sno']  . ">Edit</button><button class=' delete btn btn-sm btn-danger mx-1' id=d" . $row['sno']  . ">Delete</button></td>
        </tr>";
        }
        ?>
  
  </tbody>
</table>
      </div>
      <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
        crossorigin="anonymous"></script>
      <script src = //cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js></script>
      <script>
        let table = new DataTable('#myTable');
      </script>
      <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element)=>{
              element.addEventListener('click',(e)=>{
                tr = e.target.parentNode.parentNode
                title = tr.getElementsByTagName('td')[0].innerHTML;
                desc =  tr.getElementsByTagName('td')[1].innerHTML;
                titleEdit.value = title;
                descEdit.value = desc;
                snoEdit.value = e.target.id;
                $('#editModal').modal('toggle');
              })
        })
        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element)=>{
              element.addEventListener('click',(e)=>{
              sno = e.target.id.substr(1,);
              if(confirm('Are you sure you want to delete')){
                console.log("yes");
                window.location = `/CRUD/index.php?delete=${sno}`
              }
              else{
                console.log("no");
              }
              })
        })
      </script>
</body>
</html>