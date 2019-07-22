<?php
require 'flight/Flight.php';
require_once '../movies/DAOMovies.php';

Flight::route('/kardan/', function(){
    echo 'hello world!';
});

    Flight::route('GET /movies/@$MovieID', function($MovieID){
        
        header("Location:/movies/?action=MovieInfo&MovieID=$MovieID");
    });

Flight::start();
