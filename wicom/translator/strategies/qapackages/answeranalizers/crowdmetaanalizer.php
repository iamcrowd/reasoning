<?php
/*

   Copyright 2020 GILIA

   Author: Braun, Germán

   crowdmetaanalizer.php

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

namespace Wicom\Translator\Strategies\QAPackages\AnswerAnalizers;

load("owllinkbuilder.php", "../../../builders/");
load("documentbuilder.php", "../../../builders/");
load("owlbuilder.php", "../../../builders/");
load("answer.php");
load("ansanalizer.php");

//use Wicom\Translator\Documents\OWLlinkDocument;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\DocumentBuilder;
use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer;
use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\AnsAnalizer;
use \XMLReader;
use \SimpleXMLElement;
use \SimpleXMLIterator;



/**
   crowd Answer Analizer. Analise the answers and generate a new OWLlink document for the new Ontology!

   <IsKBSatisfiable kb="http://localhost/kb1"/>    <OK/>
   <IsClassSatisfiable kb="http://localhost/kb1"> <BooleanResponse result="true"/>
   <GetSubClassHierarchy kb="http://localhost/kb1"/>   <ClassHierarchy>
                                                          <ClassSynset>
                                                            <owl:Class abbreviatedIRI="owl:Nothing"/>
                                                          </ClassSynset>
                                                          <ClassSubClassesPair>
                                                            <ClassSynset>
                                                              <owl:Class abbreviatedIRI="owl:Thing"/>
                                                            </ClassSynset>
                                                            <SubClassSynsets>
                                                              <ClassSynset>
                                                                <owl:Class IRI="A"/>
                                                              </ClassSynset>
                                                            </SubClassSynsets>
                                                          </ClassSubClassesPair>
                                                      </ClassHierarchy>
    <GetDisjointClasses kb="http://localhost/kb1">   <ClassSynsets>
                                                        <ClassSynset>
                                                          <owl:Class abbreviatedIRI="owl:Nothing"/>
                                                        </ClassSynset>
                                                     </ClassSynsets>
    <GetEquivalentClasses kb="http://localhost/kb1"> <SetOfClasses>
                                                        <owl:Class IRI="A"/>
                                                     </SetOfClasses>

 */
class CrowdMetaAnalizer extends AnsAnalizer{

    /**
       XMLReader instance for parsing the query given to the
       reasoner.
     */
    protected $query_reader = null;
    /**
       XMLReader instance for parsing the reasoner answer.
     */
    protected $answer_reader = null;

    protected $c_strategy = null;

    /**
       Map between Queries and propper correct answers
       This method also generates the new ontology owllink after reasoning.

       Used for filtering XML tags to the ones we care.
     */
    const ANSWERS_MAP = [
        "IsKBSatisfiable" => "BooleanResponse",
        "IsClassSatisfiable" => "BooleanResponse",
        "GetSubClassHierarchy" => "ClassHierarchy",
        "GetDisjointClasses" => "ClassSynsets",
        "GetEquivalentClasses" => "SetOfClasses",
        "IsObjectPropertySatisfiable" => "BooleanResponse",
        "IsDataPropertySatisfiable" => "BooleanResponse",
        "GetSubObjectPropertyHierarchy" => "ObjectPropertyHierarchy",
        "GetDisjointObjectProperties" => "SetOfObjectPropertySynsets",
        "GetEquivalentObjectProperties" => "SetOfObjectProperties",
        "GetDisjointDataProperties" => "DataPropertySynsets",
        "GetEquivalentDataProperties" => "DataPropertySynonyms",
        "GetPrefixes" => "Prefixes",
        "IsEntailed" => "BooleanResponse",
    ];

    function generate_answer($query, $answer, $owl2 = ''){
        parent::generate_answer($query, $answer, $owl2);
    }

    function set_c_strategy($strategy){
      $this->c_strategy = $strategy;
    }
    /**
    This function is for going until the first OWLlink query.
    */

    function goto_first_query(){
      $this->owllink_queries->rewind();
      $first_query = key(CrowdMetaAnalizer::ANSWERS_MAP);

      while (($this->owllink_queries->valid()) and ($first_query != NULL) and
             ($this->owllink_queries->current()->getName() != $first_query)){

               $this->owllink_queries->next();
      }
    }

