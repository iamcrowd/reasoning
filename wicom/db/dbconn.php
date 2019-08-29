<?php 
/* 

   Copyright 2017 Giménez, Christian
   
   Author: Giménez, Christian   

   dbconn.php
   
   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wicom\DB;

use function \load;
load("config.php", "../../config/");

use \mysqli;
use \mysqli_result;

/**
   A DB connection.

   We obtain DB information from the config.php file.
 */
class DbConn{

    /**
       The DB connection. Needed by the mysql_connect() PHP function.
     */
    protected $conn = null;

    /**
       Last query's results.
    */
    protected $last_results = null;

    /**
       Create a new DbConn instance. Create database and tables if needed.

       We don't ask for parameters because it cames from globals parameters. 

       > Yes, I know, is not the best way, but life's short!
     */
    function __construct(){
        // Create connection
        
        $this->conn = new mysqli(
            $GLOBALS['config']['db']['host'],
            $GLOBALS['config']['db']['user'],
            $GLOBALS['config']['db']['password']);
      
        if ($this->conn->connect_errno){
            die('Could not connect to the DB. Ask your administrator.');
        }

        $this->conn->set_charset($GLOBALS['config']['db']['charset']);

        // Create tables if needed       
        $this->create_database();
    }

    /**
       Create all tables if they don't exists. Also select the database.
     */
    function create_database(){
        $dbname = $GLOBALS['config']['db']['database'];
        if (!$this->query("CREATE DATABASE IF NOT EXISTS %s;", [$dbname])){
            die("Database could not be created.");
        }
        $this->conn->select_db($dbname);
        if (!$this->query('CREATE TABLE IF NOT EXISTS users (name CHAR(20), pass CHAR(20), PRIMARY KEY (name));')){
            die("Tables in the database could not be created.");
        }
        if (!$this->query('CREATE TABLE IF NOT EXISTS model (name CHAR(20), owner CHAR(20), json LONGTEXT, PRIMARY KEY (name, owner), FOREIGN KEY (owner) REFERENCES users(name) );')){
            die("Tables in the database could not be created.");
        }
    }

    /**
       Close connection.
    */
    function close(){
        $this->conn->close();
    }

    /**
       Send a query to the DB.

       @param $sql a String.
       @return A mysqli_result instance. You can use the res_field() and other res_* messages implemented in this class.
       @see http://php.net/manual/en/class.mysqli-result.php
     */
    function query($sql, $params=[]){
        // Escape params for security reasons (no SQL Injections!)
        $escaped_params = [];
        foreach ($params as $p){
            $escaped_params[] = $this->conn->escape_string($p);
        }        
        $sql_processed = vsprintf($sql, $escaped_params);
        
        /*
          print($sql_processed);
          print("\n");
        */
        
        $this->last_results = $this->conn->query($sql_processed);

        return $this->last_results;
    }

    /**
       @name Last Query's Results Retrieving Functions
       
       Functions to retrieve the last query results functions.
     */
    //@{

    /**
       Retrieve all the field values.
       
       @return a Array with mixed elements (depends on the query).
       @return false if the field doesn't exists.
     */
    function res_field($field){
        if (!$this->last_results){
            return false;
        }
        
        if (!$this->field_exists($field)){
            // Field doesn't exists!
            return false;
        }
        
        $this->last_results->data_seek(0);

        $lstout = [];
        while ($row = $this->last_results->fetch_assoc()){
            $lstout[] = ($row[$field]); // push
        }

        return $lstout;        
    }

    /**
       This field name exists in the last results?
       
       @return true if it does, false otherwise.
     */
    protected function field_exists($fieldname){
        if (!$this->last_results){
            return false;
        }
        
        $fields = $this->last_results->fetch_fields();
        $amount = count($fields);
        
        $i = 0;
        while (($i < $amount) and ($fields[$i]->name != $fieldname)){
            $i++;
        }

        if ($fields[$i]->name == $fieldname){
            return true;
        }else{
            return false;
        }
    }

    /**
       Retrieve the n-th row from the last results.

       @param num A positive integer from cero (inclusive) and the amount of rows minus one inclusive.
       @return An associative Array where its keys are the field names and its values are the field value. False if the row number is out of boundaries.
       @see http://php.net/manual/en/mysqli-result.fetch-assoc.php
     */
    function res_nth_row($num){
        if (!$this->last_results){
            return false;
        }
        
        if (!$this->last_results->data_seek($num)){
            return false;
        }

        return $this->last_results->fetch_assoc();
    }

    /**
       Retrieve the mysqli_result instance of the last query.

       @return a mysqli_result instance.
       @see http://php.net/manual/en/class.mysqli-result.php
     */
    function get_last_results(){
        return $this->last_results;
    }
    //@}
    
}
?>
