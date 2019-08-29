<?php
/*

Copyright 2017

Grupo de Investigación en Lenguajes e Inteligencia Artificial (GILIA) -
Facultad de Informática
Universidad Nacional del Comahue

objecttype.php

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

namespace Wicom\Translator\Fol;
//use function \load;
//load('entitytype.php');

class ClassPredicate {

    protected $ClassName = "";

    /**
      UML: class
      EER: entity type
      ORM: non-lexical object type / entity type
      for Object subtype, there is the naming, respectively:
      subclass (UML)
      subtype (EER)
      subtype (ORM)

     * @param $name object type name
     */
    function __construct($name) {

        $this->ClassName = $name;
    }

    function get_json_array() {
        /* Empezar a modificar éstas cosass!
          {
          "forall": [{
          "var": "x"
          }, {
          "pred": [{
          "name": "A",
          "varp": "x"
          }]
          }]
         * 
         * {
          "forall": {
          "var": "x",
          "pred": {
          "name": "Student",
          "varp": "x"
          }
          }
          }
         * } */

        return ["forall" => [
                "var" => "x",
                "pred" => [
                    "name" => $this->ClassName, "varp" => ["x"]]]];
    }

}

class IsAPredicate {

    protected $parentName = "";
    protected $child = "";
    

    /**
      UML: class
      EER: entity type
      ORM: non-lexical object type / entity type
      for Object subtype, there is the naming, respectively:
      subclass (UML)
      subtype (EER)
      subtype (ORM)

     * @param $name object type name
     */
    function __construct($parent, $child) {

        $this->parentName = $parent;
        $this->child = $child;
        
    }

    function get_json_array() {
        /* Empezar a modificar éstas cosass!
          {
          "forall": [{
          "var": "x",
          "imply": [{
          "pred": [{
          "name": "A",
          "varp": "x"
          }],
          "predB": [{
          "name": "B",
          "varp": "x"
          }]
          }]
          }]
         * 
         * {"forall":{"var":"x","imply":{"pred":{"name":"Hola","varp":"x"},"predB":{"name":"Papu","varp":"x"}}}}
          }
         * } */

        return ["forall" => [
                "var" => "x", //Preguntar si debe ser arreglo
                "imply" => [
                    "pred" => ["name" => $this->child, "varp" => ["x"]],
                    "predB" => ["name" => $this->parentName, "varp" => ["x"]]]
        ]];
    }
    
    
}

class IsAConstraints {
    protected $parent= "";
    protected $constraints="";
    protected $children="";
    
    function __construct($constrains,$parent, $children) {
        $this->constrains=$constrains;
        $this->parent=$parent;       
        $this->children=$children;
    }
        
        function get_json_array() {
            $fol;
            $disjoint_fol=[];
            $covering_fol=[];
            
            foreach ($this->constrains as $constraint){
                if($constraint=="disjoint"){                 
                    $rest= array_merge($this->children,[]);
                    array_shift($rest);
                    
                    foreach($this->children as $child){
                        $this->analizeDisjointConstraint($child,$rest,$disjoint_fol);
                        array_shift($rest);
                    }
                    $disjoint_fol=["disjoint"=>$disjoint_fol];
                }
                else{//covering
                    $this->analizeCoveringConstraint($this->parent, $this->children, $covering_fol);
                    $covering_fol=["covering"=>$covering_fol];
                }
            }
            
            $fol= array_merge($disjoint_fol,$covering_fol);
            return $fol;
    }
    
    private function analizeDisjointConstraint($class, $rest,&$fol) {
        foreach ($rest as $classB) {
            if ($class != $classB) {
                $disjoint_pred = [
                    "forall" => [
                        "var" => ["x"],
                        "imply" => [
                            "pred" => [
                                "name" => $class,
                                "varp" => ["x"]
                            ],
                            "neg" => [
                                "pred" => [
                                    "name" => $classB,
                                    "varp" => "x"
                                ]
                            ]
                        ]
                    ]
                ];
                array_push($fol,$disjoint_pred);      
            }
        }
    }
    
