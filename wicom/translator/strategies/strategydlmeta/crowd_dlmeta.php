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
                  ["class" => "owl:Bottom"]
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
       Translate a JSON KF Metamodel String into another format depending on
       the given Builder.

       @param json_str A String with a KF metamodel representation in
       JSON format.
       @param builder A Wicom\Translator\Builders\DocumentBuilder subclass instance.

       @see Translator class for description about the JSON format.
    */
    function translate($json_str, $builder){

        $json = json_decode($json_str, true);

        $js_objtype = $json["Entity type"][0]["Object type"];

      /*  $js_links = $json["links"]; */

        if (!empty($js_objtype)){
            foreach ($js_objtype as $objtype){
                $builder->insert_class_declaration($objtype);

/*            if (!empty($class["attrs"])){
              foreach ($class["attrs"] as $attr){
                $builder->insert_dataproperty_declaration($attr["name"]);
              }*/
            }
        }

/*        $gen_array = [];

          if (!empty($json["links"])){
            foreach ($json["links"] as $link){
              switch ($link["type"]){
                case "generalization":
                  array_push($gen_array, $link);
                  break;
                case "association":
                  $builder->insert_objectproperty_declaration($link["name"]);
                  break;
                case "association with class":
                  $builder->insert_objectproperty_declaration($link["roles"][0]);
                  $builder->insert_objectproperty_declaration($link["roles"][1]);
                  break;
                case "n-ary association without class":
                  foreach ($link["roles"] as $role){
                    $builder->insert_objectproperty_declaration($role);
                  }
                  break;
                case "n-ary association with class":
                  foreach ($link["roles"] as $role){
                    $builder->insert_objectproperty_declaration($role);
                  }
                  break;
                }
             }
          }
        }

        $this->translate_attributes($json, $builder); */
        $this->translate_subsumption($json, $builder);

      }


      function decode($owl, $jsonbuild){}

}
?>
