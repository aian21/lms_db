<?php
require_once('classes/database.php');
$con = new database();
session_start();
$sweetAlertConfig = "";
 



 if(empty($id = $_POST['id'])) {
     header('location:index.php');
 
 } else {
    $id = $_POST['id'];
    $data = $con->viewGenreID($id);
 
}

if (isset($_POST['add'])) {
  

    $id = $_POST['id'];
    $genreName = $_POST['genreName'];

    $authorID = $con->updateGenre($genreName, $id);




if ($authorID){
    $sweetAlertConfig = "
    <script>
    Swal.fire({
      icon: 'success',
      title: 'Genre Updated',
      text: 'Genre Name Updated Successfully!',
      confirmButtonText: 'OK'
    }).then((result) => {
    if (result.isConfirmed) {
    window.location.href = 'admin_homepage.php';
    }
    });
    </script> ";

  }else{
    $_SESSION['error'] = "Sorry, there was an error adding.";
  }
 

}

?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <title>Genres</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Library Management System (Admin)</a>
    <a class="btn btn-outline-light ms-auto" href="add_authors.html">Add Authors</a>
    <a class="btn btn-outline-light ms-2 active" href="add_genres.html">Add Genres</a>
    <a class="btn btn-outline-light ms-2" href="add_books.html">Add Books</a>
  </div>
</nav>

<div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">
  <h4 class="mt-5">Add New Genre</h4>
  <form id="genreForm" method="POST" action="">
    <div class="mb-3">
      <label for="genreName" class="form-label">Genre Name</label>
      <input type="text"  value="<?php echo $data['genre_name']; ?>" name="genreName" class="form-control" id="genreName" required>
      <div class="invalid-feedback">Please enter a valid genre.</div>
    </div>

    <input type="hidden" name="id" value="<?php echo $data['genre_id']; ?>">
    <button type="submit"  id="add" name="add" class="btn btn-primary">Update Genre</button>
    
  </form>

  <script src="./package/dist/sweetalert2.js"></script>
  <?php echo $sweetAlertConfig; ?>

  <script>
    const genreNameInput = document.getElementById('genreName');

    genreNameInput.addEventListener('input', () => {
      const genreName = genreNameInput.value.trim();

      if (genreName === '') {
        genreNameInput.classList.remove('is-valid');
        genreNameInput.classList.add('is-invalid');
        genreNameInput.nextElementSibling.textContent = 'Genre name is required.';
        submitButton.disabled = true;
        return;
      }

      fetch('AJAX/check_genre.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `genreName=${encodeURIComponent(genreName)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.exists) {
          genreNameInput.classList.remove('is-valid');
          genreNameInput.classList.add('is-invalid');
          genreNameInput.nextElementSibling.textContent = 'Genre already exists.';
          submitButton.disabled = true;
        } else {
          genreNameInput.classList.remove('is-invalid');
          genreNameInput.classList.add('is-valid');
          genreNameInput.nextElementSibling.textContent = '';
          submitButton.disabled = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        submitButton.disabled = true;
      });
    });

    // Final form validation
    document.getElementById('genreForm').addEventListener('submit', function (e) {
      if (!genreNameInput.classList.contains('is-valid')) {
        genreNameInput.classList.add('is-invalid');
        e.preventDefault();
      }
    });
  </script>
</div>

<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>
