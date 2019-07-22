<?php
require_once 'controllerUsers.php';

$action=isset($_REQUEST['action'])? $_REQUEST['action'] : "";

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        switch ($action) {
            case "gologin":
                $cu = new ControllerUsers();
                $cu->goLoginUser();
                break;
            case "Logout":
                $cu = new ControllerUsers();
                $cu->logoutUser();
                break;
            case "goregister":
                $cu = new ControllerUsers();
                $cu->goRegisterUser();
                break;
            case "Insert Movie":
                $cu = new ControllerUsers();
                $cu->insertMovie();
                break;
            case "Insert actor":
                $cu = new ControllerUsers();
                $cu->insertActor();
                break;
            case "Edit Movie":
                $cu = new ControllerUsers();
                $cu->editMovie();
                break;
            case "Delete Movie":
                $cu = new ControllerUsers();
                $cu->deleteMovie();
                break;
            
        }
        break;
    case "POST":
        switch ($action) {
            case "Register":
                $cu = new ControllerUsers();
                $cu->registerUser();
                break;
            case "Log in":
                $cu = new ControllerUsers();
                $cu->loginUser();
                break;
            case "SEND MESSAGE":
                $cu = new ControllerUsers();
                $cu->validate();
                break;
            case "Save":
                $cu = new ControllerUsers();
                $cu->updateMovie();
                break;
        
        }
        
        break;
    default:header("Location:index.php");
        die();
        break;
}
?>