<?php 
/* 

   Copyright 2019 GILIA
   
   Author: GILIA

   uml_converter.php
   
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

namespace Json2Json;

/**
   This class has got the common methods for every UML JSON to JSON converter.
 */
abstract class UMLConverter {

    /**
       @defgroup Convertion Functions
       These functions returns JSON objects (PHP array representations).
     */
    ///@{

    /**
       Convert only the classes.

       @return [array] An array with the decoded JSON.
     */
    abstract function classes();

    /**
       Convert only the associations.
       
       Classes are converted in order to make the conversion consistent. 
       Generalizations are skipped.
       
       @return [array] An array with the decoded JSON.
     */

    abstract function associations();

    /**
       Convert only the generalizations.
       
       Classes are converted too in order to make the conversion consistent. 
       
       @return {array} An array with the decoded JSON.
     */
    abstract function gen();
    
    /**
       Convert all the model completely

       @return [array] A JSON Array.
     */
    abstract function convert();

    ///@}
    
    /**
       @defgroup Convert to JSON 2 String 
       Same as before, but returns a string.
     */

    ///@{
    
    /**
       Convert all generalizations and return it as string.

       @return {string} The encoding of gen()
     */    
    function classes_str(){
        return json_encode($this->classes());
    }
    
    /**
       Convert all generalizations and return it as string.

       @return {string} The encoding of gen()
     */
    function associations_str(){
        return json_encode($this->associations());
    }

    /**
       Convert all generalizations and return it as string.

       @return {string} The encoding of gen()
     */
    public function gen_str(){
        return json_encode($this->gen());
    } // gen_str

    /**
       Get the V1 JSON representation.

       @return {string} The encoding of convert()
    */
    function convert_str(){
        return json_encode($this->convert());
    }
    
    ///@} 
}

?>
