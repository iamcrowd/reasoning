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
//load('crowdmetapack.php', '../../qapackages/');
load('crowdalcqimetapack.php', '../../qapackages/crowd20/');
load('strategy.php', '../../');
load('metamodel.php', '../../');
load("crowd_checkmeta.php", "../");

//use Wicom\Translator\Strategies\QAPackages\CrowdMetaPack;
use Wicom\Translator\Strategies\QAPackages\crowd20\CrowdALCQIMetaPack;
use Wicom\Translator\Strategies\Strategy;
use Wicom\Translator\Strategies\Metamodel;
use Wicom\Translator\Strategies\Strategydlmeta\DLCheckMeta;

class DLMetaEnrico extends Metamodel{

    protected $output = null;
    protected $global_maxcard = null;
    protected $global_mincard = null;
    protected $maxcardinalities = null;
    protected $check_card = null;

    function __construct(){
      parent::__construct();
      $this->qapack = new CrowdALCQIMetaPack();
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
          $lst = [["disjointclasses" => $disjoint]];
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

    /**
      Return a role given a relationship
    */
    protected function get_rel_signature($json, $rel){
      $js_role = $json["Role"];
      $sig = [];

      foreach ($js_role as $ro) {
        if (strcmp($ro["relationship"], $rel) == 0){
          array_push($sig, $ro["rolename"]);
        }
      }
      return $sig;
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

    protected function subsumes_rel($json, $child, $parent){
      $js_rel = $json["Relationship"]["Subsumption"];

      foreach ($js_rel as $r) {
        if ((strcmp($r["entity child"], $child) == 0) &&
           (strcmp($r["entity parent"], $parent) == 0)){
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
      Return true if two relationships have the same signature.
      It means that they have different roles. Ri(ri1,ri2) and Rj(rj1,rj2), i <> j for all i,j.
      Up to now, binary relationships. Because MM assumes posicionalism, no matter the order of roles into
      the relationshp.

      @note rel structure:
      ["name_rel" => $ot,
      "roles" => $roles];
    */
    protected function same_signature($rel1, $rel2){
        if (in_array($rel1["roles"][0], $rel2["roles"]) &&
           (in_array($rel1["roles"][1], $rel2["roles"]))){

             return true;
        }
        else return false;
    }

    /**
      Relationships with different signature must be disjoint. This function generates such a disjointness.
      Moreover, relationship could not be disjoint if they participate into a subsumption of rels.
      Up to now, relationships are only binary ones.
    */
    function translate_general_axioms($json, $builder){
        $js_obj = $json["Relationship"]["Relationship"];
        $all = [];
        $diff_sig_only = [];
        $signatures = [];

        foreach ($js_obj as $r) {
            $role = [];
            $role = $this->get_rel_signature($json, $r["name"]);

            if (count($role) > 0){
              if (!in_array([$role[0],$role[1]], $signatures) &&
                 (!in_array([$role[1],$role[0]], $signatures))) {

                   array_push($signatures, $role);

                   $a = ["name_rel" => $r["name"],
                         "roles" => $role];
                   array_push($diff_sig_only, $a);
              }
            }
        }

        //only different signatures
        //$diff_sig_only = array_unique($all, SORT_REGULAR);

        $disj_rel = [];
        for ($i = 0; $i < count($diff_sig_only); $i++) {
          $j = $i + 1;
          $not_subsum = true;

          while (($not_subsum) && ($j < count($diff_sig_only))){

            if ($this->subsumes_rel($json, $diff_sig_only[$i]["name_rel"], $diff_sig_only[$j]["name_rel"])){
                $not_subsum = false;
                $child = $diff_sig_only[$i]["name_rel"];

            } elseif ($this->subsumes_rel($json, $diff_sig_only[$j]["name_rel"], $diff_sig_only[$i]["name_rel"])) {
                  $not_subsum = false;
                  $child = $diff_sig_only[$j]["name_rel"];
            }
            $j = $j + 1;
          }

          if (($not_subsum) && ($j >= count($diff_sig_only))){
            array_push($disj_rel, ["class" => $diff_sig_only[$i]["name_rel"]]);
          }
          else if ((!$not_subsum) && ($j < count($diff_sig_only))){
            $not_insert = true;
            $k = 0;

            while (($not_insert) && ($k < count($disj_rel))){
              if (($this->subsumes_rel($json, $child, $disj_rel[$k])) || ($this->subsumes_rel($json, $disj_rel[$k], $child))){
                $not_insert = false;
              }
              $k = $k + 1;
            }
            if (($not_insert) && ($k >= count($disj_rel))){
              array_push($disj_rel, ["class" => $child]);
            }
          }
        }

        // armar axioma disj
        if (count($disj_rel) > 1){
          $lst = [
            ["subclass" => [
                ["intersection" => $disj_rel],
                ["class" => "owl:Nothing"]
               ]
            ]
          ];

          $builder->translate_DL($lst);
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

        // encoding general axioms because of reification. Only will be generated if there exists at least relationships
        // with different signatures nor they are subsumed.

        //$this->translate_general_axioms($json, $builder);

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