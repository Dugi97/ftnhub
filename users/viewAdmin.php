<?php
require_once '../movies/DAOMovies.php';
require_once '../users/controllerUsers.php';
$dao = new DAOMovies();
$movies = $dao->getAllMovies();
$genres = $dao->getAllGenres();
$actors = $dao->getAllActors();
$msg1 = isset($msg1) ? $msg1 : "";
$msg2 = isset($msg2) ? $msg2 : "";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>FtnHub</title>
<link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/home.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="../js/JavaScript.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<header>
<?php include '../header/header.php'; ?>
</header>

	
<div class="grid">

<div class="genres"><?php include '../movies/viewShowGenresSidebar.php';?></div>

<div class="panel">
<div class="insertm">
<h1>INSERT MOVIE</h1>
<form action="../users/" method="get">
Title :<br><input  type="text" name="Title"><br><br>
Release Year :<br><input type="number" name="ReleaseYear"><br><br>
Plot :<br><input type="text" name="Plot"><br><br>
Movie Length :<br><input type="text" name="MovieLength"><br><br>
Picture URL :<br><input type="text" name="thumbnail"><br><br>
Youtube link :<br><input type="text" name="ytlink"><br><br>
Movie Genre:<br><select name="genre">
				<?php foreach ($genres as $g){?>
  				<option value="<?php echo $g['GenreName'];?>"><?php echo $g['GenreName'];?></option>
  				<?php } ?>
				</select> <br><br>

Actors:<br>
	<?php foreach ($actors as $a){?>
	<input type="checkbox" name="actor[]" value="<?php echo $a['FirstName']." ".$a['LastName'];?>"><?php echo $a['FirstName']." ".$a['LastName'];?> | Role: <input type="text" name="role[]"> <br>
	<?php }?> <br>
<input type="submit" name="action" value="Insert Movie">
</form>
<?php echo $msg1;?>
</div>

<div class="inserta">
<h1>INSERT ACTOR</h1>
<form action="../users/" method="get"> 
First Name: <br>
<input type="text" name="Fname"> <br><br>
Last Name: <br>
<input type="text" name="Lname"> <br><br>
Nationality: <br>
<input type="text" name="Nationality"> <br><br>
Birth: <br>
<input type="date" name="Birth"> <br><br>
Picture URL: <br>
<input type="text" name="Picurl"> <br><br>

<input type="submit" name="action" value="Insert actor">
</form>
<?php echo $msg2;?>
</div>

<div class="updatem">
<h1>UPDATE MOVIE</h1>
<table border="1">
<tr>
<th>Movie Title</th>
<th>Edit Movie</th>
<th>Delete Movie</th>
</tr>
<?php foreach($movies as $mvs){?>
<tr>
<td><?php echo $mvs['Title'];?></td>
<td><a id='ta1' href="../users/?action=Edit+Movie&MovieID=<?php echo $mvs['MovieID'];?>">Edit</a></td>
<td><a id='ta2' href="../users/?action=Delete+Movie&MovieID=<?php echo $mvs['MovieID'];?>">Delete</a></td>
</tr>
<?php } ?>
</table>
</div>

<div class="deletem">
<h1>DELETE USER</h1>
<form action="adminOptions.php" method="get"> <br>
<input type="text" name="username">
<input type="submit" name="action" value="delete">
</form>
</div>


</div>
</div>

<footer>
	<?php include '../footer/footer.php'?>
</footer>
</body>
</html>

