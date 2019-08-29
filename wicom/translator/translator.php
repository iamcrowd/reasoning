<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   translator.php

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

namespace Wicom\Translator;

use function \load;

use function \json_decode;

/**
   I translate a JSON formatted diagram into something else depending on the Builder instance given.

   1. Give a Strategy translator instance for specifying the algorithm for translating the diagram.
   2. Give a Builder for specifying the output format.

   # JSON Format

   We expect the following fields:

   - `classes` : An Array of classes information. Each class should have:
     - `attrs` An array of strings representing all attributes names
     - `methods` An array of strings representing all attributes names
     - `name` A string which represent the name of the class.
   - links : An array of links information. Each link should have:
     - `classes` : An array of strings with the name of the classes involved on the relationship.
     - `multiplicity` : An array of strings with the multiplicity on each class side.
     - `name` : A string with the name of the link.
     - `type` : A string with the type name of the link. Could be: "association", "generalization".

   ## Example
   @code{json}
   {"classes": [
     {"attrs":[], "methods":[], "name": "Person"},
     {"attrs":[], "methods":[], "name": "Cellphones"}],
    "links": [
     {"classes": ["Person", "Cellphones"],
      "multiplicity": ["1..1", "1..*"],
      "name": "hasCellphone",
      "type": "association"}
      ]
   }
   @endcode

 */
class Translator{
    protected $strategy = null;
    protected $builder = null;

    /**
       @todo The translator should add queries?
     */
    protected $with_queries = true;

    function __construct($strategy, $builder){
        $this->strategy = $strategy;
        $this->builder = $builder;
        $this->with_queries = true;
    }

    function set_with_queries($bool){
        $this->with_queries = $bool;
    }

    function get_with_queries(){
        return $this->with_queries;
    }


    function get_ontologyURI_fromNS($json_ns){
      if (array_key_exists("namespaces", $json_ns)) {

        if (array_key_exists("ontologyIRI", $json_ns["namespaces"])) {
          return $json_ns["namespaces"]["ontologyIRI"][0];
        }
        else return [];
      }
      else return [];
    }

    function get_other_URIs_fromNS($json_ns){
      if (array_key_exists("namespaces", $json_ns)) {

        if ((array_key_exists("defaultIRIs", $json_ns["namespaces"])) &&
           (array_key_exists("IRIs", $json_ns["namespaces"]))) {

          $uris = [];
          $uris = array_merge($json_ns["namespaces"]["defaultIRIs"], $json_ns["namespaces"]["IRIs"]);
          return $uris;
        }
        else return [];
      }
      return [];
    }

    /**
       @param json A String.
       @return an XML OWLlink String.
     */
    function to_owllink($json){
        $json_obj = json_decode($json, true);
        $ontoURI = $this->get_ontologyURI_fromNS($json_obj);
        $uris = $this->get_other_URIs_fromNS($json_obj);
        $this->builder->insert_header(true, true, $ontoURI, $uris);
        $this->strategy->translate($json, $this->builder);

        if (array_key_exists("owllink", $json_obj)){
            $this->builder->insert_owllink($json_obj["owllink"]);
        }

        if ($this->with_queries){
            $this->strategy->translate_queries($json, $this->builder);
        }

        $this->builder->insert_footer();
        $document = $this->builder->get_product();
        return $document->to_string();
    }


    /**
       @param json A String.
       @param owllink An Array with OWLlink strings
       @return an XML OWLlink String.
     */
    function importedto_owllink($json, $ontologyIRI, $prefix, $owllink){
        $json_obj = json_decode($json, true);
        $this->builder->insert_header(true, true, $ontologyIRI, [], $prefix);
        $this->strategy->translate($json, $this->builder);

        foreach ($owllink as $elem_owllink){
          $this->builder->insert_owllink($elem_owllink);
        }

        if ($this->with_queries){
            $this->strategy->translate_queries($json, $this->builder);
        }

        $this->builder->insert_footer();
        $document = $this->builder->get_product();
        return $document->to_string();
    }


    /**
    @todo This function should be moved to builder and refactored when
    treatment of non graphical OWL axioms is enhanced
    */
    private function insertedOWLlink2OWLNaive($owllink){
      $str_owl = [];

      $new = preg_replace("/\bowl:SubClassOf\b/","SubClassOf", $owllink);
      $new2 = preg_replace("/\bowl:Class\b/","Class", $new);
      $new3 = preg_replace("/\bowl:ObjectProperty\b/","ObjectProperty", $new2);
      $new4 = preg_replace("/\bowl:ObjectSomeValuesFrom\b/","ObjectSomeValuesFrom", $new3);
      $new5 = preg_replace("/\bowl:ObjectMaxCardinality\b/","ObjectMaxCardinality", $new4);
      $new6 = preg_replace("/\bowl:ObjectMinCardinality\b/","ObjectMinCardinality", $new5);
      $new7 = preg_replace("/\bowl:ObjectInverseOf\b/","ObjectInverseOf", $new6);
      $new8 = preg_replace("/\bowl:EquivalentClasses\b/","EquivalentClasses", $new7);
      $new9 = preg_replace("/\bowl:ObjectIntersectionOf\b/","ObjectIntersectionOf", $new8);
      $new10 = preg_replace("/\bowl:DisjointClasses\b/","DisjointClasses", $new9);
      $new11 = preg_replace("/\bowl:ObjectPropertyDomain\b/","ObjectPropertyDomain", $new10);
      $new12 = preg_replace("/\bowl:ObjectPropertyRange\b/","ObjectPropertyRange", $new11);
      $new13 = preg_replace("/\bowl:DataProperty\b/","DataProperty", $new12);
      $new14 = preg_replace("/\bowl:DataPropertyDomain\b/","DataPropertyDomain", $new13);
      $new15 = preg_replace("/\bowl:DataPropertyRange\b/","DataPropertyRange", $new14);
      $owl_str = preg_replace("/\bowl:SubObjectPropertyOf\b/","SubObjectPropertyOf", $new15);

      return $owl_str;
    }

    /**
       @param json A String.
       @return a XML OWL2 String.
     */
    function to_owl2($json){
        $json_obj = json_decode($json, true);
        $ontoURI = $this->get_ontologyURI_fromNS($json_obj);
        $uris = $this->get_other_URIs_fromNS($json_obj);
        $this->builder->insert_header_owl2($ontoURI, $uris);
        $this->strategy->translate($json, $this->builder);

        if (array_key_exists("owllink", $json_obj) && count($json_obj["owllink"]) != 0){
            $owl_str = $this->insertedOWLlink2OWLNaive($json_obj["owllink"]);
            $this->builder->insert_owl2($owl_str);
        }

        $this->builder->insert_footer();
        $document = $this->builder->get_product();
        return $document->to_string();
    }

}
