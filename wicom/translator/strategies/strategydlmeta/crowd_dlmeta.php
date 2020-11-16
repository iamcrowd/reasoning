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

namespace Wicom\Translator\Strategies\Strategydlmeta;

//use function \load;
//load("strategy.php","../");
//load('crowdmetapack.php', '../qapackages/');

use function \load;
load('crowdmetapack.php', '../qapackages/');
load('strategy.php', '../');
load('metamodel.php', '../');
load("crowd_checkmeta.php", "./");

use Wicom\Translator\Strategies\QAPackages\CrowdMetaPack;
use Wicom\Translator\Strategies\Strategy;
use Wicom\Translator\Strategies\Metamodel;
use Wicom\Translator\Strategies\Strategydlmeta\DLCheckMeta;

class DLMeta extends Metamodel{

    protected $output = null;
    protected $global_maxcard = null;
    protected $global_mincard = null;
    protected $maxcardinalities = null;
    protected $check_card = null;

    function __construct(){
      parent::__construct();
      $this->qapack = new CrowdMetaPack();
      $this->check_card = false;
      $this->global_maxcard = 0;
      $this->global_mincard = 0;
      $this->maxcardinalities = [];
    }

    function get_output($json, $strategy){
      $this->output = new DLCheckMeta($json, $strategy);
      return $this->output->built_output();
    }

    function get_global_maxcardinality(){
      return $this->global_maxcard;
    }

    function get_maxcardinalities(){
      return $this->maxcardinalities;
    }

    /**
      true if you want to check cardinalities
      false otherwise
    */
    function set_check_cardinalities($bool){
      $this->check_card = $bool;
    }

    function get_check_cardinalities(){
      return $this->check_card;
    }

    /**
       Translate a JSON KF Disjointness Constraint into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel containing a disjointness constraint in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
    */
    protected function translate_disjointness($json, $disj_name, $builder){
      $disjoint = [];
      $constraints = $json["Constraints"]["Disjointness constraints"]["Disjoint object type"];

      foreach ($constraints as $constraint) {
        if (strcasecmp($disj_name,$constraint["name"]) == 0){
          foreach ($constraint["entities"] as $entitydisj){
              array_push($disjoint, ["class" => $entitydisj]);
          }
          $lst = [
              ["subclass" => [
                  ["intersection" => $disjoint],
                  ["class" => "owl:Nothing"]
                 ]
              ]
          ];
          $builder->translate_DL($lst);
        }
      }
    }

    /**
       Translate a JSON KF Completeness Constraint into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel containing a completeness constraint in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
    */
    protected function translate_completeness($json, $compl_name, $parent, $builder){
      $complete = [];
      $constraints = $json["Constraints"]["Completeness constraints"];

      foreach ($constraints as $constraint) {
        if (strcasecmp($compl_name,$constraint["name"]) == 0){
          foreach ($constraint["entities"] as $entitycompl){
              array_push($complete, ["class" => $entitycompl]);
          }
          $gencov = [["subclass" => [
              ["class" => $parent],
              ["union" => $complete]
          ]]];
          $builder->translate_DL($gencov);
        }
      }
    }

    protected function is_entity_type($json, $objtype){
      $js_objtype = $json["Entity type"]["Object type"];

      foreach ($js_objtype as $ot) {
        if (strcmp($objtype, $ot) == 0){
          return true;
        }
      }
      return false;
    }

    protected function is_role($json, $role){
      $js_role = $json["Role"];

      foreach ($js_role as $ro) {
        if (strcmp($ro["rolename"], $role) == 0){
          return true;
        }
      }
      return false;
    }

    /**
      Return a role given an object type and a relationship
    */
    protected function get_role($json, $rel, $objtype){
      $js_role = $json["Role"];

      foreach ($js_role as $ro) {
        if ((strcmp($ro["entity type"], $objtype) == 0) &&
           (strcmp($ro["relationship"], $rel) == 0)){
          return $ro["rolename"];
        }
      }
      return null;
    }

    protected function is_relationship($json, $rel){
      $js_rel = $json["Relationship"]["Relationship"];

      foreach ($js_rel as $r) {
        if (strcmp($r["name"], $rel) == 0){
          return true;
        }
      }
      return false;
    }

    /**
      Return object types involved in a Relationship
    */
    protected function get_relationship_objecttypes($json, $rel){
      $js_rel = $json["Relationship"]["Relationship"];

      foreach ($js_rel as $r) {
        if (strcmp($r["name"], $rel) == 0){
          return $r["entities"];
        }
      }
      return [];
    }

