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

    abstract function classes();
    function classes_str(){
        return json_encode($this->classes());
    }

    abstract function associations();
    function associations_str(){
        return json_encode($this->associations());
    }
}

?>
