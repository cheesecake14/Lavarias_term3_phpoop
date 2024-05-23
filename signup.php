<?php

require_once('classes/database.php');

$con = new database();

if (isset($_POST['Signup'])) {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $birthday = $_POST['birthday'];
  $gender = $_POST['gender'];  
  $username = $_POST['user'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  if($password == $confirm) {
    if($con->signup($firstname, $lastname, $birthday, $gender, $username, $password)) {
        header('location:multisave.php');
  } else {
    $error_message = 'Username already exist. Please choose a diffrent username.';
  }
} else {
    $error_message = 'Password did not match';
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SignUp Page</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container-fluid rounded shadow login-container">
  <h2 class="text-center mb-4">SignUp</h2>
  <form method="post">
    <div class="form-group">
      <label for="firstname">First Name:</label>
      <input type="text" class="form-control" name="firstname" placeholder="Pangalan mo">
    </div>
    <div class="form-group">
      <label for="lastname">Last Name:</label>
      <input type="text" class="form-control" name="lastname" placeholder="Apelyido mo">
    </div>
    <div class="mb-3">
      <label for="birthday" class="form-label">Birthday:</label>
      <input type="date" class="form-control" name="birthday">
    </div>
    <div class="mb-3">
      <select class="form-select" aria-label="Default select example" name="gender">
        <option selected>Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Choose not to Indicate">Choose not to Indicate</option>
      </select>
    </div>
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" class="form-control" name="user" placeholder="Ano pangalan mo?">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" name="password" placeholder="Nakalimutan mo Password mo">
    </div>
    <div class="form-group">
      <label for="confirm">Confirm Password:</label>
      <input type="password" class="form-control" name="confirm" placeholder="Paulit ng Password Mo">
    </div>
    <div class="container">
      <div class="row gx-1">
        <div class="col">
            <input type="submit" name="Signup" class="btn btn-danger btn-block">
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>