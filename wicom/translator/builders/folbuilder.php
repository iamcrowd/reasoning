<?php
/*

Copyright 2016 Giménez, Christian
 
Author: Giménez, Christian

jsonbuilder.php
 
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
load("documents.php");
load("jsonmetadocument.php", "../documents/");

/**
 I set the common behaviour for every DocumentBuilder subclass.

 @abstract
 */

use Wicom\Translator\Documents\JSONMetaDocument;

	
class JsonMetaBuilder extends Documents{
	
	function __construct(){
		$this->product = new JSONMetaDocument;
	}
	
	public function insert_meta_entity(){ 
		$this->product->insert_entity();
		$this->insert_meta_role();
		$this->insert_meta_relationship();
		$this->insert_meta_entityType();
		$this->insert_meta_constraint();
	}

	
	public function insert_meta_role(){
		$this->product->insert_role();
	}
	
	public function insert_meta_relationship(){
		$this->product->insert_relationship();
	}

	public function insert_meta_object($object){
		
		$object->get_json_array(); // ["name" => "objecttypename"]
		
		
	}
	
}
?>