    /**
    This function is for going until the first OWLlink response.
    @return $ontologyIRI a String with the current Ontology IRI.
    */

    function goto_first_response(){
      $this->owllink_responses->rewind();
      $first_response = CrowdMetaAnalizer::ANSWERS_MAP[key(CrowdMetaAnalizer::ANSWERS_MAP)];

      if ($this->owllink_responses->current()->getName() == "KB"){
        $ontologyIRI = $this->owllink_responses->current()->attributes()[0]->__toString();
      }

      while (($this->owllink_responses->valid()) and ($first_response != NULL) and
             ($this->owllink_responses->current()->getName() != $first_response)){

               $this->owllink_responses->next();
      }

      return $ontologyIRI;
    }

    function get_current_owlclass($query = true){
      if ($query){
          return $this->owllink_queries->current()->children("owl",TRUE);
      }
      else {
        return $this->owllink_responses->current()->children("owl",TRUE);
      }
    }

    /**
    Parsing OWLlink <ClassHierarchy> tag. This function returns an array of subhierarchies.
    <ClassSubClassesPair>
                  <ClassSynset>
                    <owl:Class abbreviatedIRI="owl:Thing"/>
                  </ClassSynset>
                  <SubClassSynsets>
                    <ClassSynset>
                      <owl:Class IRI="A"/>
                    </ClassSynset>
                  </SubClassSynsets>
                </ClassSubClassesPair>
    */

    function parse_owllinkhierarchy(){
      $hierarchies = [];

      foreach ($this->owllink_responses->current()->children() as $first_children) {
        //<ClassSynset><owl:Class abbreviatedIRI="owl:Nothing"/></ClassSynset>
        $tag_first_child = $first_children->getName();
        if (($tag_first_child != "ClassSynset") and ($tag_first_child = "ClassSubClassesPair")){
          $hierarchy = [];
//          ;
/*          <ClassSubClassesPair>
              <ClassSynset>
                <owl:Class abbreviatedIRI="owl:Thing"/>
              </ClassSynset>
              <SubClassSynsets>
                <ClassSynset>
                  <owl:Class IRI="A"/>
                </ClassSynset>
              </SubClassSynsets>
            </ClassSubClassesPair>

            <ClassSubClassesPair>
              <ClassSynset>
                <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#C"/>
                <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#E"/>
              </ClassSynset>
              <SubClassSynsets>
                <ClassSynset>
                  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#F"/>
                </ClassSynset>
              </SubClassSynsets>
            </ClassSubClassesPair>
            */
          foreach ($first_children->children() as $second_children){ //<ClassSynset>
            $tag_second_child = $second_children->getName();

            switch ($tag_second_child){

              case "ClassSynset":  //parent/s
                $class_parent = $second_children->children("owl",TRUE);
                $class_parent_name = [];

                for ($i=0; $i < $class_parent->count(); $i++) {
                  array_push($class_parent_name, $class_parent[$i]->attributes()[0]);
                }
                break;

              case "SubClassSynsets" : //child/s
                foreach ($second_children->children() as $third_children){
                  $tag_third_child = $third_children->getName();

                  if ($tag_third_child = "ClassSynset"){
                    $class_child = $third_children->children("owl",TRUE);
                    $class_child_name = [];

                    for ($i=0; $i < $class_child->count(); $i++){
                      array_push($class_child_name, $class_child[$i]->attributes()[0]);
                    }
                  }
                }
              }
          }
          foreach ($class_parent_name as $parent_e) {
            foreach ($class_child_name as $child_e) {
              array_push($hierarchies, [$parent_e->__toString(),$child_e->__toString()]);
            }
          }
        }
      }
      return $hierarchies;
    }

    /**
    Parsing OWLlink <ClassSynsets> tag. This function returns an array of disjoint classes.
    <ClassSynsets>
      <ClassSynset>
        <owl:Class abbreviatedIRI="owl:Nothing"/>
      </ClassSynset>
      <ClassSynset>
        <owl:Class IRI="B"/>
      </ClassSynset>
    </ClassSynsets>
    */

    function parse_owllinkdisjoint(){
      $disjoint = [];

      foreach ($this->owllink_responses->current()->children() as $children){ //<ClassSynset>
        $tag_child = $children->getName();

        if ($tag_child = "ClassSynset"){
            $class = $children->children("owl",TRUE);

            if ($class->count() > 0){
              $class_name = $class[0]->attributes()[0];
            }
            array_push($disjoint, $class_name->__toString());
        }
      }
      return $disjoint;
    }

