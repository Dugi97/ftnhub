<?php
require_once 'controllerUsers.php';
require_once '../movies/DAOMovies.php';
$dao=new DAOMovies();

$cu = new ControllerUsers();
$cus = new DAOUsers();


// BRISANJE KORISNIKA PO IMENU
$allU=$cus->getAllUsers();
$username=isset($_GET['username'])?$_GET['username']:"";
if(!empty($username)){
    foreach ($allU as $a) {
        if($a['username']==$username){
            $dao->deleteRateByUsername($a['ReviewerID']);
            $cus->deleteUser($username);
        }
        include_once '../movies/viewShowAllMovies.php';
        
    }
}

?>