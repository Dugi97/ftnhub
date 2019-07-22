<?php
require_once '../config/db.php';

class DAOMovies {
    private $db;
    
    private $GETALLMOVIES = "SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m 
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID ORDER BY m.MovieID ASC ";
    //private $UPDATETELEFON = "UPDATE telefoni SET cena = ? WHERE id = ?";
    //private $DELETETELEFON = "DELETE  FROM telefoni WHERE id = ?";
    private $SELECTBYID = "SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail, m.ytlink FROM movie m
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID WHERE m.MovieID = ? ";
    //private $INSERTTELEFON = "INSERT INTO telefoni (naziv, cena, id_proizvodjaca) VALUES (?, ?, ?)";
    private $SEARCH = "SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID 
                               WHERE m.Title LIKE ? UNION
                               SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID
                               WHERE m.MovieID LIKE ? UNION
                               SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID
                               WHERE g.GenreName LIKE ? UNION
                               SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID
                               WHERE m.ReleaseYear LIKE ? ";
    private $GETALLGENRES = "SELECT * FROM genres g";
    private $GETALLBYGENRE = "SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m 
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID WHERE g.GenreID = ? ";
    private $GETALLCAST = "SELECT a.Picture, a.FirstName, a.LastName, m.Role FROM actor a 
                           JOIN moviecast m ON a.ActorID = m.ActorID WHERE m.MovieID = ? ";
    private $INSERTRATE = "INSERT INTO rating(MovieID, user_id, rev_stars) VALUES (?,?,?)";
    private $SELECTUSERID ="SELECT ReviewerID FROM reviewer WHERE username = ?";
    private $GETMOVIEID = "SELECT MovieID FROM movies WHERE MovieID = ? ";
    private $DELETERATE = "DELETE FROM rating WHERE MovieID = ? AND user_id= ? ";
    private $AVERAGERATE = "SELECT TRUNCATE(AVG(rev_stars),1) AS rate, user_id FROM rating WHERE MovieID = ?";
    private $GETALLBYNAMEGENRE = "SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID WHERE g.GenreName = ? AND m.MovieID != ? ";
    private $DELETERATEBYUSER = "DELETE FROM rating WHERE user_id = ? ";
    private $INSERTCOMMENT="INSERT INTO comments(MovieID,Username,text,date) VALUES (?,?,?,CURRENT_TIMESTAMP)";
    private $GETCOMMENTBYMOVIEID="SELECT * FROM comments WHERE MovieID = ?";
    private $GETALLCOMMENTS="SELECT * FROM comments WHERE 1";
    private $INSERTMOVIE="INSERT INTO movie(Title,ReleaseYear,Plot,MovieLength,thumbnail,ytlink) VALUES (?,?,?,?,?,?)";
    private $GETMOVIEID2 = "SELECT MovieID FROM movie WHERE Title = ?";
    private $GETGENREID = "SELECT GenreID FROM genres WHERE GenreName = ?";
    private $INSERTGENRE ="INSERT INTO moviegenres VALUES (?,?)";
    private $GETMOVIE="SELECT m.MovieID, m.Title, g.GenreName, m.ReleaseYear, m.Plot, m.MovieLength, m.thumbnail FROM movie m 
                               JOIN moviegenres ON m.MovieID = moviegenres.MovieID JOIN genres g ON moviegenres.GenreID = g.GenreID WHERE m.MovieID = ? ";
    private $INSERTWATCHLIST="INSERT INTO watchlist(MovieID,UserID) VALUES(?,?)";
    private $GETID="SELECT MovieID FROM watchlist";
    private $GETALL="SELECT * FROM watchlist";
    private $GETRATEBYMOVIEID="SELECT rev_stars FROM rating WHERE MovieID=?";
    private $DELETEWL = "DELETE FROM watchlist WHERE MovieID = ? AND UserID= ? ";
    private $GETALLACTORS = "SELECT FirstName, LastName FROM actor ORDER BY ActorID ASC";
    private $GETACTORIDBYFLNAME = "SELECT ActorID FROM actor WHERE CONCAT(FirstName, ' ', LastName) = ?";
    private $INSERTCAST = "INSERT INTO moviecast VALUES (?,?,?)";
    private $INSERTACTOR = "INSERT INTO actor (FirstName, LastName, Nationality, Birth, picture) VALUES (?,?,?,?,?)";
    private $GETMOVIEINFOBYID = "SELECT m.*, g.GenreName, c.*, CONCAT(a.FirstName,' ',a.LastName) AS fullname FROM movie m
                                 JOIN moviegenres mg ON m.MovieID = mg.MovieID 
                                 JOIN genres g ON mg.GenreID = g.GenreID
                                 JOIN moviecast c ON c.MovieID = m.MovieID
                                 JOIN actor a ON a.ActorID = c.ActorID WHERE m.MovieID = ?";
    private $GETACTOR = "SELECT CONCAT(a.FirstName,' ',a.LastName) AS fullname FROM actor a WHERE fullname LIKE ? ";
    private $UPDATEMOVIE = "UPDATE movie SET Title = ? , ReleaseYear = ? , Plot = ? , MovieLength = ? , thumbnail = ? , ytlink = ? WHERE MovieID = ? ";
    private $UPDATEGENRE = "UPDATE moviegenres SET GenreID = ? WHERE MovieID = ? ";
    private $DELETECAST = "DELETE FROM moviecast WHERE MovieID = ? ";
    private $DELETEGENRE = "DELETE FROM moviegenres WHERE MovieID = ? ";
    private $DELETERATING = "DELETE FROM rating WHERE MovieID = ? ";
    private $DELETEMOVIE = "DELETE FROM movie WHERE MovieID = ? ";
    
    
    
