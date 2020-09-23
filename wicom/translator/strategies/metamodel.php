<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   uml.php

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

namespace Wicom\Translator\Strategies;

use function \load;
load('strategy.php');

abstract class Metamodel extends Strategy{
    /**
       Given a JSON object represeting a KF metamodel, this function encodes such instance into DL.

       @param json_str A String with a diagram representation in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
    */

    abstract function translate($json, $builder);

    abstract function set_check_cardinalities($bool);

    abstract function get_check_cardinalities();

}
?>
