<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian. Braun, Germán

   answer.php

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

namespace Wicom\Translator\Strategies\QAPackages\AnswerAnalizers;


load("owllinkbuilder.php", "../../../builders/");
load("documentbuilder.php", "../../../builders/");
load("owlbuilder.php", "../../../builders/");

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\DocumentBuilder;
use \XMLReader;
use \SimpleXMLElement;
use \SimpleXMLIterator;
use \XMLWriter;

/**
   A reasoner answer summary.

   # Why there's satisfiable classes and unsatisfiable too?
   We need both, despite you may think that the complement of one set
   is the other.

   Maybe, the GUI won't have all the set of classes, or maybe one
   class is a suggestion. The GUI is responsible to use one set,
   another or both depending on its needs.

   So, for the purpose of being a bit RESTfull, it is good to have
   both sets despite all.
 */
class Answer{

    protected $kb_satis = null;
    protected $satis_classes = [];
    protected $unsatis_classes = [];
    protected $satis_op = [];
    protected $unsatis_op = [];
    protected $satis_dp = [];
    protected $unsatis_dp = [];
    protected $subsumptions = [];
    protected $equivalences = [];
    protected $equivalences_op = [];
    protected $equivalences_dp = [];
    protected $disjunctions = [];
    protected $disjunctions_op = [];
    protected $disjunctions_dp = [];
    protected $reasoner_input = null;
    protected $reasoner_output = null;
    protected $new_owl2 = null;
    protected $orig_owl2 = null;
    protected $inferredSubs = [];
    protected $inferredCards = [];
    protected $inferredDisj = [];
    protected $inferredEquivs = [];
    protected $beauty_responses = null;
    protected $stricter_cardinalities = [];

    function __construct($builder){
        $this->new_owl2 = $builder;
    }

    function set_kb_satis($bool){
        $this->kb_satis = $bool;
    }

    function add_satis_class($classname){
        array_push($this->satis_classes, $classname);
    }

    function add_unsatis_class($classname){
        array_push($this->unsatis_classes, $classname);
    }

    function add_satis_op($opname){
        array_push($this->satis_op, $opname);
    }

    function add_unsatis_op($opname){
        array_push($this->unsatis_op, $opname);
    }

    function add_satis_dp($dpname){
        array_push($this->satis_dp, $dpname);
    }

    function add_unsatis_dp($dpname){
        array_push($this->unsatis_dp, $dpname);
    }

    function add_beauty_responses($dl_like){
      $this->beauty_responses = $dl_like;
    }

    /**
       Add a subsumption/generalization suggestion.

       @param $name {string}
       @param $children {array} An array of string class names.
       @param $parent {string}
       @param $restriction {array} An array of restrictions. For example:
       `['total', 'disjoint']`
     */
    function add_subsumption($name, $children, $parent, $restrictions){
	     $this->subsumptions[] = [
	    "name" => $name,
	    "classes" => $children,
	    "multiplicity" => null,
	    "roles" => [null, null],
	    "type" => "generalization",
	    "parent" => $parent,
	    "constraint" => $restrictions
	   ];
    }

    function add_subsumptions($subsumptions_n){
	     foreach($subsumptions_n as $s){
            array_push($this->subsumptions, $s);
	         }
    }

    /**
       Add a disjoint relationship between concepts/classes.

       @param $name {string} The relationship name.
       @param $classes {array} An array of strings with class names.
       For example: `['class1', 'class2']`
    */
    function add_disjoint($name, $classes){
	     $this->disjunctions[] = [
	        "type" => "disjoint",
	        "name" => $name,
	        "classes" => $classes
	       ];
    }

    function add_disjunctions($disjunctions_n){
	     foreach($disjunctions_n as $d){
            array_push($this->disjunctions, $d);
	     }
    }

    function add_disjunctions_op($disjunctions_n){
       foreach($disjunctions_n as $d){
            array_push($this->disjunctions_op, $d);
       }
    }

