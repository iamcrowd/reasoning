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

use Wicom\Translator\Strategies\QAPackages\CrowdMetaPack;
use Wicom\Translator\Strategies\Strategy;

class DLMeta extends Strategy{

    function __construct(){
      parent::__construct();

      $this->qapack = new CrowdMetaPack();
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
      $constraints = $json["Disjointness constraints"][0]["Disjoint object type"];

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
      $constraints = $json["Completeness constraints"];

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

    /**
       Translate a JSON KF Subsumption into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel containing a subsumption in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
       @todo Disjointness and Complete constraints
    */
    protected function translate_subsumption($json, $builder){
      $json_subs = $json["Relationship"][0]["Subsumption"];
      $already_constrencoded = [];

      foreach ($json_subs as $sub){
          $parent = $sub["entity parent"];
          $child = $sub["entity children"];
          $lst = [
              ["subclass" => [
                  ["class" => $child],
                  ["class" => $parent]
                ]
              ]];
          $builder->translate_DL($lst);

          if ($sub["disjointness constraints"] != ""){
            $disj_name = $sub["disjointness constraints"];
            if (!in_array($disj_name,$already_constrencoded)){
              $this->translate_disjointness($json, $disj_name, $builder);
              array_push($already_constrencoded, $disj_name);
            }
          }

          if ($sub["completeness constraints"] != ""){
            $comp_name = $sub["completeness constraints"];
            if (!in_array($comp_name,$already_constrencoded)){
              $this->translate_completeness($json, $comp_name, $parent, $builder);
              array_push($already_constrencoded, $comp_name);
            }
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
    */
    protected function translate_relationship($json, $builder){
      $json_rel = $json["Relationship"][1]["Relationship"];
      $json_role = $json["Role"];
      $already_rolencoded = [];

      foreach ($json_rel as $rel){
          $relname = $rel["name"];
          $entities = $rel["entities"];

          foreach ($json_role as $role) {
            $lst = [];
            $lst_dom = [];
            $lst_range = [];

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
            }
          }

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
          //print_r($lst_card);
          $builder->translate_DL($lst_card);
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

        $js_objtype = $json["Entity type"][0]["Object type"];
        $js_role = $json["Role"];
        $js_rel = $json["Relationship"][1]["Relationship"];

        if (!empty($js_objtype)){
            foreach ($js_objtype as $objtype){
                $builder->insert_class_declaration($objtype);
            }
        }
        if (!empty($js_role)){
            foreach ($js_role as $role){
                $builder->insert_objectproperty_declaration($role["rolename"]);
            }
        }
        if (!empty($js_rel)){
            foreach ($js_rel as $rel){
                $builder->insert_class_declaration($rel["name"]);
            }
        }

        $this->translate_subsumption($json, $builder);
        $this->translate_relationship($json, $builder);

      }


      function decode($owl, $jsonbuild){}

}
?>
