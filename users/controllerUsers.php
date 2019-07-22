<?php
require_once 'modelUser.php';
require_once 'DAOUsers.php';
require_once 'session.php';
require_once '../movies/DAOMovies.php';
//kod za logovanje
class ControllerUsers {
    
    function goLoginUser() {
        include 'viewLogin.php';
    }
    
    function testInput ($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    function loginUser () {
        $cu = new ControllerUsers();
        
        $username = isset($_POST['username'])? $cu->testInput($_POST['username']) : "";
        $password = isset($_POST['password'])? $cu->testInput($_POST['password']) : "";
        $remember_user = isset($_POST['remember_user'])? $_POST['remember_user'] : "";
        
        if(!empty($username) && !empty($password)) {
            $daoC = new DAOUsers();
            $userC = $daoC->getUserByUsernamePassword($username, $password);
            
            if($username == $userC['username'] && $password == $userC['password']) {
                   
                   if(isset($_SESSION['user_logged'])) {
                        unset($_SESSION['user_logged']);
                        $user_logged = new modelUser($username, $password);
                        $_SESSION['user_logged'] = $user_logged;
                        
                        if($remember_user == "Yes") {
                            $toCookie = array("user" =>$_SESSION["user_logged"]->username, "pass"=>$_SESSION["user_logged"]->password);
                            $json = json_encode($toCookie);
                            setcookie("json_cookie", $json, time() + (10), "/"); 
                        } else {
                            setcookie("json_cookie", "", time()-3600, "/");
                         }
                        
                         header("Location:../movies/viewShowAllMovies.php");
                    } else {
                        $user_logged = new modelUser($username, $password);
                        $_SESSION['user_logged'] = $user_logged;
                        
                        if($remember_user == "Yes") {
                            $toCookie = array("user" =>$_SESSION["user_logged"]->username, "pass"=>$_SESSION["user_logged"]->password);
                            $json = json_encode($toCookie);
                            setcookie("json_cookie", $json, time() + (10), "/"); 
                        } else {
                           setcookie("json_cookie", "", time()-3600, "/"); 
                        }
                        
                        include 'viewDashboard.php';}    
                } else {
                    $msg = "Wrong username or password";
                    include 'viewLogin.php';
                }
        } else {
            $msg = "You must fill in all fields";
            include '../users/viewLogin.php';
        }  
    }
    
    function logoutUser () {
        session_start();
        $idCh = isset($_GET['username'])? $_GET['username'] : "";
       
        
        if(isset($_SESSION['user_logged'])) {
            if ($idCh == $_GET['username']) { 
                session_destroy();
                unset($_SESSION['user_logged']);
                 
                header("Location:../index.php");
            } else {
                header("Location:../index.php");
                
                exit;
            }
        }
    }
    // Kod za registraciju
    function registerUser () {
        $cu = new ControllerUsers();
        
        $username = isset($_POST['username'])? $cu->testInput($_POST['username']) : "";
        $password = isset($_POST['password'])? $cu->testInput($_POST['password']) : "";
        $password_c = isset($_POST['password_c'])? $cu->testInput($_POST['password_c']) : "";
        
        if(!empty($username) && !empty($password) && !empty($password_c)) {
            if (strlen($password) >= 8) {
                if($password == $password_c) {
                    $daoC = new DAOUsers();
                    $userC = $daoC->getUserByUsername($username);
                    if($username != $userC['username']) {
                        $dao = new DAOUsers();
                        $newuser = $dao->insertUser($username, $password);
                        $msg = "You have successfully registered, now you can log in";
                        include 'viewLogin.php';
                        
                    } else {
                        $msg = "Username already exist, you can choose another one";
                        include 'viewRegister.php';
                    }
                } else {
                    $msg = "Passwords don't match";
                    include 'viewRegister.php';
                }
            } else {
                $msg = "Password need to have at least 8 characters";
                include 'viewRegister.php';
            }
        } else {
            $msg = "You must fill in all fields";
            include 'viewRegister.php';
        }
    }
    
    function goRegisterUser () {
        include 'viewRegister.php';
    }
    function validate(){
        $cu = new ControllerUsers();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = isset($_POST['name'])? $cu->testInput($_POST['name']) : "";
            $email = isset($_POST['email'])? $cu->testInput($_POST['email']) : "";
            $message = isset($_POST['message'])? $cu->testInput($_POST['message']) : "";
            
            if (!empty($name) && !empty($email) && !empty($message)){
                if(preg_match("/^[a-zA-Z]*$/", $name)){
                    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                        if(strlen($message) > 10) {
                            // ???
                            $err = "Message sent succesfully !";
                            include '../users/viewContact.php';
                        }
                        else {
                            
                            $err = "Must have atleast 10 characters !";
                            include '../users/viewContact.php';
                        }
                    }
                    else {
                        $err = "Invalid email !";
                        include '../users/viewContact.php';
                    }
                    
                }
                else {
                    $err = "Name must contain only letters !";
                    include '../users/viewContact.php';
                }
            }
            else {
                $err = 'All fields must be filled !';
                include '../users/viewContact.php';
            }
            
            
        }
        else {
            $err = 'Wrong method !';
            include '../users/viewContact.php';
        }
    }
    
    function insertMovie(){
        
        $dao=new DAOMovies();
        $cu = new ControllerUsers();
        
        // UNOS FILMOVA
        
        $ytlink=isset($_GET['ytlink'])? $cu->testInput($_GET['ytlink']) : "";
        $Title=isset($_GET['Title'])? $cu->testInput($_GET['Title']) : "";
        $ReleaseYear=isset($_GET['ReleaseYear'])? $cu->testInput($_GET['ReleaseYear']) : "";
        $Plot=isset($_GET['Plot'])? $cu->testInput($_GET['Plot']) : "";
        $MovieLength=isset($_GET['MovieLength'])? $cu->testInput($_GET['MovieLength']) : "";
        $thumbnail=isset($_GET['thumbnail'])? $cu->testInput($_GET['thumbnail']) : "";
        
        $genre=isset($_GET['genre'])? $cu->testInput($_GET['genre']) : "";
        
        $ArrayActors=isset($_GET['actor'])? ($_GET['actor']) : "";
        $Role=isset($_GET['role'])? ($_GET['role']) : "";
        $resultRole = array_values(array_filter($Role));
        
        if(!empty($ytlink) && !empty($Title) && !empty($ReleaseYear) && !empty($Plot) && !empty($MovieLength) && !empty($thumbnail) && !empty($ArrayActors) && !empty($Role)){
            if(is_numeric($ReleaseYear)){
                $dao->insertMovie($Title, $ReleaseYear, $Plot, $MovieLength, $thumbnail, $ytlink);
                
                $mid = $dao->getMovieIDbyTitle($Title);
                $gid = $dao->getGenreIDbyName($genre);
                $dao->insertGenre($mid['MovieID'], $gid['GenreID']);
                
                for($i=0; $i<count($ArrayActors); $i++){
                    $act = $dao->getActorIDByFLName($ArrayActors[$i]);
                    $dao->InsertCast($act['ActorID'], $mid['MovieID'], $resultRole[$i]);
                    
                }
                header("Location:../users/viewAdmin.php");
                
                
            }else{
                $msg1="Release Year must be a number !!!";
                include '../users/viewAdmin.php';
            }
            
        }else{
            $msg1="You have to fill in all fields !!!";
            include '../users/viewAdmin.php';
        }
    }
    
    function insertActor(){
        //DODAVANJE GLUMCA
        $dao = new DAOMovies();
        $cu = new ControllerUsers();
        
        $Fname=isset($_GET['Fname'])? $cu->testInput($_GET['Fname']) : "";
        $Lname=isset($_GET['Lname'])? $cu->testInput($_GET['Lname']) : "";
        $Nationality=isset($_GET['Nationality'])? $cu->testInput($_GET['Nationality']) : "";
        $Birth=isset($_GET['Birth'])? $cu->testInput($_GET['Birth']) : "";
        $picurl=isset($_GET['Picurl'])? $cu->testInput($_GET['Picurl']) : "";
        
        if(!empty($Fname) && !empty($Lname) && !empty($Nationality)){
            if(!is_numeric($Fname) && !is_numeric($Lname) && !is_numeric($Nationality)){
                $dao->InsertActor($Fname, $Lname, $Nationality, $Birth, $picurl);
                
                header("Location:../users/viewAdmin.php");
            }
            else {
                $msg2="First Name, Last Name and Nationality must be character !!!";
                include '../users/viewAdmin.php';
            }
            
        }else{
            $msg2="You have to fill in all fields !!!";
            include '../users/viewAdmin.php';
        }
    }
    
    function editMovie() {
        $MovID = isset($_GET['MovieID'])? $_GET['MovieID'] : "";
        
        include '../users/EditMovie.php';
    }
    
    function updateMovie(){
        $dao=new DAOMovies();
        $cu = new ControllerUsers();
        
        // IZMENA FILMOVA
        
        $Title=isset($_POST['Title'])? $cu->testInput($_POST['Title']) : "";
        $ReleaseYear=isset($_POST['ReleaseYear'])? $cu->testInput($_POST['ReleaseYear']) : "";
        $Plot=isset($_POST['Plot'])? $cu->testInput($_POST['Plot']) : "";
        $MovieLength=isset($_POST['MovieLength'])? $cu->testInput($_POST['MovieLength']) : "";
        $thumbnail=isset($_POST['thumbnail'])? $cu->testInput($_POST['thumbnail']) : "";
        $ytlink=isset($_POST['ytlink'])? htmlspecialchars_decode($_POST['ytlink']) : "";
        $movieID = isset($_POST['MovieID'])? $cu->testInput($_POST['MovieID']) : "";
        
        $genre=isset($_POST['genre'])? $cu->testInput($_POST['genre']) : "";
        $gid = $dao->getGenreIDbyName($genre);
        
        $ArrayActors=isset($_POST['actor'])? ($_POST['actor']) : "";
        $Role=isset($_POST['role'])? ($_POST['role']) : "";
        $resultRole = array_values(array_filter($Role));
        
        
        if(!empty($ytlink) && !empty($Title) && !empty($ReleaseYear) && !empty($Plot) && !empty($MovieLength) && !empty($thumbnail)){
            if(is_numeric($ReleaseYear)){
                $dao->updateMovie($Title, $ReleaseYear, $Plot, $MovieLength, $thumbnail, $ytlink, $movieID);
                $dao->updateGenre($gid['GenreID'], $movieID);
                $dao->deleteCast($movieID);
                
                for($i=0; $i<count($ArrayActors); $i++){
                    $act = $dao->getActorIDByFLName($ArrayActors[$i]);
                    $dao->InsertCast($act['ActorID'], $movieID, $resultRole[$i]);
                    
                }
                header("Location:../users/viewAdmin.php");
                
            }
            else{
                $msg3 = "Release Year must be a number !";
                include '../users/editMovie.php';
            }
            
        }
        else {
            $msg3 = "You have to fill in all fields !";
            include '../users/editMovie.php';
        }
    }
    
    function deleteMovie() {
        $dao = new DAOMovies();
        
        $movieID = isset($_GET['MovieID'])? $_GET['MovieID'] : "";
        
        $dao->deleteCast($movieID);
        $dao->deleteGenre($movieID);
        $dao->deleteRating($movieID);
        $dao->deleteMovie($movieID);
        
        
        header("Location:../users/viewAdmin.php");
        
        
    }
    

    
}

?>