    function add_disjunctions_dp($disjunctions_n){
       foreach($disjunctions_n as $d){
            array_push($this->disjunctions_dp, $d);
       }
    }

    function add_equivalences($equivalences_n){
	     foreach($equivalences_n as $e){
            array_push($this->equivalences, $e);
	     }
    }

    function add_equivalences_op($equivalences_n){
	     foreach($equivalences_n as $e){
            array_push($this->equivalences_op, $e);
	     }
    }

    function add_equivalences_dp($equivalences_n){
       foreach($equivalences_n as $e){
            array_push($this->equivalences_dp, $e);
       }
    }

    function add_stricter_cardinalities($stricter_cardinalities){
      foreach($stricter_cardinalities as $e){
           array_push($this->stricter_cardinalities, $e);
      }
    }

    function add_cardinality_link_sugges($linkname, $col_classnames, $multiplicity,$roles){
        array_push($this->graph_links_sugges,
                   ["name" => $linkname,
                    "classes" => $col_classnames,
                    "multiplicity" => $multiplicity,
                    "roles" => $roles,
                    "type" => "association"]);
    }


    function set_reasoner_input($input_str){
        $this->reasoner_input = $input_str;
    }
    function set_reasoner_output($output_str){
        $this->reasoner_output = $output_str;
    }

    function set_original_owl2($owl2_srt){
	     $owl = new OWLBuilder();
	      $owl->insert_owl2($owl2_srt);
	       $xml = $owl->get_product();
	        $this->orig_owl2 = $xml->to_string();
    }

    function get_new_owl2(){
	     return $this->new_owl2->get_product(true);
    }

    function start_owl2_answer($ontologyIRI, $iris, $prefixes){
	     $this->new_owl2->insert_header_owl2($ontologyIRI, $iris, $prefixes);
    }

    function end_owl2_answer(){
	     $this->new_owl2->insert_footer();
    }

    function translate_responses($dl_responses){
//	     $this->new_owl2->translate_DL($dl_responses);
    }

    function copyowl2_to_response(){
	     $owl_xml = new SimpleXMLIterator($this->orig_owl2);
	      $owl_xml->rewind();

	       foreach ($owl_xml->children() as $child){
            $this->new_owl2->insert_owl2($child->asXML());
	         }
    }

    /**
      Get Equivalent Classes from Answer
    */
    function get_equiv($primitive){
	     $arr_eq = [];
	     $meqs = [];

	     foreach ($this->equivalences as $e){
            $eqs = [];

            if (in_array($primitive, $e)){
		             $eqs = array_filter($e, function($v, $primitive) {
                  if (strcmp($v, $primitive) !== 0)
                        return $v;
                }, ARRAY_FILTER_USE_BOTH);
		        $meqs = array_merge($arr_eq, $eqs);
            }
	      }
	       return $meqs;
    }

    /**
      Get Disjoint Classes from Answer
    */
    function get_disjoint_classes(){
	     return $this->disjunctions;
    }

    // $answer->incorporate_inferredSubs();
    function incorporate_inferredSubs($infSubs){
	     $this->inferredSubs = $infSubs;
    }

    // $answer->incorporate_inferredCards();
    function incorporate_inferredCards($infCards){
	     if (count($infCards) > 0){
            $this->inferredCards = $infCards;
	         }
    }

    // $answer->incorporate_inferredDisj();
    function incorporate_inferredDisj($infDisj){
	     $this->inferredDisj = $infDisj;
    }

    // $answer->incorporate_inferredEquiv();
    function incorporate_inferredEquivs($infEquivs){
	     $this->inferredEquivs = $infEquivs;
    }