    /**
    Parsing OWLlink <SetOfObjectPropertySynsets> tag. This function returns an array of disjoint object properties.
    <SetOfObjectPropertySynsets>
      <ObjectPropertySynset>
        <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#bottomObjectProperty"/>
      </ObjectPropertySynset>
    </SetOfObjectPropertySynsets>
    */

    function parse_owllinkOPdisjoint(){
      $op_disjoint = [];

      foreach ($this->owllink_responses->current()->children() as $children){ //<ClassSynset>
        $tag_child = $children->getName();

        if ($tag_child = "ObjectPropertySynset"){
            $op = $children->children("owl",TRUE);

            if ($op->count() > 0){
              $op_name = $op[0]->attributes()[0];
            }
            array_push($op_disjoint,$op_name->__toString());
        }
      }
      return $op_disjoint;
    }

    /**
    Parsing OWLlink <DataPropertySynsets> tag. This function returns an array of disjoint data properties.
    <DataPropertySynsets>
      <DataPropertySynset>
        <owl:DataProperty IRI="http://www.w3.org/2002/07/owl#bottomDataProperty"/>
      </DataPropertySynset>
    </DataPropertySynsets>
    */

    function parse_owllinkDPdisjoint(){
      $dp_disjoint = [];

      foreach ($this->owllink_responses->current()->children() as $children){ //<DataPropertySynset>
        $tag_child = $children->getName();

        if ($tag_child = "DataPropertySynset"){
            $dp = $children->children("owl",TRUE);

            if ($dp->count() > 0){
              $dp_name = $dp[0]->attributes()[0];
            }
            array_push($dp_disjoint,$dp_name->__toString());
        }
      }
      return $dp_disjoint;
    }

    /**
    Parsing OWLlink <SetOfClasses> tag. This function returns an array of equivalent classes.
    <SetOfClasses>
      <owl:Class IRI="D"/>
      <owl:Class IRI="C"/>
    </SetOfClasses>
    */
    function parse_owllinkequivalent(){
      $equivalent = [];

      $owl_children = $this->owllink_responses->current()->children("owl",TRUE);

      foreach ($owl_children as $child){
        $class_name = $child[0]->attributes()[0];
        array_push($equivalent,$class_name->__toString());
      }

      return $equivalent;
    }

    /**
    Parsing OWLlink <SetOfObjectProperties> tag. This function returns an array of equivalent object properties.
    <SetOfObjectProperties>
      <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#person"/>
    </SetOfObjectProperties>
    */
    function parse_owllinkOPequivalent(){
      $op_equivalent = [];

      $owl_children = $this->owllink_responses->current()->children("owl",TRUE);

      foreach ($owl_children as $child){
        $op_name = $child[0]->attributes()[0];
        array_push($op_equivalent, $op_name->__toString());
      }

      return $op_equivalent;
    }

    /**
    Parsing OWLlink <DataPropertySynonyms> tag. This function returns an array of equivalent data properties.
    <DataPropertySynonyms>
      <owl:DataProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#Name"/>
    </DataPropertySynonyms>
    */
    function parse_owllinkDPequivalent(){
      $dp_equivalent = [];

      $owl_children = $this->owllink_responses->current()->children("owl",TRUE);

      foreach ($owl_children as $child){
        $dp_name = $child[0]->attributes()[0];
        array_push($dp_equivalent, $dp_name->__toString());
      }

      return $dp_equivalent;
    }

    /**
    Parsing OWLlink <Prefixes> tag. This function returns an array of prefixes.
    <Prefixes>
      <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
      <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
      <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
      <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
      <Prefix name="test" fullIRI="http://www.owllink.org/test/ont#"/>
      <Prefix name="myOnt" fullIRI="http://www.owllink.org/examples/myOntology#"/>
    </Prefixes>
    */

    function parse_prefixes(){
      $prefix = [];

      foreach ($this->owllink_responses->current()->children() as $children){ //<Prefix>
        $tag_child = $children->getName();

        if ($tag_child = "Prefix"){
          $name = $children[0]->attributes()[0];
          $iri = $children[0]->attributes()[1];
          array_push($prefix,[$name->__toString(), $iri->__toString()]);
        }
      }
      return $prefix;
    }


