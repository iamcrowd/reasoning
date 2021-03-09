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
   Queries only for the Berardi strategy.

   Generates queries for checking:

   * KB Satisfiability.
   * Classes satisfiability.

 */
class EnzoQueries extends QueriesGenerator {
    function __construct(){
    }

    /**
       Generate all queries on the builder provided.

       @param $json_str a String representing the JSON of the user model.
       @param $builder an instance of Wicom\Translator\Builders\DocumentBuilder.

     */
    function generate_all_queries($json_str, $builder){
			/*
        $this->gen_class_satisfiable($json_str, $builder);
		$this->gen_satisfiable($builder);
        $this->gen_all_classes($builder);
		*/
        parent::generate_all_queries($json_str, $builder);
    }

    /**
       I generate queries for checking diagram satisfability.

       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
     */
    function gen_satisfiable($builder){
        $builder->insert_satisfiable();
    } 

    /**
       I generate queries for checking satisfability per each class
       in the diagram.

       @param $json_diagram a String in JSON format with the diagram.
       @param $builder A Wicom\Translator\Builders\DocumentBuilder
       instance.
    */
    function gen_class_satisfiable($json_diagram, $builder){
		
		//echo "WOW eznoqueries.php";
        $json = json_decode($json_diagram, true);
        $json_classes = $json["entities"];

        foreach ($json_classes as $jelem) {
            $builder->insert_satisfiable_class($jelem["name"]);
        }
    } 

    /**
       Generate query for getting all classes.

       @see gen_class_satisfiable() for parameters.
     */
    function gen_all_classes($builder){
        $builder->insert_get_all_classes_query();
    } 

}
?>
