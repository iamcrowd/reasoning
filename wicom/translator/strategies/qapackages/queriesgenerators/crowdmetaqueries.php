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
       Generate queries for checking satisfiability of min and max.

       @see gen_class_satisfiable() for parameters.
     */
    protected function gen_class_satisfiable_min_max($json_diagram, $builder){
        // [[class => [min, max]], [class2 => [min2, max2]], ...]
        $lst_classes = $builder->get_classes_with_min_max();

        foreach ($lst_classes as $classname => $tuple){
            $builder->insert_satisfiable_class($tuple[0]);
            $builder->insert_satisfiable_class($tuple[1]);
        }
    }



    /**
       Generate queries for checking for entailed classes min and max.

       @see gen_class_satisfiable() for parameters.
     */
    protected function gen_entailed_classes_min_max($json_diagram, $builder){
        // [[class => [min, max]], [class2 => [min2, max2]], ...]
        $lst_classes = $builder->get_classes_with_min_max();

        foreach ($lst_classes as $classname => $tuple){
            $builder->insert_isEntailed_query([$classname, $tuple[0]]);
            $builder->insert_isEntailed_query([$classname, $tuple[1]]);
        }
    }

    /**
       Generate queries for checking for subclasses of min and max.

       @see gen_class_satisfiable() for parameters.
     */
    protected function gen_sub_classes_min_max($json_diagram, $builder){
        // [[class => [min, max]], [class2 => [min2, max2]], ...]
        $lst_classes = $builder->get_classes_with_min_max();

        foreach ($lst_classes as $classname => $tuple){
            $builder->insert_get_subClasses_query($tuple[0]);
            $builder->insert_get_subClasses_query($tuple[1]);
        }
    }

    /**
       Generate queries for checking for superclasses of min and max.

       @see gen_class_satisfiable() for parameters.
     */
    protected function gen_super_classes_min_max($json_diagram, $builder){
        // [[class => [min, max]], [class2 => [min2, max2]], ...]
        $lst_classes = $builder->get_classes_with_min_max();

        foreach ($lst_classes as $classname => $tuple){
            $builder->insert_get_superClasses_query($tuple[0]);
            $builder->insert_get_superClasses_query($tuple[1]);
        }
    }

    /**
       Generate queries for checking equivalent of min and max.

       @see gen_class_satisfiable() for parameters.
     */
    protected function gen_equivalent_classes_min_max($json_diagram, $builder){
        // [[class => [min, max]], [class2 => [min2, max2]], ...]
        $lst_classes = $builder->get_classes_with_min_max();

        foreach ($lst_classes as $classname => $tuple){
            $builder->insert_get_equivalentClasses_query($tuple[0]);
            $builder->insert_get_equivalentClasses_query($tuple[1]);
        }
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
        $json_classes = $json["Object type"];

        foreach ($json_classes as $jelem) {
            $builder->insert_satisfiable_class($jelem["name"]);
        }
    }

    /**
       I generate queries for checking satisfability for each objectProperty (role)
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
       @todo add roles for n-ary assocs. Now only generates queries for UML binary associations
    */
    function gen_objectProperty_satisfiable($json_diagram, $builder){

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
