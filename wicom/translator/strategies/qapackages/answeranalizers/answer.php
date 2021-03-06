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


require_once __DIR__ . '/../../../builders/owllinkbuilder.php';
require_once __DIR__ . '/../../../builders/documentbuilder.php';
require_once __DIR__ . '/../../../builders/owlbuilder.php';

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
    protected $disjunctions = [];
    protected $reasoner_input = null;
    protected $reasoner_output = null;
    protected $new_owl2 = null;
    protected $orig_owl2 = null;
    protected $inferredSubs = [];
    protected $inferredCards = [];
    protected $inferredDisj = [];
    protected $inferredEquivs = [];

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

    function add_equivalences($equivalences_n){
	foreach($equivalences_n as $e){
            array_push($this->equivalences, $e);
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
	$this->new_owl2->translate_DL($dl_responses);
    }

    function copyowl2_to_response(){
	$owl_xml = new SimpleXMLIterator($this->orig_owl2);
	$owl_xml->rewind();

	foreach ($owl_xml->children() as $child){
            $this->new_owl2->insert_owl2($child->asXML());
	}
    }

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

    function get_unsatClasses(){
	return $this->unsatis_classes;
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
             "equivalences" => $this->equivalences,
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
}
