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

# require_once '../common/import_functions.php';
use function \load;

load('uml_converter.php');

use function \json_decode;

/**
   Implements the conversion between JSON version 1 to the new version 2.
 */
class V1toV2 extends UMLConverter{

    /**
       The input as a JSON parsed string. 

       It must be a V1 JSON.
     */
    protected $input  = null;

    /**
       Mapping between the class names and their ids.
     */
    protected $class2id = [];

    /**
       Create a new instance.

       @param $input [string] A JSON string.
     */
    function __construct($input=''){
        $this->input = json_decode($input, true);

        $this->map_id_with_class();
    }

    /**
       Associate each class name with its own id.

       If the id is not in the V1 JSON, create a new one.
     */
    protected function map_id_with_class(){
        $this->class2id = [];
        $lastid = 0;
        
        $lst_classes = $this->input['classes'];

        foreach ($lst_classes as $class1){
            // Use the id if exists, else create a new one.
            $name = $class1['name'];
            $id = 0;
            if (array_key_exists('id', $class1)) {
                $id = $class1['id'];
            }else{
                $id = "c$lastid";
                $lastid ++;
            }
            $this->class2id[$name] = $id;
        }   
    } // map_id_with_class

    /**
       Convert only the classes.
     */
    function classes(){
        $ret = [];
        $classes = $this->input['classes'];

        foreach ($classes as $class1){
            $class2 = [
                'attributes' => $class1['attrs'],
                'methods' => $class1['methods'],
                'name' => $class1['name'],
                'id' => $this->class2id[$class1['name']],
                /*
                   'position' => [
                   'x' : $x,
                   'y' : $y,
                   ],*/
                'size' => [
                    'height' => 80,
                    'width' => 105,
                ],
            ];
            
            $ret[] = $class2;
        }

        return [
            'associationWithClass' => [],
            'associations' => [],
            'classes' => $ret,
            'inheritances' => [],
        ];
    }

    function associations(){
        $ret = [];
        $lst_assocs = $this->input['links'];
        $lastid = 0;
        
        foreach ($lst_assocs as $assoc1){
            // Only convert binary associations
            // Type: associations (not generalizations, etc.)
            // Associations with only 2 classes.
            if ($assoc1['type'] == 'association' &&
                sizeof($assoc1['classes']) == 2){

                // For id, use the one defined on the association if it exists.
                // Else create one.
                $id = 0;
                if (array_key_exists('id', $assoc1)){
                    $id = $assoc1['id'];
                }else{
                    $id = "c$lastid";
                    $lastid++;
                }

                
                $assoc2 = [
                    'id' => $id,
                    'info' => [
                        'cardDestino' => $assoc1['multiplicity'][0],
                        'cardOrigin' => $assoc1['multiplicity'][1],
                        'nameAssociation' => $assoc1['name'],
                        'roleDestiny' => '',
                        'roleOrigin' => '',
                    ],
                    'source' => $this->class2id[$assoc1['classes'][0]],
                    'target' => $this->class2id[$assoc1['classes'][1]],
                    'type' => 'binaryAssociation',
                ];
                
                $ret[] = $assoc2;
            }
        }

        $classes = $this->classes()['classes'];
        
        return [
            'associationWithClass' => [],
            'associations' => $ret,
            'classes' => $classes,
            'inheritances' => [],
        ];
    }
    
    function gen(){
    }

    function convert(){
    }

    
}

?>
