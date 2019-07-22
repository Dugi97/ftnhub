<?php
$ttl = isset($_GET['keyword'])? $_GET['keyword'] : "";
$msg = isset($msg)? $msg : "";
$mvs = isset($mvs)? $mvs : "";
$message = isset($message)? $message : "";
$a = isset($a)? $a : "";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Insert title here</title>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/home.css">
</head>
<body>
<form class="searchbar" action="../movies/" method="GET">
<input class="auto" type="text" name="keyword" placeholder=" Type..." value="<?php echo $ttl;?>">
<input type="submit" name="action" value="Search"> 
</form>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>	
<script type="text/javascript">
$(function() {
	
	//autocomplete
	$(".auto").autocomplete({
		source: "searchmv.php",
		minLength: 1
	});				
});
</script>

</body>
</html>