    /**
    This function starts parsing OWLlink responses file using an Concrete Iterator and outs a new
    array of responses to be inserted in the new ontology.
    It delegates to SimpleXMLIterator and OWLlinkBuilder
    */

    function get_responses(){

      $bool_responses = [
          "IsKBSatisfiable" => "false",
          "IsClassSatisfiable" => [],
          "IsObjectPropertySatisfiable" => [],
          "IsDataPropertySatisfiable" => [],
          "GetSubClassHierarchy" => [],
          "GetDisjointClasses" => [],
          "GetEquivalentClasses" => [],
          "GetDisjointObjectProperties" => [],
          "GetEquivalentObjectProperties" => [],
          "GetDisjointDataProperties" => [],
          "GetEquivalentDataProperties" => [],
          "GetPrefixes" => [],
          "GetOntologyIRI" => [],
          "DL" => [],
          "OWLlink error" => [],
          "isEntailedMaxCard" => []
      ];

      $this->goto_first_query();
      $uri = $this->goto_first_response();

      $ontologyIRI = ["prefix" => "" , "value" => $uri];

      array_push($bool_responses["GetOntologyIRI"], $ontologyIRI);

      while (($this->owllink_responses->valid()) and ($this->owllink_queries->valid())){

            $name_query = $this->owllink_queries->current()->getName();

            switch ($name_query){
              case "IsKBSatisfiable":
                $name_response = $this->owllink_responses->current()->getName();
                if ($name_response = "BooleanResponse"){
                  $attr_response = $this->owllink_responses->current()->attributes()["result"];
                  $bool_responses[$name_query] = $attr_response->__toString();
                }
                break;

              case "IsClassSatisfiable":
                $name_response = $this->owllink_responses->current()->getName();
                if ($name_response = "BooleanResponse"){
                  $attr_response = $this->owllink_responses->current()->attributes()["result"];

                  $class_query = $this->get_current_owlclass();
                  if ($class_query->count() > 0){
                    $class_name = $class_query[0]->attributes()["IRI"];

                    if (!isset($class_name)){
                      $class_name = $class_query[0]->attributes()["abbreviatedIRI"];
                    }
                    array_push($bool_responses[$name_query], [$attr_response->__toString(),$class_name->__toString()]);
                  }
                }
                break;

              case "IsObjectPropertySatisfiable":
                $name_response = $this->owllink_responses->current()->getName();

                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());
                }
                elseif ($name_response = "BooleanResponse"){
                  $attr_response = $this->owllink_responses->current()->attributes()["result"];

                  $op_query = $this->get_current_owlclass();
                  if ($op_query->count() > 0){
                    $op_name = $op_query[0]->attributes()["IRI"];

                    if (!isset($op_name)){
                      $op_name = $op_query[0]->attributes()["abbreviatedIRI"];
                    }
                    array_push($bool_responses[$name_query], [$attr_response->__toString(),$op_name->__toString()]);
                  }
                }
                break;

              case "IsDataPropertySatisfiable":
                $name_response = $this->owllink_responses->current()->getName();

                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());
                }
                elseif ($name_response = "BooleanResponse"){
                  $attr_response = $this->owllink_responses->current()->attributes()["result"];

                  $dp_query = $this->get_current_owlclass();
                  if ($dp_query->count() > 0){
                    $dp_name = $dp_query[0]->attributes()["IRI"];

                    if (!isset($dp_name)){
                      $dp_name = $class_query[0]->attributes()["abbreviatedIRI"];
                    }
                    array_push($bool_responses[$name_query], [$attr_response->__toString(),$dp_name->__toString()]);
                  }
                }
                break;

              case "GetSubClassHierarchy":   // {father,[childs]}
                $name_response = $this->owllink_responses->current()->getName();
                if ($name_response = "ClassHierarchy"){
                  $hierarchy = $this->parse_owllinkhierarchy();
                  array_push($bool_responses[$name_query],$hierarchy);
                }
                foreach ($hierarchy as $subhier) {
                  $parent = $subhier[0];
                  $counter = 1;
                  while ($counter < count($subhier)){
                    // translate response to DL
                    array_push($bool_responses["DL"],["subclass" => [
                                                      ["class" => $subhier[$counter]],
                                                      ["class" => $parent]]]);
                    $counter = $counter + 1;
                  }
                }
                break;

              case "GetDisjointClasses":   // {class,[disjointclasses]}
                $name_response = $this->owllink_responses->current()->getName();

                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());

                } elseif ($name_response = "ClassSynsets"){
                    $class_query = $this->get_current_owlclass();
                    if ($class_query->count() > 0){
                      $class_name = $class_query[0]->attributes()["IRI"];
                      $disjoint = $this->parse_owllinkdisjoint();
                      $class_d = $class_name->__toString();
                      array_push($bool_responses[$name_query], $disjoint);
                    }

                    foreach ($disjoint as $disjoint_class) {
                      array_push($bool_responses["DL"],["disjointclasses" => [
                                                      ["class" => $class_d],
                                                      ["class" => $disjoint_class]]]);
                    }
                }
                break;

              case "GetDisjointObjectProperties":   // {op,[disjointop]}
                $name_response = $this->owllink_responses->current()->getName();

                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());

                } elseif ($name_response = "SetOfObjectPropertySynsets"){
                    $op_query = $this->get_current_owlclass();
                    if ($op_query->count() > 0){
                      $op_name = $op_query[0]->attributes()["IRI"];
                      $op_disjoint = $this->parse_owllinkOPdisjoint();
                      $op_d = $op_name->__toString();
                      array_push($bool_responses[$name_query], $op_disjoint);
                    }

                    foreach ($op_disjoint as $disjoint_op_el) {
                      array_push($bool_responses["DL"],["disjointobjectproperty" => [
                                                      ["objectproperty" => $op_d],
                                                      ["objectproperty" => $disjoint_op_el]]]);
                    }
                }
                break;

              case "GetDisjointDataProperties":   // {dp,[disjointdp]}
                $name_response = $this->owllink_responses->current()->getName();
                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());

                } elseif ($name_response = "DataPropertySynsets"){
                    $dp_query = $this->get_current_owlclass();
                    if ($dp_query->count() > 0){
                      $dp_name = $dp_query[0]->attributes()["IRI"];
                      $dp_disjoint = $this->parse_owllinkDPdisjoint();
                      $dp_d = $dp_name->__toString();
                      array_push($bool_responses[$name_query], $dp_disjoint);
                    }
                    foreach ($dp_disjoint as $disjoint_dp_el) {
                      array_push($bool_responses["DL"],["disjointdataproperty" => [
                                                      ["dataproperty" => $dp_d],
                                                      ["dataproperty" => $disjoint_dp_el]]]);
                    }
                }
                break;

              case "GetEquivalentClasses":
                $name_response = $this->owllink_responses->current()->getName();
                if ($name_response = "SetOfClasses"){
                  $class_query = $this->get_current_owlclass();
                  if ($class_query->count() > 0){
                    $class_name = $class_query[0]->attributes()["IRI"];
                    $equivalent = $this->parse_owllinkequivalent();
                    $class_e = $class_name->__toString();
                    // [[class1, class2], [class3, class4, $class5]]
                    array_push($bool_responses[$name_query], $equivalent);
                  }
                }
                foreach ($equivalent as $equiv_class) {
                  array_push($bool_responses["DL"],["equivalentclasses" => [
                                                      ["class" => $class_e],
                                                      ["class" => $equiv_class]]]);
                }
                break;

              case "GetEquivalentObjectProperties":
                $name_response = $this->owllink_responses->current()->getName();
                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());

                } elseif ($name_response = "SetOfObjectProperties"){
                    $op_query = $this->get_current_owlclass();
                    if ($op_query->count() > 0){
                      $op_name = $op_query[0]->attributes()["IRI"];
                      $op_equivalent = $this->parse_owllinkOPequivalent();
                      $op_e = $op_name->__toString();
                      // [[op1, op2], [op3, op4, op5]]
                      array_push($bool_responses[$name_query], $op_equivalent);
                    }

                    foreach ($op_equivalent as $equiv_op_el) {
                      array_push($bool_responses["DL"],["equivalentobjectproperty" => [
                                                      ["objectproperty" => $op_e],
                                                      ["objectproperty" => $equiv_op_el]]]);
                    }
                }
                break;

              case "GetEquivalentDataProperties":
                $name_response = $this->owllink_responses->current()->getName();

                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());
                }
                elseif ($name_response = "DataPropertySynonyms"){
                  $dp_query = $this->get_current_owlclass();
                  if ($dp_query->count() > 0){
                    $dp_name = $dp_query[0]->attributes()["IRI"];
                    $dp_equivalent = $this->parse_owllinkDPequivalent();
                    $dp_e = $dp_name->__toString();
                    // [[op1, op2], [op3, op4, op5]]
                    array_push($bool_responses[$name_query], $dp_equivalent);
                  }
                  foreach ($dp_equivalent as $equiv_dp_el) {
                    array_push($bool_responses["DL"],["equivalentdataproperty" => [
                                                      ["dataproperty" => $dp_e],
                                                      ["dataproperty" => $equiv_dp_el]]]);
                  }
                }
                break;

              case "GetPrefixes":
                $name_response = $this->owllink_responses->current()->getName();
                if (strcmp($name_response, "Error") == 0){
                  $error_response = $this->owllink_responses->current()->attributes()["error"];
                  array_push($bool_responses["OWLlink error"], $error_response->__toString());

                } elseif ($name_response = "Prefixes"){
                    $prefixes = $this->parse_prefixes();

                    foreach ($prefixes as $prefix){
                      array_push($bool_responses[$name_query],["prefix" => $prefix[0],"iri" => $prefix[1]]);
                    }
                }
                break;

              case "IsEntailed": // what if we are checking cardinalities
                if ($this->c_strategy->get_check_cardinalities()){
                  $c_max = $this->c_strategy->get_global_maxcardinality();
                  $c_maxcard_encoded = $this->c_strategy->get_maxcardinalities();
                  $result = [];
                  $maxcardresponses = [];

                  foreach ($c_maxcard_encoded as $c_maxcard_encoded_el) {
                    $responses_array = [];
                    $c_maxcard_encoded_el["query responses"] = [];

                    for ($i = 1; $i <= $c_max; $i++) {
                      $name_response = $this->owllink_responses->current()->getName();
                      if ($name_response = "BooleanResponse"){
                        $attr_response = $this->owllink_responses->current()->attributes()["result"];

                        $class_query = $this->get_current_owlclass();
                        $query_card = $class_query->SubClassOf->ObjectMaxCardinality->attributes()["cardinality"]->__toString();
                        $response = $attr_response->__toString();
                        array_push($responses_array, ["query card" => $query_card, "bool" => $response]);
                      }
                      $this->owllink_responses->next();
                      $this->owllink_queries->next();
                    }
                    $c_maxcard_encoded_el["query responses"] = array_merge($c_maxcard_encoded_el["query responses"], $responses_array);
                    array_push($result, $c_maxcard_encoded_el);
                  }
                  $withInferences = [];
                  $withInferences = $this->filter_stricter_MaxCard($result);
                  $bool_responses["DL"] = array_merge($bool_responses["DL"], $withInferences);
                  $bool_responses["isEntailedMaxCard"] = array_merge($bool_responses["isEntailedMaxCard"], $withInferences);
                }
              break;
            }
            $this->owllink_responses->next();
            $this->owllink_queries->next();
      }


      return $bool_responses;
    }

    /**
    Filter stricter max cardinalities for the role given as "op". Elements "class", "op", "rel" and "maxcard" represent
    the original role defined in the MM given as input. The last element "query results" includes the responses for each
    query on the cardinality of the current role.

    array(1) {
      [0]=>
      array(2) {
        [0]=>
        array(5) {
          ["class"]=>
          string(40) "http://crowd.fi.uncoma.edu.ar/kb1#Person"
          ["op"]=>
          string(40) "http://crowd.fi.uncoma.edu.ar/kb1#person"
          ["rel"]=>
          string(42) "http://crowd.fi.uncoma.edu.ar/kb1#enrolled"
          ["maxcard"]=>
          string(1) "2"
          [0]=>
          array(9) {
            [0]=>
            array(2) {
              ["query card"]=>
              string(1) "1"
              ["bool"]=>
              string(5) "false"
            }
            [1]=>
            array(2) {
              ["query card"]=>
              string(1) "2"
              ["bool"]=>
              string(5) "false"
            }
          }
        }
    */
    function filter_stricter_MaxCard($anArrayOfMaxCard){
      $card_max_ax = [];

      foreach ($anArrayOfMaxCard as $role) {
        $a_responses = $role["query responses"];
        $i = 0;
        while ($i < count($a_responses) && !(filter_var($a_responses[$i]["bool"], FILTER_VALIDATE_BOOLEAN))) {
          $i++;
        }
        if (filter_var($a_responses[$i]["bool"], FILTER_VALIDATE_BOOLEAN)){
          $el = [
                          ["subclass" => [
                            ["class" => $role["class"]],
                            ["maxcard" => [
                                          $a_responses[$i]["query card"],
                                          ["inverse" => ["role" => $role["op"]]],
                                          ["filler" => $role["rel"]]
                                          ]
                            ]
                          ]
                        ]
                      ];
          $card_max_ax = array_merge($card_max_ax, $el);
        }
      }
      return $card_max_ax;
    }


    function analize(){

        $responses = $this->get_responses();

        $val = $responses["IsKBSatisfiable"];
        $this->answer->set_kb_satis($val == "true");

        $col_class = $responses["IsClassSatisfiable"];
        foreach ($col_class as $val){
            if ($val[0] == "true"){
                $this->answer->add_satis_class($val[1]);
            }else{
                $this->answer->add_unsatis_class($val[1]);
            }
        }

        $col_op = $responses["IsObjectPropertySatisfiable"];
        foreach ($col_op as $val){
            if ($val[0] == "true"){
                $this->answer->add_satis_op($val[1]);
            }else{
                $this->answer->add_unsatis_op($val[1]);
            }
        }

        $col_dp = $responses["IsDataPropertySatisfiable"];
        foreach ($col_dp as $val){
            if ($val[0] == "true"){
                $this->answer->add_satis_dp($val[1]);
            }else{
                $this->answer->add_unsatis_dp($val[1]);
            }
        }

        $this->answer->add_subsumptions($responses["GetSubClassHierarchy"]);
        $this->answer->add_disjunctions($responses["GetDisjointClasses"]);
        $this->answer->add_disjunctions_op($responses["GetDisjointObjectProperties"]);
        $this->answer->add_disjunctions_dp($responses["GetDisjointDataProperties"]);
        $this->answer->add_equivalences($responses["GetEquivalentClasses"]);
        $this->answer->add_equivalences_op($responses["GetEquivalentObjectProperties"]);
        $this->answer->add_equivalences_dp($responses["GetEquivalentDataProperties"]);

        $ontologyIRI = $responses["GetOntologyIRI"][0];
        $prefixes = $responses["GetPrefixes"];

        $this->answer->add_stricter_cardinalities($responses["isEntailedMaxCard"]);

        $this->answer->translate_responses($responses["DL"]);
        //$this->answer->copyowl2_to_response();
        $this->answer->end_owl2_answer();

        $this->answer->add_beauty_responses($responses["DL"]);

        return $this->answer;
    }


	  function incorporate_inferredSubs($iSubs){
		    $this->answer->incorporate_inferredSubs($iSubs);
	  }

    function incorporate_inferredCards($iCards){
        $this->answer->incorporate_inferredCards($iCards);
    }


// Beauty Responses!

    public function get_beatified_responses(){
      return $this->answer->to_beatified_json();
    }

    function get_kb_status(){
      return $this->answer->get_kb_status();
    }

    function get_unsatClasses(){
      return $this->answer->get_unsatClasses();
    }

    function get_satClasses(){
      return $this->answer->get_satClasses();
    }

    function get_unsatObjectProperties(){
      return $this->answer->get_unsatObjectProperties();
    }

    function get_satObjectProperties(){
      return $this->answer->get_satObjectProperties();
    }

    function get_equiv($primitive){
      return $this->answer->get_equiv($primitive);
    }

    function get_all_equiv_class(){
      return $this->answer->get_all_equiv_class();
    }

    function get_subclass($class){
      return $this->answer->get_subclass($class);
    }

    function get_disjoint_class($class){
      return $this->answer->get_disjoint_class($class);
    }

    function get_all_disjoint_class(){
      return $this->answer->get_all_disjoint_class();
    }

    function get_stricter_cardinalities(){
      return $this->answer->get_stricter_cardinalities();
    }

    function get_reasoning_warning(){
      return $this->get_responses()["OWLlink error"];
    }

}