    public function __construct()
    {
        $this->db = DB::createInstance();
    }
    
   /* public function insertTelefon($naziv, $cena, $id_proizvodjaca)
    {
        $statement = $this->db->prepare($this->INSERTTELEFON);
        $statement->bindValue(1, $naziv);
        $statement->bindValue(2, $cena);
        $statement->bindValue(3, $id_proizvodjaca);
        
        $statement->execute();
    } */
    
    public function getAllMovies()
    {
        $statement = $this->db->prepare($this->GETALLMOVIES);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function getAllActors()
    {
        $statement = $this->db->prepare($this->GETALLACTORS);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function getAllGenres()
    {
        $statement = $this->db->prepare($this->GETALLGENRES);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function getAllByGenre($id)
    {
        $statement = $this->db->prepare($this->GETALLBYGENRE);
        $statement->bindValue(1, $id);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function getMovieInfoByID($id)
    {
        $statement = $this->db->prepare($this->GETMOVIEINFOBYID);
        $statement->bindValue(1, $id);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    
    public function getActorIDByFLName($fullname)
    {
        
        $statement = $this->db->prepare($this->GETACTORIDBYFLNAME);
        $statement->bindValue(1, $fullname);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
    }
    
    public function getAllByGenreName($name, $id)
    {
        $statement = $this->db->prepare($this->GETALLBYNAMEGENRE);
        $statement->bindValue(1, $name);
        $statement->bindValue(2, $id);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    
    public function getMovieByID($id)
    {
        $statement = $this->db->prepare($this->SELECTBYID);
        $statement->bindValue(1, $id);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
    }
    
    public function getMovieByKeyword($key)
    {
        $statement = $this->db->prepare($this->SEARCH);
        $statement->bindValue(1, $key);
        $statement->bindValue(2, $key);
        $statement->bindValue(3, $key);
        $statement->bindValue(4, $key);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function getActorsByMovieId($id)
    {
        $statement = $this->db->prepare($this->GETALLCAST);
        $statement->bindValue(1, $id);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function insertRate($movieid, $userid, $rate)
    {
        
        $statement = $this->db->prepare($this->INSERTRATE);
        $statement->bindValue(1, $movieid);
        $statement->bindValue(2, $userid);
        $statement->bindValue(3, $rate);
        
        $statement->execute();
        
    }
    
    public function getUserID($username)
    {
        
        $statement = $this->db->prepare($this->SELECTUSERID);
        $statement->bindValue(1, $username);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
    }
    
    public function getMovieID($id)
    {
        
        $statement = $this->db->prepare($this->GETMOVIEID);
        $statement->bindValue(1, $id);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
    }
    
    public function deleteRate($movieid, $userid)
    {
        
        $statement = $this->db->prepare($this->DELETERATE);
        $statement->bindValue(1, $movieid);
        $statement->bindValue(2, $userid);
        
        $statement->execute();
        
    }
    
    public function getAverageRate($movieid)
    {
        $statement = $this->db->prepare($this->AVERAGERATE);
        $statement->bindValue(1, $movieid);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
    }
    
    public function insertComment($MovieID,$UserID,$comment){
        $statement = $this->db->prepare($this->INSERTCOMMENT);
        $statement->bindValue(1, $MovieID);
        $statement->bindValue(2, $UserID);
        $statement->bindValue(3, $comment);
        
        $statement->execute();
    }
    
    public function getCommentByMovieID($MovieID)
    {
        
        $statement = $this->db->prepare($this->GETCOMMENTBYMOVIEID);
        $statement->bindValue(1, $MovieID);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    public function getAllComments()
    {
        
        $statement = $this->db->prepare($this->GETALLCOMMENTS);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    
    public function insertMovie($Title,$ReleaseYear,$Plot,$MovieLength,$thumbnail,$ytlink)
    {
        
        $statement = $this->db->prepare($this->INSERTMOVIE);
        
        $statement->bindValue(1, $Title);
        $statement->bindValue(2, $ReleaseYear);
        $statement->bindValue(3, $Plot);
        $statement->bindValue(4, $MovieLength);
        $statement->bindValue(5, $thumbnail);
        $statement->bindValue(6, $ytlink);
        
        $statement->execute();
        
    }
    
    public function InsertCast($ActorID, $MovieID, $Role)
    {
        
        $statement = $this->db->prepare($this->INSERTCAST);
        
        $statement->bindValue(1, $ActorID);
        $statement->bindValue(2, $MovieID);
        $statement->bindValue(3, $Role);
        
        $statement->execute();
        
    }
    
    public function InsertActor($FirstName, $LastName, $Nationality, $Birth, $Picture)
    {
        
        $statement = $this->db->prepare($this->INSERTACTOR);
        
        $statement->bindValue(1, $FirstName);
        $statement->bindValue(2, $LastName);
        $statement->bindValue(3, $Nationality);
        $statement->bindValue(4, $Birth);
        $statement->bindValue(5, $Picture);
        
        $statement->execute();
        
    }
    
    public function getMovieIDbyTitle($titl)
    {
        
        $statement = $this->db->prepare($this->GETMOVIEID2);
        
        $statement->bindValue(1, $titl);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
        
    }
    
    public function getGenreIDbyName($name)
    {
        
        $statement = $this->db->prepare($this->GETGENREID);
        
        $statement->bindValue(1, $name);
        
        $statement->execute();
        
        $result = $statement->fetch();
        return $result;
        
    }
    
    public function insertGenre($title, $genre)
    {
        
        $statement = $this->db->prepare($this->INSERTGENRE);
        
        $statement->bindValue(1, $title);
        $statement->bindValue(2, $genre);
        
        $statement->execute();
        
    }
    
    public function deleteRateByUsername($username)
    {
        
        $statement = $this->db->prepare($this->DELETERATEBYUSER);
        $statement->bindValue(1, $username);
        
        $statement->execute();
    }
    public function getRate($MovieID){
        $statement = $this->db->prepare($this->GETRATEBYMOVIEID);
        $statement->bindValue(1, $MovieID);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    public function getAllWatchlist(){
        $statement = $this->db->prepare($this->GETALL);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    public function getID(){
        $statement = $this->db->prepare($this->GETID);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    
    public function insertWatchlist($MovieID,$UserID){
        $statement = $this->db->prepare($this->INSERTWATCHLIST);
        $statement->bindValue(1, $MovieID);
        $statement->bindValue(2, $UserID);
        
        $statement->execute();
        
    }
    public function getMovie($MovieID){
        $statement = $this->db->prepare($this->GETMOVIE);
        $statement->bindValue(1, $MovieID);
        
        $statement->execute();
        
        $result = $statement->fetchAll();
        return $result;
    }
    public function deleteWL($movieid, $userid)
    {
        
        $statement = $this->db->prepare($this->DELETEWL);
        $statement->bindValue(1, $movieid);
        $statement->bindValue(2, $userid);
        
        $statement->execute();
        
    }
    
    public function updateMovie($title, $releaseYear, $plot, $movieLength, $thumbnail, $ytlink, $movieID)
    {
        
        $statement = $this->db->prepare($this->UPDATEMOVIE);
        $statement->bindValue(1, $title);
        $statement->bindValue(2, $releaseYear);
        $statement->bindValue(3, $plot);
        $statement->bindValue(4, $movieLength);
        $statement->bindValue(5, $thumbnail);
        $statement->bindValue(6, $ytlink);
        $statement->bindValue(7, $movieID);
        
        $statement->execute();
        
    }
    
    public function updateGenre($genreID, $movieID)
    {
        
        $statement = $this->db->prepare($this->UPDATEGENRE);
        $statement->bindValue(1, $genreID);
        $statement->bindValue(2, $movieID);

        $statement->execute();
        
    }
    
    public function deleteCast($movieID)
    {
        
        $statement = $this->db->prepare($this->DELETECAST);
        $statement->bindValue(1, $movieID);
        
        $statement->execute();
        
    }
    
    public function deleteGenre($movieID)
    {
        
        $statement = $this->db->prepare($this->DELETEGENRE);
        $statement->bindValue(1, $movieID);
        
        $statement->execute();
        
    }
    
    public function deleteRating($movieID)
    {
        
        $statement = $this->db->prepare($this->DELETERATING);
        $statement->bindValue(1, $movieID);
        
        $statement->execute();
        
    }
    
    public function deleteMovie($movieID)
    {
        
        $statement = $this->db->prepare($this->DELETEMOVIE);
        $statement->bindValue(1, $movieID);
        
        $statement->execute();
        
    }
    
    
    
  
}
?>
