<?php
/**
* Controls the database access for the entire site
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/

/**
 * Database handles the database connection methods
 *
 * @author David Stanley <david@stanleysoft.org>
 * @license MIT
 */
class DATABASE
{   
    /**
     * The host server address
     *
     * @author David Stanley <ken@stanleysoft.org>
     * @license MIT
     */
    private $host = "localhost";
    /** The database name*/
    private $db_name = "wb";
    /** The database user name*/
    private $username = "wb";
    /** The database password*/
    private $password = "wb";
    /** The connection string */
    public $conn;
     
    /**
     * Makes connection with the database
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>