    private function analizeCoveringConstraint($parent, $children, &$fol) {
        $classes_or = [];

        foreach ($children as $child) {

            array_push($classes_or, ["pred" => [
                    "name" => $child,
                    "var" => ["x"]
            ]]);
        }

        $covering_fol = ["forall" => [
                "var" => ["x"],
                "imply" => [
                    "pred" => ["name" => $parent],
                    "or" => $classes_or
                ]
        ]];

        array_push($fol, $covering_fol);
    }

}

class Attribute {

    protected $objClassAttr = "";
    protected $objectAttrName = "";
    protected $objAttrDataType = "";

    /**
      UML: attribute
      EER: attribute, but without including a data type in the diagram
      ORM: absent (represented differently)
     */
    function __construct($classname, $attrname, $datatype) {

        $this->objClassAttr = $classname;
        $this->objectAttrName = $attrname;
        $this->objAttrDataType = $datatype;
    }

    function get_json_array() {
        /* {
          "forall": [{
          "var": ["x", "y"],
          "imply": [{
          "and": [{
          "pred": [{
          "name": "C",
          "var": ["x"]
          }],
          "a": [{
          "var": ["x", "y"]
          }]
          }],
          "pred": [{
          "name": "T",
          "var": ["y"]
          }]
          }]
          }]
          } */

        return ["forall" => [
                "var" => ["x", "y"],
                "imply" => [
                    "and" => [
                        "pred" => ["name" => $this->objClassAttr, "varp" => ["x"]],
                        "predA" => ["name" => $this->objectAttrName, "varp" => ["x", "y"]]],
                    "predT" => ["name" => $this->objAttrDataType, "varp" => ["y"]]]
        ]];
    }

}

class Association{
    protected $class1="";
    protected $class2="";
    protected $mult1="";
    protected $mut2="";
    protected $name_association="";
    
    protected $class_association="";
    
    
    function __construct($classes,$multiplicities, $name_association,$class_association) {
        /* 
        mult1 es de tipo 0...*
         *          */
        $this->class1 = $classes[0];
        $this->class2 = $classes[1];
        $this->mult1 = $multiplicities[0];
        $this->mult2 = $multiplicities[1];
        $this->name_association = $name_association;
        
        $this->class_association=$class_association;
    }
    
    function get_json_array(){
        $multiplicity1= $this->analize_multiplicity($this->mult1);
        $multiplicity2= $this->analize_multiplicity($this->mult2); 
        
        
        
        return [["forall" => [
                "var" => ["x", "y"],
                "imply" => [
                    "pred" => ["name" => $this->name_association, "varp" => ["x","y"]],
                    "and" => [
                        "pred" => ["name" => $this->class1 , "varp" => ["x"]],
                        "predB" => ["name" => $this->class2 , "varp" => ["y"]]
                    ]
                 ]
            ]],
            ["forall" => [
                "var" => ["x"],
                "imply" => [
                    "pred" => ["name" => $this->class1, "varp" => ["x"]],
                    "multiplicity" => [
                        "min" => $multiplicity2[0],
                        "#" => ["var" => ["y"],
                                "pred" => ["name" => $this->name_association, "varp" => ["x","y"]]],
                        "max" => $multiplicity2[1]
                        ]
                 ]
            ]
            ],
            ["forall" => [
                "var" => ["x"],
                "imply" => [
                    "pred" => ["name" => $this->class2, "varp" => ["y"]],
                    "multiplicity" => [
                        "min" => $multiplicity1[0],
                        "#" => ["var" => ["x"],
                                "pred" => ["name" => $this->name_association, "varp" => ["x","y"]]],
                        "max" => $multiplicity1[1]
                        ]
                 ]
            ]
            ]];
        
    }
    
    function analize_multiplicity($multiplicity){
        // $multiplicity="a..b"     a=0,1,2....n    b=1....n
        $ret=[];
        
        /*   "name": "estudia",
            "classes": ["Persona", "Carrera"],
            "multiplicity": ["1..*", "1.."],
            "roles": [null, null],
            "type": "association"*/
        
        switch($multiplicity){
        case "1..1":
            $ret = ["1","1"];
            break;
        case "0..1":
            $ret = ["0","1"];
            break;            
        case "1..*":
        case "1..n":
            $ret = ["1","*"]; //preguntar ésto despúes
            break;
        case "0..*":            
        case "0..n":
            $ret = ["0","*"]; 
            break;
        }
        
        return $ret;
        
    }
}
    
 
?>

