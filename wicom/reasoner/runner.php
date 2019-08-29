<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   runner.php

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
   I execute the representation and queries into the reasoner.
 */
class Runner {

    /**
       The Connector instance.

       Is where the reasoner is executed.
     */
    protected $connection = null;

    function __construct($connector){
        $this->connection = $connector;
    }

    function run($document = null){
        $this->connection->run($document);
    }

    function get_last_answer(){
        return $this->connection->get_col_answers()[0];
    }

    function get_answers(){
        return $this->connection->get_col_answers();
    }
}

?>
