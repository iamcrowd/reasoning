<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   wicom.php

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

namespace Wicom;

class Wicom{
    function __construct(){
    }

    /**
       Check the diagram represented in JSON format for full reasoning.

       @param $json_str A String with the diagram in JSON format.
       @param $strategy A String representing an specific Description Logic encoding
       @param $reasoner A String with the reasoner name. We support two: Konclude and Racer.

       @return Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer an answer object.
     */
    function full_reasoning($json_str, $reasoner = 'Racer', $check_cards = false, $strategy = 'metamodel'){
    }


    /**
       Check the diagram represented in JSON format for satisfiability.

       @param $json_str A String with the diagram in JSON format.
       @param $strategy A String representing an specific Description Logic encoding
       @param $reasoner A String with the reasoner name. We support two: Konclude and Racer.

       @return Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer an answer object.
     */
    function is_satisfiable($json_str, $strategy = 'crowd', $reasoner = 'Racer'){

    }


    function owl2importer($owl2, $reasoner = 'Racer'){

    }


}

?>
