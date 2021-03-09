<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   uml.php

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

namespace Wicom\Translator\Strategies;

require_once __DIR__ . '/strategy.php';

//use function \preg_match;

abstract class UML extends Strategy{
    /**
       Translate a JSON String into another format depending on
       the given Builder.

       - Each Class is a Class concept in DL.
       - Subclasses just generalize their direct superclasses or TOP.

       @param json_str A String with a diagram representation in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
    */
    function translate($json_str, $builder){

        $json = json_decode($json_str, true);

        $js_clases = $json["classes"];
        $js_links = $json["links"];

        if (!empty($js_clases)){
            foreach ($js_clases as $class){
                // The builder provides a shorthand for this.
                $builder->insert_class_declaration($class["name"]);

            if (!empty($class["attrs"])){
              foreach ($class["attrs"] as $attr){
                $builder->insert_dataproperty_declaration($attr["name"]);
              }
            }
          }

          $gen_array = [];

          if (!empty($json["links"])){
            foreach ($json["links"] as $link){
              switch ($link["type"]){
                case "generalization":
                  array_push($gen_array, $link);
                  break;
                case "association":
                  $builder->insert_objectproperty_declaration($link["name"]);
                  break;
                case "association with class":
                  $builder->insert_objectproperty_declaration($link["roles"][0]);
                  $builder->insert_objectproperty_declaration($link["roles"][1]);
                  break;
                case "n-ary association without class":
                  foreach ($link["roles"] as $role){
                    $builder->insert_objectproperty_declaration($role);
                  }
                  break;
                case "n-ary association with class":
                  foreach ($link["roles"] as $role){
                    $builder->insert_objectproperty_declaration($role);
                  }
                  break;
                }
             }
          }
        }

        $this->translate_attributes($json, $builder);
        $this->translate_links($json, $builder);

      }


    protected abstract function translate_attributes($json, $builder);

    /**
       Translate only the links from a JSON string with links using
       the given builder.
       @param json A JSON object, the result from a decoded JSON
       String.
       @return false if no "links" part has been provided.
    */
    protected abstract function translate_links($json, $builder);


    /**
       Translate an OWLlink into a JSON depending how can the ontology be drawn.

       - Each Class is a Class concept in DL.
       - Subclasses just generalize their direct superclasses or TOP.

       @param owllink_str A XML file.
       @param builder A Wicom\Translator\Builders\JSONBuilder subclass instance.

       @see Translator class for description about the JSON format.

       @todo Here coding a XML parser for ontologies and UML.
    */
    function decode($owllink_str, $jsonbuilder){

    }

    function merge_answer($json_o, $json_new){
      parent::merge_answer($json_o, $json_new);
    }


}
?>
