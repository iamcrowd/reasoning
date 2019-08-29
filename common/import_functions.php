<?php 
/* 

   Copyright 2016 Giménez, Christian
   
   Author: Giménez, Christian   

   import_functions.php
   
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

$current_path = "./";

/**
   This is a function for loading a module relative to the current file in static context.

   # Problem   
   There is a problem about paths. When an HTTP is requested on http://localhost/folderA/index.php the current path is the real one matching folderA virtual one, say ./proyect/folderA/index.php.

   If this index.php requires the file ./proyect/folderA/folderB/require1.php , require1.php is a module that uses the folderA path as current directory, not folderB, and it may require something from other folder like ./proyect/folderA/folderC/require2.php so, it will have:

   @code{.php}
   require_once '../folderC/require2.php';
   @endcode

   This won't work... Because the current directory is the one matching the HTTP request virtual folder: ./proyect/folderA/ .

   This is worse when require1.php is required by other PHP web page from other folder: If an HTTP request the http://localhost/index2.php and this index2.php requires ./project/folderA/folderB/require1.php, but require1.php wants the file on `../folderC/require2.php`, wich will points to ./proyect/../folderC/require2.php giving up with an error.

   # Solution
   A mechanism that concatenates relative paths:

   - When ./project/folderA/index.php executes `load("require1.php", "folderB/")` the current path should change to: "./folderB/".
       - When require1.php executes `load("require2.php", "../folderC/")` the current paht should change to: "./folderB/../folderC/"
           - When require2.php finish, the current path should be restored to: "./folderB/" deleting what require1.php stored when loading this file.
       - When require1.php finish, the current path should be restored to : "./" deleting what index.php stored when loading this file.
 */
function load($module, $path=null){
    global $current_path;
    
    if ($path != null){
        // Store the current path before modifying it.
        $previous_path = $current_path;
        $current_path .= $path;
    }

    try{
        
        // Load the module...
        require_once($current_path . $module);
        
    }catch(Exception $e){
        echo "Fatal error:\nCouldn't load in $current_path the module $module\n";        
    }

    if ($path != null){
        // Restore the current path with the previous.
        $current_path = $previous_path;
    }
}
?>
