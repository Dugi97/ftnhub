<?php
require_once '../movies/DAOMovies.php';
//require_once '../users/controllerUsers.php';
$dao = new DAOMovies();
$movies = $dao->getAllMovies();
$genres = $dao->getAllGenres();
$actors = $dao->getAllActors();
$minfo = $dao->getMovieInfoByID($MovID);
$msg3 = isset($msg3) ? $msg3 : "";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Edit Movie</title>
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

<div class="editm">
<h1>Edit Movie</h1>
<form action="../users/" method="post">
Movie ID: <input  type="hidden" name="MovieID" value="<?php foreach ($minfo as $min){ echo $min['MovieID']; break;}?>"> <?php echo $MovID;?><br><br>
Title :<br><input  type="text" name="Title" value="<?php foreach ($minfo as $min){ echo $min['Title']; break;}?>"><br><br>
Release Year :<br><input type="number" name="ReleaseYear" value="<?php foreach ($minfo as $min){ echo $min['ReleaseYear']; break;}?>"><br><br>
Plot :<br><input type="text" name="Plot" value="<?php foreach ($minfo as $min){ echo $min['Plot']; break;}?>"><br><br> 
Movie Length :<br><input type="text" name="MovieLength" value="<?php foreach ($minfo as $min){ echo $min['MovieLength']; break;}?>"><br><br>
Picture URL :<br><input type="text" name="thumbnail" value="<?php foreach ($minfo as $min){ echo $min['thumbnail']; break;}?>"><br><br>
Youtube link :<br><input type="text" name="ytlink" value="<?php foreach ($minfo as $min){ echo htmlspecialchars($min['ytlink']); break;}?>"><br><br>
Movie Genre:<br><select name="genre">
				<?php foreach ($genres as $g){ foreach ($minfo as $min){?>
				<?php if($min['GenreName'] == $g['GenreName']){ ?>
				    <option value="<?php echo $g['GenreName'];?>" selected><?php echo $g['GenreName']; break;?></option>
				<?php } 
                else { ?>
				    <option value="<?php echo $g['GenreName'];?>"><?php echo $g['GenreName']; break;?></option>
  				<?php } ?>
				<?php }}?>
  				
				</select> <br><br>

<?php foreach ($minfo as $min){
    $new[]= $min['fullname'];
    $rolle[]= $min['Role'];
}
?>



Actors:<br>
	<?php $i=0; foreach ($actors as $a){?>
	 
	
	<?php if(in_array($a['FirstName']." ".$a['LastName'], $new)){?>
		<input type="checkbox" name="actor[]" value="<?php echo $a['FirstName']." ".$a['LastName'];?>" checked><?php echo $a['FirstName']." ".$a['LastName']; ?>
		| Role: <input type="text" name="role[]" value="<?php echo $rolle[$i++];?>"> <br>
	<?php } 
	else {?>
	    <input type="checkbox" name="actor[]" value="<?php echo $a['FirstName']." ".$a['LastName'];?>"><?php echo $a['FirstName']." ".$a['LastName']; ?>
	    | Role: <input type="text" name="role[]"> <br>
	<?php }} ?>
<br>
<input type="submit" name="action" value="Save">

</form>
<?php echo $msg3;?>
</div>

	
	
</div> 

</div>
</div>

<footer>
	<?php include '../footer/footer.php'?>
</footer>
</body>
</html>
