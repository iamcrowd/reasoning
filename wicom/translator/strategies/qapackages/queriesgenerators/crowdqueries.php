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
class CrowdQueries extends QueriesGenerator {
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
       I generate queries for checking diagram satisfability.

       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
     */
/*    function gen_satisfiable($builder){
        $builder->insert_satisfiable();
    } */

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
       I generate queries for checking satisfability per each class
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
/*    function gen_class_satisfiable($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $jelem) {
            $builder->insert_satisfiable_class($jelem["name"]);
        }

        $this->gen_class_satisfiable_min_max($json_diagram, $builder);
    }*/

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
       Generate queries for checking for entailed classes.

       @see gen_class_satisfiable() for parameters.
     */
/*    function gen_entailed_classes($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        $i = 0;

        while ($i < count($json_classes) - 1){
            $j = $i + 1;
            while ($j < count($json_classes)) {
              $array_en = [];
              array_push($array_en, $json_classes[$i]["name"], $json_classes[$j]["name"]);
              $builder->insert_isEntailed_query($array_en);
              $j++;
            }
            $i++;
        }

        $this->gen_entailed_classes_min_max($json_diagram, $builder);
    } */

    /**
       Generate query for getting all classes.

       @see gen_class_satisfiable() for parameters.
     */
/*    function gen_all_classes($builder){
        $builder->insert_get_all_classes_query();
    } */

    /**
       Generate query for getting subclasses hierarchy.

       @see gen_class_satisfiable() for parameters.
     */
/*    function gen_sub_classes_hierarchy($builder){
        $builder->insert_get_subClassHierarchy_query();
    } */

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
       Generate queries for getting subclasses.

       @see gen_class_satisfiable() for parameters.
     */
/*    function gen_sub_classes($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $jelem){
            $builder->insert_get_subClasses_query($jelem["name"]);
        }

        $this->gen_sub_classes_min_max($json_diagram, $builder);
    }*/

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
       Generate queries for getting superclasses.

       @see gen_class_satisfiable() for parameters.
     */
/*    function gen_super_classes($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $classname){
            $builder->insert_get_superClasses_query($classname["name"]);
        }

        $this->gen_super_classes_min_max($json_diagram, $builder);
    } */

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
       Generate queries for getting equivalent classes.

       @see gen_class_satisfiable() for parameters.
     */
/*    function gen_equivalent_classes($json_diagram, $builder){
        $json = json_decode($json_diagram, true);
        $json_classes = $json["classes"];

        foreach ($json_classes as $classname){
            $builder->insert_get_equivalentClasses_query($classname["name"]);
        }

        $this->gen_equivalent_classes_min_max($json_diagram, $builder);
    }*/

}
?>
