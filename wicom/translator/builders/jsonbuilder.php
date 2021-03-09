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

require_once __DIR__ . '/documents.php';

use Wicom\Translator\Documents\JSONDocument;

/**
 I set the common behaviour for every DocumentBuilder subclass.

 @abstract
 */


abstract class JSONBuilder extends Documents{
	protected $product = null;



	public function decode_DL(){
		
	}

}
?>
