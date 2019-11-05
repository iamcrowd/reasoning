<?php
/* 

   Copyright 2019 GILIA
   
   Author: GILIA

   v2tov1.php
   
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
   Implements the conversion between JSON version 2 to the new version 1.
 */
class V2toV1 extends UMLConverter{

    /**
       The input as a JSON parsed string. 

       It must be a V2 JSON.
     */
    protected $input  = null;

    /**
       Mapping between class names and their ids.
     */
    protected $id2class = [];

    /**
       Create a new instance.

       @param $input [string] A JSON string.
     */
    function __construct($input=''){
        $this->input = json_decode($input, true);

        $this->map_class_with_ids();
    } // __constructs

    /**
       Generate mappings between classes and ids.

       Create the id2class mapping for converting an id into a class easily.
    */
    protected function map_class_with_ids(){
        $this->id2class = [];
        $lst_classes = $this->input['classes'];
        
        foreach ($lst_classes as $class){
            $this->id2class[$class['id']] = $class['name'];
        }
    } // map_class_with_ids

    // doc inherited
    function classes(){
        $ret = [];
        
        $lst_classes = $this->input['classes'];
        foreach ($lst_classes as $class){
            $ret[] = [
                'attrs' => [],
                'methods' => [],
                'name' => $class['name'],
                'id' => $class['id']
            ];
        }

        return [
            'classes' => $ret,
            'links' =>  []
        ];
    } // classes

    // doc inherited
    function associations(){
        $classes = $this->classes()['classes'];
        $links = [];
        
        $lst_assocs = $this->input['associations'];
        foreach ($lst_assocs as $assoc){
            $links[] = [
                'classes' => [
                    $this->id2class[$assoc['source']],
                    $this->id2class[$assoc['target']],
                ],
                'multiplicity' => [
                    $assoc['info']['cardDestino'],
                    $assoc['info']['cardOrigin'],
                ],
                'name' => $assoc['info']['nameAssociation'],
                'type' => 'association'
            ];
        }

        return [
            'classes' => $classes,
            'links' => $links
        ];
    } // associations

    // doc inherited
    function gen(){
        $classes = $this->classes()['classes'];
        $gens = [];

        $lst_gens2 = $this->input['inheritances'];
        foreach ($lst_gens2 as $gen2){
            // Convert each child id into its name.
            $children = [];
            foreach ($gen2['subClasses'] as $child){
                $children[] = $this->id2class[$child];
            }

            // Set constraint
            $constraints = [];
            if ($gen2['type'] == 'd'){
                $constraints = ['disjoint'];
            }else if ($gen2['type'] == 'c'){
                $constraints = ['covering'];
            }else if ($gen2['type'] == 'c/d') {
                $constraints = ['covering', 'disjoint'];
            }
            
            $gens[] = [
                'classes' => $children,
                'multiplicity' => null,
                'name' => $gen2['id'],
                'type' => 'generalization',
                'parent' => $this->id2class[$gen2['superClasses'][0]],
                'constraint' => $constraints,
            ];
        }

        return [
            'classes' => $classes,
            'links' => $gens
        ];
    } // gen

    // doc inherited
    function convert(){
        
    } // convert
    
}

?>
