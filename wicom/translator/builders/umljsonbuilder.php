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
load("umljsondocument.php", "../documents/");

use Wicom\Translator\Documents\UMLJSONDocument;

/**
 I set the common behaviour for every DocumentBuilder subclass.

 @abstract
 */


class UMLJSONBuilder extends Documents{

	public function __construct(){
		$this->product = new UMLJSONDocument();
	}

	public function set_prefixes($prefixes){
		$this->product->set_prefixes($prefixes);
	}

	public function set_ontologyIRI($ontologyIRI){
		$this->product->set_ontologyIRI($ontologyIRI);
	}

	public function insert_class($classname){
		$this->product->insert_class_without_attr($classname);
	}

	public function insert_subsumption($classes, $parent, $constraints){
		$this->product->insert_subsumption($classes, $parent, $constraints);
	}

	public function insert_relationship($classes, $name, $cardinalities = ["0..*","0..*"], $roles = ["",""]){
		if ($roles == ["",""]){
			$roles = [strtolower($classes[0]),strtolower($classes[1])];
		}
		$this->product->insert_relationship($classes, $name, $cardinalities, $roles);
	}

	function insert_withclass_relationship($classes, $name, $assoc_class, $cardinalities = ["0..*","0..*"], $roles = ["",""]){
		if ($roles == ["",""]){
			$roles = [strtolower($classes[0]),strtolower($classes[1])];
		}
		$this->product->insert_withclass_relationship($classes, $name, $cardinalities, $roles);
	}

	public function insert_attribute($attribute, $class, $datatype){
		$this->product->insert_attribute($attribute, $class, $datatype);
	}

	public function get_product(){
			return $this->product;
	}

}
?>
