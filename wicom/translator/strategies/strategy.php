<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   strategy.php

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

require_once __DIR__ . '/../builders/umljsonbuilder.php';

use Wicom\Translator\Builders\UMLJSONBuilder;

/**
   @see Translator class for description about the JSON format.
*/
abstract class Strategy{

    /**
       An instance of any QAPack subclass.

       @see Wicom\Translator\Strategies\QAPackages
     */
    protected $qapack = null;

    function __construct(){
    }

    /**
       Translate the given JSON String into the OWLlink XML string.

       @param json The JSON string
       @param build The Builder instance.
       @return An XML String.

       @see Translator class for description about the JSON format.
     */
    abstract function translate($json, $build);

    abstract function decode($owl, $jsonbuild);

    /**
       Generate queries appropiates for this queries using the Builder to store them in the document.

       @param $json a JSON string representing the user's model.
       @param $builder Wicom\Translator\Builders\DocumentBuilder
    */
    function translate_queries($json, $builder){
        $this->qapack->generate_queries($json, $builder);
    }

    function analize_answer($reasoner_query, $reasoner_answer, $owl2){
        $this->qapack->analize_answer($reasoner_query, $reasoner_answer, $owl2);
        return $this->qapack->get_answer();
    }

    function get_answer(){
        return $this->qapack->get_answer();
    }


    // model.root.uripatternbar = () -> ///^((http|https):\/\/([a-zA-Z0-9\/\.\-\_])+\/([a-zA-Z0-9])+)$///i
    // model.root.uripatternhash = () -> ///^((http|https):\/\/([a-zA-Z0-9\/\.\-\_])+\#([a-zA-Z0-9])+)$///i
    // model.root.prefixpatternname = () -> ///([a-zA-Z0-9])+\:([a-zA-Z0-9])+///i
    /**
    Compare subsumptions in original JSON representing a diagram against new JSON extracted from a realised ontology.

    @return an array of inferred subsumptions. An empty array otherwise.
    */
    function compare_subsumptions($json_o, $json_new, $jsonbuilder){
      // sacar subsumptions de answer. Cada subsumption es [father, childs1, ....., childn]
      // decodificamos json para trabajar con Array
      $subslinks_o = [];
      $subslinks_new = [];

      $subslinks_o = $jsonbuilder->get_product()->get_subs_links($json_o);
      $subslinks_new = $jsonbuilder->get_product()->get_subs_links($json_new);

      $inferred_subs = [];

      foreach ($subslinks_new as $sub_n) {
        $flag = false;
        foreach($subslinks_o as $sub_o){
          if ($jsonbuilder->get_product()->same_subsumption($sub_n, $sub_o)){
            $flag = true;
          }
        }
        if (!$flag){
          array_push($inferred_subs, $sub_n);
        }
      }
      return $inferred_subs;
    }

    /**
    This function gets new "graphicable" constraints (subsumptions, cardinalities) from OWLlink responses already processed

    @param $json_o. Original JSON representing a diagram
    @param $json_new. New diagram extracted by using SPARQL-DL. This json contains the new ontology with "graphicable elements".
    @param $answer. Reasoner output. It contains all the axioms and contraints "graphicable and non-graphicable".
    */
    function merge_answer($json_o, $json_new){
	     $jsonbuilder = new UMLJSONBuilder();
       $inferredSubs = $this->compare_subsumptions($json_o, $json_new, $jsonbuilder);
       $this->qapack->incorporate_inferredSubs($inferredSubs);

    }

}
