<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   crowdqueries.php

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

namespace Wicom\Translator\Strategies\QAPackages\QueriesGenerators;

use function \load;
load('queriesgenerator.php');

/**
   Queries only for the Crowd strategy.

   Generates queries for checking:

   * KB Satisfiability.
   * Classes satisfiability.
   * For cardinalities inference.

 */
class CrowdMetaQueries extends QueriesGenerator {
    function __construct(){
    }

    /**
       Generate all queries on the builder provided.

       @param $json_str a String representing the JSON of the user model.
       @param $builder an instance of Wicom\Translator\Builders\DocumentBuilder.

     */
    function generate_all_queries($el_toQuery, $builder){
      parent::generate_all_queries($el_toQuery, $builder);
      if ($el_toQuery->get_check_cardinalities()){
        $this->generate_maxcardinality_queries($el_toQuery, $builder);
      }
    }


    /**
       I generate queries for checking satisfability per each class
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_class_satisfiable($el_toQuery, $builder){
      $classes = $el_toQuery->get_classes();
      foreach ($classes as $jelem) {
          $builder->insert_satisfiable_class($jelem);
      }
    }

    /**
       I generate queries for checking satisfability for each objectProperty (role)
       in the diagram.

       @param $json_diagram a String in JSON format with a metamodel instance.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_objectProperty_satisfiable($el_toQuery, $builder){
      $OP = $el_toQuery->get_objectProperties();
      foreach ($OP as $jelem) {
          $builder->insert_satisfiable_objectProperty($jelem);
      }
    }

    /**
       I generate queries for checking satisfability for each attribute
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_dataProperty_satisfiable($el_toQuery, $builder){
      $DP = $el_toQuery->get_dataProperties();
      foreach ($DP as $jelem) {
          $builder->insert_satisfiable_dataProperty($jelem);
      }
    }


    /**
       Generate queries for checking disjointness in each class
       of the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getDisjointClasses($el_toQuery, $builder){
      $classes = $el_toQuery->get_classes();
      foreach ($classes as $jelem) {
          $builder->insert_get_disjointClasses_query($jelem);
      }
    }

    /**
       Generate queries for checking class equivalence in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getEquivalentClasses($el_toQuery, $builder){
      $classes = $el_toQuery->get_classes();
      foreach ($classes as $jelem) {
          $builder->insert_get_equivalentClasses_query($jelem);
      }
    }

    /**
       Generate queries for checking disjointness for each OP
       generated after encoding a KF instance.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getDisjointObjProp($el_toQuery, $builder){
      $OP = $el_toQuery->get_objectProperties();
      foreach ($OP as $jelem) {
          $builder->insert_getDisjointObjProp_query($jelem);
      }
    }

    /**
       Generate queries for checking equivalence for each OP
       generated after encoding a KF instance.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getEquivalentObjProp($el_toQuery, $builder){
      $OP = $el_toQuery->get_objectProperties();
      foreach ($OP as $jelem) {
          $builder->insert_getEquivalentObjProp_query($jelem);
      }
    }

    /**
       Generate queries for checking disjointness for each DP generated after encoding a KF instance.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getDisjointDataProp($el_toQuery, $builder){
      $OP = $el_toQuery->get_dataProperties();
      foreach ($OP as $jelem) {
          $builder->insert_getDisjointDataProp_query($jelem);
      }
    }

    /**
       Generate queries for checking equivalence for each DP generated after encoding a KF instance.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getEquivalentDataProp($el_toQuery, $builder){
      $OP = $el_toQuery->get_dataProperties();
      foreach ($OP as $jelem) {
          $builder->insert_getEquivalentDataProp_query($jelem);
      }
    }

    /**
       Generate query for getting subclasses hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_subClassHierarchy($builder){
      parent::gen_subClassHierarchy($builder);
    }

    /**
       Generate query for getting subObjectProperties hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_subObjectPropertyHierarchy($builder){
        parent::gen_subObjectPropertyHierarchy($builder);
    }


    /**
       Generate queries for checking for entailed classes. Entailed applies just for EquivalentClasses axiom

       @see gen_class_satisfiable() for parameters.
       @see get_entailedDirect_classes for class axioms diferent from EquivalentClasses
     */
    function gen_entailed_EquivalentClasses($json_diagram, $builder){

    }

    /**
       Generate queries for checking for entailedDirect classes. EntailedDirect applies for
       DisjointClasses, SubClassOf and SubObjectPropertyOf axiom

       @see gen_class_satisfiable() for parameters.
     */
    function gen_entailedDirect_SubClasses($json_diagram, $builder){

    }

    /**
    Generate queries for checking for entailedDirect classes. EntailedDirect applies for
    DisjointClasses, SubClassOf and SubObjectPropertyOf axiom

    @see gen_class_satisfiable() for parameters.
     */
    function gen_entailedDirect_DisjointClasses($json_diagram, $builder){
    }

    /**
    Generate queries for checking for entailedDirect classes. EntailedDirect applies for
    DisjointClasses, SubClassOf and SubObjectPropertyOf axiom

    @see gen_class_satisfiable() for parameters.
     */
    function gen_entailedDirect_SubObjPropertyOf($json_diagram, $builder){
    }


    /**
    Generate questios isEntailed for check max Cardinalities
    Function takes the global max cardinality and each role encoded to generate one isEntailed query for each possible cardinality
    */
    function generate_maxcardinality_queries($c_strategy, $builder){
      $mxCard_g = $c_strategy->get_global_maxcardinality();
      $array_card = $c_strategy->get_maxcardinalities();

      foreach ($array_card as $card_el) {
        for ($i = 1; $i <= $mxCard_g ; $i++) {
          $builder->insert_isEntailedMaxCardinality_query($card_el["class"], $card_el["op"], $i);
        }
      }
    }

}
?>
