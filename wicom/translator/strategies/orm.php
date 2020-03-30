<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   orm.php

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

use function \load;
load('strategy.php');

abstract class ORM extends Strategy{
    function translate($json_str, $builder)
	{
		//print_r("@@@ Dentro de TRANSLATE DEFINIDO EN ORM.PHP Y EXTENDIDO POR Crows_orm_strat.php.PHP@@@");
		$json = json_decode($json_str, true);

        $js_entities = $json["entities"];
        $js_connectors = $json["connectors"];

        if (!empty($js_entities)){
          foreach ($js_entities as $entity){
            $builder->insert_class_declaration($entity["name"]);

            if ($entity["ref"]!=""){
				$builder->insert_dataproperty_declaration($entity["ref"]);
            }
          }

          $gen_array = [];

          if (!empty($js_connectors)){
            foreach ($js_connectors as $connector){
              switch ($connector["type"]){
                case "subtyping":
                  array_push($gen_array, $connector);
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
