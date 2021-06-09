<?php
/*

   Copyright 2019 GILIA

   Author: gab

   crowd_dlmeta.php

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

namespace Wicom\Translator\Strategies\Strategydlmeta\crowd20;

use function \load;
load('crowdalcqimetapack.php', '../../qapackages/crowd20/');
load("crowd_checkmeta.php", "../");
load("crowd_dl_alcqi_meta.php", "./");

use Wicom\Translator\Strategies\QAPackages\crowd20\CrowdALCQIMetaPack;
use Wicom\Translator\Strategies\Strategydlmeta\DLCheckMeta;

use Wicom\Translator\Strategies\Strategydlmeta\crowd20\DLALCQIMeta;

class DLALCQIMetaForAll extends DLALCQIMeta{


    function __construct(){
      parent::__construct();
    }

    /**
       Translate a JSON KF Relationship and its Roles into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel containing a relationship and its roles in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.

       ∃Ai \sqsubseteq A
       ∃Ai- \sqsubseteq Ci
       for i ∈ {1, . . . , n}
       Ci \sqsubseteq ∃A \sqcap <= 1 A \sqcap · · · \sqcap ∃An \sqcap <= 1 An
    */
    protected function translate_relationship($json, $builder){
      $json_rel = $json["Relationship"]["Relationship"];
      $json_role = $json["Role"];
      $json_ot_card = $json["Constraints"]["Cardinality constraints"]["Object type cardinality"];

      foreach ($json_rel as $rel){
          $already_rolencoded = [];
          $relname = $rel["name"];
          $entities = $rel["entities"];

          // Roles with cardinalities
          foreach ($json_role as $role) {
            $lst = [];
            $lst_role = [];
            $lst_role_inv = [];
            $role_ot_card = $role["object type cardinality"];

            if (strcasecmp($role["relationship"], $relname) == 0){
              array_push($already_rolencoded, $role);
              $lst_role = [
                "subclass" => [
                  ["class" => $relname],
                  ["forall" => [
                    ["role" => $role["rolename"]],
                    ["class" => $role["entity type"]]]
                  ],
                  ]
                ];

                array_push($lst, $lst_role);
                $builder->translate_DL($lst);

                foreach ($role_ot_card as $role_card) {
                  foreach ($json_ot_card as $ot_card) {

                    if (strcasecmp($ot_card["name"], $role_card) == 0){
                      $card_conj = [];
                      $min = $ot_card["minimum"];
                      $max = $ot_card["maximum"];

                      if (strcasecmp($min, "0") != 0){
                        $card_min_ax = [
                                      ["subclass" => [
                                        ["class" => $role["entity type"]],
                                        ["mincard" => [$min,
                                                        ["inverse" => ["role" => $role["rolename"]]],
                                                        ["class" => $relname]
                                                      ]
                                        ]
                                      ]
                                     ]
                                    ];

                        $builder->translate_DL($card_min_ax);
                      }

                      if ((strcasecmp($max, "N") != 0) && (strcasecmp($max, "*") != 0)){
                        $card_max_ax = [
                                      ["subclass" => [
                                        ["class" => $role["entity type"]],
                                        ["maxcard" => [
                                                      $max,
                                                        ["inverse" => ["role" => $role["rolename"]]],
                                                        ["class" => $relname]
                                                      ]
                                                    ]
                                        ]
                                      ]
                                    ];
                        $builder->translate_DL($card_max_ax);

                        if ($this->check_card){
                          if ($max > $this->global_maxcard){ $this->global_maxcard = $max; }
                          \array_push($this->maxcardinalities, ["class" => $role["entity type"],
                                                                "op" => $role["rolename"],
                                                                "rel" => $relname,
                                                                "maxcard" => $max]);
                        }
                        } else {
                          if ($this->check_card){
                            \array_push($this->maxcardinalities, ["class" => $role["entity type"],
                                                                  "op" => $role["rolename"],
                                                                  "rel" => $relname,
                                                                  "maxcard" => "*"]);
                          }
                        }
                      }
                    }
                  }
                }
              }
        }
    }


}
?>
