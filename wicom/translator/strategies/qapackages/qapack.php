<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   qapack.php

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

namespace Wicom\Translator\Strategies\QAPackages;

/**
Abstract class for a pack of Queries generators and Answer analizer.

Each subclass is a Q/A Pack and must have one Queries generator and its respective Answer analizer.

# Subclass Implementation

* The constructor must create the queries generator and the answer analizer instances.
*/
abstract class QAPack{
    /**
       Query generator object.

       A subclass of QueryGenerator.
     */
    protected $query_generator = null;
    /**
       Answer analizer object.

       A subclass of AnswerAnalizer.
     */
    protected $ans_analizer = null;


    /**

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder a Wicom\Translator\Builders\DocumentBuilder instance.

     */
    function generate_queries($el_toQuery, $builder){
        $this->query_generator->generate_all_queries($el_toQuery, $builder);
    }

    /**
       Analize the reasoner results. Use get_answer() for retrieving the answer.

       @param $reasoner_query A String with the reasoner input (query).
       @param $reasoner_answer A String with the reasoner output (answer).
       @param $owl2 A String with the original OWL 2 ontology (owl2)
    */
    function analize_answer($reasoner_query, $reasoner_answer, $owl2 = ""){
        $this->ans_analizer->generate_answer($reasoner_query, $reasoner_answer, $owl2);
        $this->ans_analizer->analize();
    }

    public function get_query_generator(){
      return $this->query_generator;
    }

    public function get_ans_analizer(){
      return $this->ans_analizer;
    }

	  function incorporate_inferredSubs($inferredSubs){
		   $this->ans_analizer->incorporate_inferredSubs($inferredSubs);
	  }

    function incorporate_inferredCards($inferredCards){
      $this->ans_analizer->incorporate_inferredCards($inferredCards);
    }


    // Next function works on beauty responses TODO: document them!
    public function get_equiv($primitive){
      return $this->ans_analizer->get_equiv($primitive);
    }

    public function get_all_equiv_class(){
      return $this->ans_analizer->get_all_equiv_class();
    }

    public function get_disjoint_class($class){
      return $this->ans_analizer->get_disjoint_class();
    }

    public function get_all_disjoint_class(){
      return $this->ans_analizer->get_all_disjoint_class();
    }

    public function get_subclass($parent){
      return $this->ans_analizer->get_subclass($parent);
    }

    public function get_subobjectproperty($parent){
      return $this->ans_analizer->get_subobjectproperty($parent);
    }

    public function get_kb_status(){
      return $this->ans_analizer->get_kb_status();
    }

    public function get_unsatClasses(){
      return $this->ans_analizer->get_unsatClasses();
    }

    public function get_satClasses(){
      return $this->ans_analizer->get_satClasses();
    }

    public function get_unsatObjectProperties(){
      return $this->ans_analizer->get_unsatObjectProperties();
    }

    public function get_satObjectProperties(){
      return $this->ans_analizer->get_satObjectProperties();
    }

    public function get_stricter_cardinalities(){
      return $this->ans_analizer->get_stricter_cardinalities();
    }

    public function get_classOfStricter($stricter_el){
      return $this->ans_analizer->get_classOfStricter($stricter_el);
    }

    public function get_opOfStricter($stricter_el){
      return $this->ans_analizer->get_opOfStricter($stricter_el);
    }

    public function get_roleOfStricter($stricter_el){
      return $this->ans_analizer->get_roleOfStricter($stricter_el);
    }

    public function get_maxOfStricter($stricter_el){
      return $this->ans_analizer->get_maxOfStricter($stricter_el);
    }

    /**
     Returns possible warnings from off-the-shelf reasoners
    */
    public function get_reasoning_warning(){
      return $this->ans_analizer->get_reasoning_warning();
    }

    /**
       Retrieve the answer.

       Ensure to call the analize_answer() before.

       @return Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer An answer object.
     */
    function get_answer(){
        //return $this->ans_analizer->get_answer();
        return $this->ans_analizer->get_beatified_responses();
    }

}
?>