    /**
       Translate a JSON KF Subsumption into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel containing a subsumption in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.

       C1 \sqsubseteq C
       Ci \sqcap Cj \sqsubseteq \bot (disjointness)
       C \sqsubseteq C1 \sqcup · · · \sqcup Ck (completeness)

       @note complete and disjoint subtypes are declared among
       subtypes on the subsumption relation, not between any object types
       @note a subsumption can be declared among any two Relationships,
       between any two Object types or between any two Roles. However completeness and disjointness
       constraint are only declared over two or more object types.
    */
    protected function translate_subsumption($json, $builder){
      $json_subs = $json["Relationship"]["Subsumption"];
      $already_constrencoded = [];

      foreach ($json_subs as $sub){
          $parent = $sub["entity parent"];
          $child = $sub["entity child"];

          if ($this->is_entity_type($json,$parent) && $this->is_entity_type($json,$child)){

               $lst = [
                 ["subclass" => [
                   ["class" => $child],
                   ["class" => $parent]
                 ]
                 ]];
                $builder->translate_DL($lst);

                if (array_key_exists("disjointness constraints", $sub)) {
                  if ($sub["disjointness constraints"] != ""){
                    $disj_name = $sub["disjointness constraints"];

                    if (!in_array($disj_name,$already_constrencoded)){
                      $this->translate_disjointness($json, $disj_name, $builder);
                      array_push($already_constrencoded, $disj_name);
                    }
                  }
                }

                if (array_key_exists("completeness constraints", $sub)) {
                  if ($sub["completeness constraints"] != ""){
                    $comp_name = $sub["completeness constraints"];

                    if (!in_array($comp_name,$already_constrencoded)){
                      $this->translate_completeness($json, $comp_name, $parent, $builder);
                      array_push($already_constrencoded, $comp_name);
                    }
                  }
                }
            } elseif ($this->is_relationship($json,$parent) && $this->is_relationship($json,$child)) {
                $lst = [
                  ["subclass" => [
                    ["class" => $child],
                    ["class" => $parent]
                  ]
                  ]];
                  $builder->translate_DL($lst);

                  $end_ot_child = [];
                  $end_ot_parent = [];
                  $end_ot_child = $this->get_relationship_objecttypes($json,$child);
                  $end_ot_parent = $this->get_relationship_objecttypes($json,$parent);

                  if ((\count($end_ot_child) != 0) && (\count($end_ot_parent) != 0)){

                    for ($i = 0; $i < \count($end_ot_child); $i++) {
                      $role_child = null;
                      $role_parent = null;
                      $role_child = $this->get_role($json, $child, $end_ot_child[$i]);
                      $role_parent = $this->get_role($json, $parent, $end_ot_parent[$i]);

                      if (($role_child != null) && ($role_parent != null)){
                        if (!in_array([$role_child, $role_parent], $already_constrencoded)){
                          $lst_r = [
                            ["subrole" => [
                              ["role" => $role_child],
                              ["role" => $role_parent]
                            ]
                            ]];
                          $builder->translate_DL($lst_r);
                          array_push($already_constrencoded, [$role_child, $role_parent]);
                        }
                      }
                    }
                  }

            } elseif ($this->is_role($json,$parent) && $this->is_role($json,$child)) {
                if (!in_array([$child, $parent], $already_constrencoded)){
                  $lst = [
                    ["subrole" => [
                      ["role" => $child],
                      ["role" => $parent]
                    ]
                    ]];
                  $builder->translate_DL($lst);
                  array_push($already_constrencoded, [$child, $parent]);
                }
            }
      }
    }

    /**
       Translate a JSON KF Attibute Property into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel containing an attributive property in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.

       datapropertydomain(DP,A)
       datapropertyrange(DP,Datatype)
       A \sqsubseteq \exists DP \sqcap (\leq 1 DP)

       @// NOTE:   Basic encoding without considering cardinalities.
       They are translated as dataproperties with domain and range.

       @// TODO: add cardinalities in attributes. Currently, one-to-one
    */
    protected function translate_attributiveProperty($json, $builder){
      $json_attrProp = $json["Relationship"]["Attributive property"]["Attributive property"];

      foreach ($json_attrProp as $attr_el) {
        $attr_dom = $attr_el["domain"];

        foreach ($attr_dom as $attr_dom_el) {

          $el = [
                  ["data_domain" => [
                    ["data_role" => $attr_el["name"]],
                    ["class" => $attr_dom_el]
                  ]],
                  ["data_range" => [
                    ["data_role" => $attr_el["name"]],
                    ["datatype" => $attr_el["range"]]
                  ]],
                  ["subclass" => [
                    ["class" => $attr_dom_el],
                    ["data_mincard" => [
                          1,
                          ["data_role" => $attr_el["name"]]
                          ]
                        ]
                    ]
                  ],
                  ["subclass" => [
                    ["class" => $attr_dom_el],
                    ["data_maxcard" => [
                          1,
                          ["data_role" => $attr_el["name"]]
                          ]
                        ]
                    ]
                  ]
                ];

            $builder->translate_DL($el);

        }
      }
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
            $lst_dom = [];
            $lst_range = [];
            $role_ot_card = $role["object type cardinality"];

            if (strcasecmp($role["relationship"], $relname) == 0){
              array_push($already_rolencoded, $role);
              $lst_dom = [
                "subclass" => [
                  ["exists" => [
                    ["role" => $role["rolename"]],
                    ["class" => "owl:Thing"]]],
                  ["class" => $relname]
                  ]
                ];

              $lst_range = [
                "subclass" => [
                  ["exists" => [
                    ["inverse" => ["role" => $role["rolename"]]],
                    ["class" => "owl:Thing"]]],
                  ["class" => $role["entity type"]]
                  ]
                ];

                array_push($lst, $lst_dom);
                array_push($lst, $lst_range);
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
                                        ["mincard" => [$min, ["inverse" => ["role" => $role["rolename"]]]]]
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
                                                      $max, ["inverse" => ["role" => $role["rolename"]]]
                                                      ]]
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

