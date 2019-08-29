<?php 
/* 

   Copyright 2016 Giménez, Christian
   
   Author: Giménez, Christian   

   connector.php
   
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

namespace Wicom\Reasoner;

/**
   A reasoner connection representation 
 */
abstract class Connector{

    /**
       The last answers stored from the reasoner.
     */
    protected $col_answers = [];

    function __construct(){
        $this->col_answers = [];
    }
    
    abstract function run($document);

    function reset(){
        $this->col_answers = [];
    }
    
    function get_col_answers(){
        return $this->col_answers;
    }
}

?>