    /**
       The string generated by to_json() is like the following.

       @code{.json}
       {
       "satisfiable": {
       "kb" : true,
       "classes" : ["name1", "name2"]
       },
       "unsatisfiable": {
       "classes" : ["name3", "name4"]
       },
       "suggestions" : {
       "links" : [
       {"name" : "suggestion 1",
       "classes": ["classname 1", "classname 2"]}
       ]
       },
       "reasoner" : {
       "input" : "STRING WITH REASONER INPUT",
       "output" : "STRING WITH REASONER OUTPUT"
       }
       }
       @endcode
     */
    function to_json(){
        return json_encode(
            ["satisfiable" => [
                "kb" => $this->kb_satis,
                "classes" => $this->satis_classes,
                "objectproperties" => $this->satis_op,
                "dataproperties" => $this->satis_dp],
             "unsatisfiable" => [
                 "classes" => $this->unsatis_classes,
                 "objectproperties" => $this->unsatis_op,
                 "dataproperties" => $this->unsatis_dp],
             "subsumptions" => $this->subsumptions,
             "disjunctions" => $this->disjunctions,
             "disjunctions_op" => $this->disjunctions_op,
             "disjunctions_dp" => $this->disjunctions_dp,
             "equivalences" => $this->equivalences,
             "equivalences_op" => $this->equivalences_op,
             "equivalences_dp" => $this->equivalences_dp,
             "stricter_cardinalities" => $this->stricter_cardinalities,
             "reasoner" => [
                 "input" => $this->reasoner_input,
                 "output" => $this->reasoner_output,
                 // "owl2" => $this->get_new_owl2()->to_string()
	     ],
             "inferredSubs" => $this->inferredSubs,
             "inferredCards" => $this->inferredCards,
             "inferredDisj" => $this->inferredDisj,
             "inferredEquiv" => $this->inferredEquivs,

            ]
        );
    }

    /**
      This function returns a beautified json with OWLlink responses.
      "beauty_responses" is an array of elements (DL axioms-like) together with its related equivalent, disjoint or sub/super class

      @// NOTE:
          {
            "subclass": [
              {
                "class": "http://crowd.fi.uncoma.edu.ar/kb1#F"
              },
              {
                "class": "http://crowd.fi.uncoma.edu.ar/kb1#C"
              }
            ]
          },
    */
    function to_beatified_json(){
        return json_encode(
            ["satisfiable" => [
                "kb" => $this->kb_satis,
                "classes" => $this->satis_classes,
                "objectproperties" => $this->satis_op,
                "dataproperties" => $this->satis_dp],
             "unsatisfiable" => [
                 "classes" => $this->unsatis_classes,
                 "objectproperties" => $this->unsatis_op,
                 "dataproperties" => $this->unsatis_dp],
              "beauty_responses" => $this->beauty_responses,
            ]
        );
    }


    /**
      This functions return specific axioms from the beauty responses
    */

    function get_kb_status(){
      return $this->kb_satis;
    }

    function get_unsatClasses(){
       return $this->unsatis_classes;
    }

    function get_satClasses(){
       return $this->satis_classes;
    }

    function get_unsatObjectProperties(){
       return $this->unsatis_op;
    }

    function get_satObjectProperties(){
       return $this->satis_op;
    }

    function get_all_classes(){
      return $all = \array_merge_recursive($this->satis_classes, $this->unsatis_classes);
    }

    /**
      Get a subclass given a class as father
    */
    function get_subclass($class){
      $all_sub = [];
      foreach ($this->beauty_responses as $el) {
        if (array_key_exists('subclass', $el)){
          if (
              (array_key_exists('class', $el["subclass"][0])) &&
              (array_key_exists('class', $el["subclass"][1]))
             ){
               if (\strcmp($el["subclass"][1]["class"],$class) == 0){
                 array_push($all_sub, $el["subclass"][0]["class"]);
              }
          }
        }
      }
      return $all_sub;
    }

