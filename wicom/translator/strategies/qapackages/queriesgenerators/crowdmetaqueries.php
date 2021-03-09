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

require_once __DIR__ . '/queriesgenerator.php';

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
    function generate_all_queries($json_str, $builder){
/*        $this->gen_satisfiable($builder);
        $this->gen_class_satisfiable($json_str, $builder);
        $this->gen_entailed_classes($json_str, $builder);
        $this->gen_all_classes($builder);
        $this->gen_sub_classes($json_str, $builder);
        $this->gen_super_classes($json_str, $builder);
        $this->gen_equivalent_classes($json_str, $builder);
        $this->gen_sub_classes_hierarchy($builder);*/

        parent::generate_all_queries($json_str, $builder);
    }


    /**
       I generate queries for checking satisfability per each class
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_class_satisfiable($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["Entity type"]["Object type"];

        foreach ($json_classes as $jelem) {
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
    function gen_objectProperty_satisfiable($json_meta, $builder){
        $json = json_decode($json_meta, true);
        $json_roles = $json["Role"];

        foreach ($json_roles as $role) {
          $builder->insert_satisfiable_objectProperty($role["rolename"]);
        }
    }

    /**
       I generate queries for checking satisfability for each attribute
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_dataProperty_satisfiable($json_diagram, $builder){

    }


    /**
       Generate queries for checking disjointness in each class
       of the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getDisjointClasses($json_diagram, $builder){
    }

    /**
       Generate queries for checking class equivalence in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_getEquivalentClasses($json_diagram, $builder){
    }

    /**
       Generate query for getting subclasses hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_subClassHierarchy($builder){

    }

    /**
       Generate query for getting subObjectProperties hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_subObjectPropertyHierarchy($builder){

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

}
?>
