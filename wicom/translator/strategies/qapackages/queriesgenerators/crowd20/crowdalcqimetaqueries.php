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

namespace Wicom\Translator\Strategies\QAPackages\QueriesGenerators\crowd20;

use function \load;
//load('queriesgenerator.php', '../');
load('crowdmetaqueries.php', '../');


use Wicom\Translator\Strategies\QAPackages\QueriesGenerators\CrowdMetaQueries;

/**
   ALCQI Queries only for the Crowd 2.0 strategy.

   Generates queries for checking:

   * KB Satisfiability.
   * Classes satisfiability.
   * For cardinalities inference.

 */
class CrowdALCQIMetaQueries extends CrowdMetaQueries {
    function __construct(){
    }

    /**
       Generate all queries on the builder provided.

       @param $json_str a String representing the JSON of the user model.
       @param $builder an instance of Wicom\Translator\Builders\DocumentBuilder.

     */
    function generate_all_queries($el_toQuery, $builder){
      parent::generate_all_queries($el_toQuery, $builder);
    }

    /**
    Generate ALCQI questios isEntailed for check max Cardinalities
    Function takes the global max cardinality and each role encoded to generate one isEntailed query for each possible cardinality
    */
    function generate_maxcardinality_queries($c_strategy, $builder){
      $mxCard_g = $c_strategy->get_global_maxcardinality();
      $array_card = $c_strategy->get_maxcardinalities();

      foreach ($array_card as $card_el) {
        for ($i = 1; $i <= $mxCard_g ; $i++) {
          $builder->insert_isEntailedMaxCardinality_query($card_el["class"], $card_el["op"], $i, $card_el["rel"]);
        }
      }
    }

}
?>