    /**
      Get the respective disjoint for the class given as parameter
    */
    function get_disjoint_class($class){
      $all_disj = [];
      foreach ($this->beauty_responses as $el) {
        if (array_key_exists('disjointclasses', $el)){
          $aux = "";

          if (\strcmp($el["disjointclasses"][0]["class"],$class) == 0){
            $aux = $el["disjointclasses"][1]["class"];

          } elseif (\strcmp($el["disjointclasses"][1]["class"],$class) == 0) {
              $aux = $el["disjointclasses"][0]["class"];
          }

          if (\strcmp($aux,"") !== 0){
            if (!\in_array($aux, $all_disj)){
              array_push($all_disj, $aux);
            }
          }
        }
      }
      return $all_disj;
    }

    /**
      Get all disjoint class axioms

      @// NOTE: this function do not remove axioms so that both disjoint(A,B) and disjoint(B,A) are kept.
    */
    function get_all_disjoint_class(){
      $all_disj = [];
      $classes = $this->get_all_classes();

      foreach ($classes as $sc_el) {
        $disj_of_sc = [];
        $disj_of_sc = $this->get_disjoint_class($sc_el);

        if (\count($disj_of_sc) !== 0){
          $aux = [];
          foreach ($disj_of_sc as $d) {
              \array_push($all_disj, [$sc_el, $d]);
          }
        }
      }
      return $all_disj;
    }

    /**
      Get the respective equivalent for the class given as parameter.

      @// NOTE: this function does not consider axioms as equivalent(D, D)
    */
    function get_equivalent_class($class){
      $all_equiv = [];
      foreach ($this->beauty_responses as $el) {
        if (array_key_exists('equivalentclasses', $el)){
          $aux = "";

          if (\strcmp($el["equivalentclasses"][0]["class"],$el["equivalentclasses"][1]["class"]) !== 0){

            if (\strcmp($el["equivalentclasses"][0]["class"],$class) == 0){
              $aux = $el["equivalentclasses"][1]["class"];

            } elseif (\strcmp($el["equivalentclasses"][1]["class"],$class) == 0) {
                $aux = $el["equivalentclasses"][0]["class"];
            }

            if (\strcmp($aux,"") !== 0){
              if (!\in_array($aux, $all_equiv)){
                array_push($all_equiv, $aux);
              }
            }
          }
        }
      }
      return $all_equiv;
    }

    /**
      Get all equivalent class axioms

      @// NOTE: axioms like equivalent(D,C) and equivalent(C,D)  represents the very same axioms in DL so that one of them is removed
    */
    function get_all_equiv_class(){
      $all_equiv = [];
      $classes = $this->get_all_classes();

      foreach ($classes as $sc_el) {
        $equiv_of_sc = [];
        $equiv_of_sc = $this->get_equivalent_class($sc_el);

        if (\count($equiv_of_sc) !== 0){
          $aux = [];
          foreach ($equiv_of_sc as $e) {

            if ((!\in_array([$sc_el, $e], $all_equiv)) && (!\in_array([$e, $sc_el], $all_equiv))){
              \array_push($all_equiv, [$sc_el, $e]);
            }
          }
        }
      }
      return $all_equiv;
    }

    function get_stricter_cardinalities(){
      return $this->stricter_cardinalities;
    }

    /**
    $el = [
                    ["subclass" => [
                      ["class" => $role["class"]],
                      ["maxcard" => [
                                    $a_responses[$i]["query card"],
                                    ["inverse" => ["role" => $role["op"]]],
                                    ["filler" => $role["rel"]]
                                    ]
                      ]
                    ]
                  ]
                ];
    */
    function get_classOfStricter($stricter_el){
      return $stricter_el["subclass"][0]["class"];
    }

    function get_opOfStricter($stricter_el){
      return $stricter_el["subclass"][1]["maxcard"]["filler"];
    }

    function get_roleOfStricter($stricter_el){
      return $stricter_el["subclass"][1]["maxcard"][1]["inverse"]["role"];
    }

    function get_maxOfStricter($stricter_el){
      return $stricter_el["subclass"][1]["maxcard"][0];
    }



    public function responses_to_json($dl_responses){
      return json_encode($dl_responses);
    }

}
