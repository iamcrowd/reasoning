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

    protected const DEFAULT_IRI = "http://fi.uncoma.edu.ar/api#";

    /**
       Does the output JSON have IRIs as names?
     */
    protected $prefix_iris = true;
    
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
            $class_v1 = [
                'attrs' => [],
                'methods' => [],
                'id' => $class['id'],
                'position' => $class['position'],
            ];
            if ($this->prefix_iris){
                $class_v1['name'] = V2toV1::DEFAULT_IRI . $class['name'];
            }else{
                $class_v1['name'] = $class['name'];
            }
            
            $ret[] = $class_v1;
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
        $lastid = 0;
        
        $lst_assocs = $this->input['associations'];
        foreach ($lst_assocs as $assoc){
            $id = '';
            if (array_key_exists('id', $assoc)){
                $id = $assoc['id'];
            }else{
                $id = "c$lastid";
            }

            $link = [
                'multiplicity' => [
                    $assoc['info']['cardDestino'],
                    $assoc['info']['cardOrigin'],
                ],
                'type' => 'association',
                'id' => $id,
                'roles' => [
                    $assoc['info']['roleOrigin'],
                    $assoc['info']['roleDestiny'],
                ],
            ];

            if ($this->prefix_iris) {
                $link['name'] = V2toV1::DEFAULT_IRI .
                                $assoc['info']['nameAssociation'];
                $link['classes'] = [
                    V2toV1::DEFAULT_IRI . $this->id2class[$assoc['source']],
                    V2toV1::DEFAULT_IRI . $this->id2class[$assoc['target']],
                ];
            }else{
                $link['name'] = $assoc['info']['nameAssociation'];
                $link['classes'] = [
                    $this->id2class[$assoc['source']],
                    $this->id2class[$assoc['target']],
                ];
            }

            $links[] = $link;
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
                if ($this->prefix_iris){
                    $children[] = V2toV1::DEFAULT_IRI .
                                  $this->id2class[$child];
                }else{
                    $children[] = $this->id2class[$child];
                }
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

            $gen = [
                'classes' => $children,
                'multiplicity' => null,
                'type' => 'generalization',
                'constraint' => $constraints,
            ];

            if ($this->prefix_iris){
                $gen['name'] = V2toV1::DEFAULT_IRI .
                               $gen2['id'];
                $gen['parent'] = V2toV1::DEFAULT_IRI .
                                 $this->id2class[$gen2['superClasses'][0]];
            }else{
                $gen['name'] = $gen2['id'];
                $gen['parent'] = $this->id2class[$gen2['superClasses'][0]];
            }

            $gens[] = $gen;
        }

        return [
            'classes' => $classes,
            'links' => $gens
        ];
    } // gen

    // doc inherited
    function convert(){
        $classes = $this->classes()['classes'];
        $assocs = $this->associations()['links'];
        $gen = $this->gen()['links'];

        $links = array_merge($gen, $assocs);
        
        return [
            'classes' => $classes,
            'links' => $links,
        ];
    } // convert

    function with_prefix_iris(){
        $this->prefix_iris = true;
    }

    function without_prefix_iris(){
        $this->prefix_iris = false;
    }
    
}

?>