            // Relationships after encoding each role in the instance
            $conjunction = [];

            foreach ($already_rolencoded as $erole) {
              $exists_temp = [
                "exists" => [
                  ["role" => $erole["rolename"]],
                  ["class" => "owl:Thing"]
                ]
              ];
              $card_temp = [
                "maxcard" => [1,
                  ["role" => $erole["rolename"]]
                ]
              ];
              array_push($conjunction, $exists_temp);
              array_push($conjunction, $card_temp);
            }

            $lst_card = [
              ["subclass" => [
                  ["class" => $relname],
                  ["intersection" => $conjunction]
                ]
              ]
            ];
            $builder->translate_DL($lst_card);
        }
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
    protected function translate_attributeMappedTo($json, $builder){
      $json_mappedTo = $json["Relationship"]["Attributive property"]["Attribute"]["Mapped to"];

      foreach ($json_mappedTo as $mapped){
          $attrname = $mapped["name"];
          $attrrange = $mapped["range"];
          $attrdomains = $mapped["domain"];

          foreach ($attrdomains as $domain_el) {
            // encoded as Attributive Property
            $el = [
                    ["data_domain" => [
                      ["data_role" => $attrname],
                      ["class" => $domain_el]
                    ]],
                    ["data_range" => [
                      ["data_role" => $attrname],
                      ["datatype" => $attrrange]
                    ]],
                    ["subclass" => [
                      ["class" => $domain_el],
                      ["data_mincard" => [
                            1,
                            ["data_role" => $attrname]
                            ]
                          ]
                      ]
                    ],
                    ["subclass" => [
                      ["class" => $domain_el],
                      ["data_maxcard" => [
                            1,
                            ["data_role" => $attrname]
                            ]
                          ]
                      ]
                    ]
                  ];

              $builder->translate_DL($el);
          }
        }
    }

    /**
       Translate a JSON KF Metamodel String into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel representation in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
       @// NOTE:  declarations are put at top when the encoding is starting in order to get "standard"
       documents.
    */
    function translate($json_str, $builder){

        $json = json_decode($json_str, true);

        $js_objtype = $json["Entity type"]["Object type"];
        $js_role = $json["Role"];
        $js_rel = $json["Relationship"]["Relationship"];
        $js_valueType = $json["Entity type"]["Value property"]["Value type"];
        $js_attrProp = $json["Relationship"]["Attributive property"]["Attributive property"];
        $js_attrMappedTo = $json["Relationship"]["Attributive property"]["Attribute"]["Mapped to"];

        if (!empty($js_objtype)){
            foreach ($js_objtype as $objtype){
                $builder->insert_class_declaration($objtype);
                array_push($this->classes, $objtype);
            }
        }
        if (!empty($js_valueType)){
            foreach ($js_valueType as $valuetype){
                $builder->insert_class_declaration($valuetype);
                array_push($this->classes, $valuetype);
            }
        }
        if (!empty($js_role)){
            foreach ($js_role as $role){
                $builder->insert_objectproperty_declaration($role["rolename"]);
                array_push($this->objectProperties, $role["rolename"]);
            }
        }
        if (!empty($js_rel)){
            foreach ($js_rel as $rel){
                $builder->insert_class_declaration($rel["name"]);
                array_push($this->classes, $rel["name"]);
            }
        }
        if (!empty($js_attrProp)){
            foreach ($js_attrProp as $attrProp_el){
                $builder->insert_dataproperty_declaration($attrProp_el["name"]);
                array_push($this->dataProperties, $attrProp_el["name"]);
            }
        }

        if (!empty($js_attrMappedTo)){
            foreach ($js_attrMappedTo as $attrMappedTo_el){
                $builder->insert_dataproperty_declaration($attrMappedTo_el["name"]);
                array_push($this->dataProperties, $attrMappedTo_el["name"]);
            }
        }

        $this->translate_subsumption($json, $builder);
        $this->translate_relationship($json, $builder);
        $this->translate_attributiveProperty($json, $builder);
        $this->translate_attributeMappedTo($json, $builder);
      }

}
?>
