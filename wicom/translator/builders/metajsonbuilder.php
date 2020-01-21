<?php
/*

Copyright 2020 GILIA

Author: GILIA

metajsonbuilder.php

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


class MetaJSONBuilder extends Documents{

	public function __construct(){
		$this->product = new MetaJSONDocument();
	}

	public function insert_object_type($otname){
		$this->product->insert_object_type($otname);
	}

	public function insert_subsumption($parent, $child, $id, $compl = "", $disj = ""){
		$this->product->insert_subsumption($parent, $child, $id, $compl, $disj);
	}

	public function get_product(){
			return $this->product;
	}

}
?>
