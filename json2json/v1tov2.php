<?php
/* 

   Copyright 2019 GILIA
   
   Author: GILIA

   v1tov2.php
   
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

require_once '../common/import_functions.php';
use function \load;

use function \json_decode;

/**
   Implements the conversion between JSON version 1 to the new version 2.
*/
class V1toV2{

    /**
       The input as a JSON parsed string. 

       It must be a V1 JSON.
    */
    protected $input  = null;

    /**
       Create a new instance.

       @param $input [string] A JSON string.
    */
    function __construct($input=''){
        $this->input = json_decode($input, true);
    }

    /**
       Convert only the classes.
    */
    function classes(){
        
    }
    
}

?>
