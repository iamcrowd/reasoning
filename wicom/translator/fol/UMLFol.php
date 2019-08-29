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
                        "pred" => ["name" => $this->objClassAttr, "var" => ["x"]],
                        "predA" => ["name" => $this->objectAttrName, "var" => ["x", "y"]]],
                    "predT" => ["name" => $this->objAttrDataType, "var" => ["y"]]]
        ]];
    }

}

?>

