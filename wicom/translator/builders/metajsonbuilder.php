<?php
/*

Copyright 2018 GILIA

Author: GILIA

umljsonbuilder.php

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

namespace Wicom\Translator\Builders;

use function \load;
load("jsonbuilder.php");
load("metajsondocument.php", "../documents/");

use Wicom\Translator\Documents\MetaJSONDocument;

/**
 I set the common behaviour for every DocumentBuilder subclass.

 @abstract
 */


class MetaJSONBuilder extends Documents{

		public function __construct(){
			$this->product = new MetaJSONDocument;
		}

		public function get_product(){
			return $this->product->get_product();
		}

		public function instantiate_MM($json){
			$this->product->instantiate_MM($json);
		}

		/**
			Insert a subsumtion into the KF instance
		*/
		public function insert_subsumption($child, $parent, $constraints = []){
			return $this->product->insert_subsumption($child, $parent, $constraints = []);
		}

		/**
			Check if a subsumption between both parent and child given as parameters exists in the current KF product
		*/
		public function subsumption_in_instance($child, $parent){
			return $this->product->subsumption_in_instance($child, $parent);
		}



}
?>
