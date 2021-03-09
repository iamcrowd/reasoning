<?php
/*

   Copyright 2016 GILIA

   Author: Giménez, Christian. Braun, Germán

   crowdanalizer.php

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

require_once __DIR__ . '/../../../builders/owllinkbuilder.php';
require_once __DIR__ . '/../../../builders/documentbuilder.php';
require_once __DIR__ . '/../../../builders/owlbuilder.php';
require_once __DIR__ . '/answer.php';
require_once __DIR__ . '/ansanalizer.php';

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
        "GetDisjointObjectProperties" => "ObjectPropertySynsets",
        "GetEquivalentObjectProperties" => "SetOfObjectProperties",
        "GetPrefixes" => "Prefixes",
    ];

    function generate_answer($query, $answer, $owl2 = ''){
        parent::generate_answer($query, $answer, $owl2);
    }

    /**
    This function is for going until the first OWLlink query.
    */

    function goto_first_query(){
      $this->owllink_queries->rewind();
      $first_query = key(CrowdAnalizer::ANSWERS_MAP);

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
      $first_response = CrowdAnalizer::ANSWERS_MAP[key(CrowdAnalizer::ANSWERS_MAP)];

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
            </ClassSubClassesPair> */
          foreach ($first_children->children() as $second_children){ //<ClassSynset>
            $tag_second_child = $second_children->getName();

            switch ($tag_second_child){

              case "ClassSynset":
                $class_parent = $second_children->children("owl",TRUE);

                if ($class_parent->count() > 0){
                  $class_parent_name = $class_parent[0]->attributes()[0];
                }
                array_push($hierarchy,$class_parent_name->__toString());  // insert parent
                break;

              case "SubClassSynsets" :
                foreach ($second_children->children() as $third_children){
                  $tag_third_child = $third_children->getName();

                  if ($tag_third_child = "ClassSynset"){
                    $class_child = $third_children->children("owl",TRUE);

                    if ($class_child->count() > 0){
                      $class_child_name = $class_child[0]->attributes()[0];
                    }
                  }
                  array_push($hierarchy,$class_child_name->__toString()); // insert child
                }
              }
          }
          array_push($hierarchies,$hierarchy);
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
            array_push($disjoint,$class_name->__toString());
        }
      }
      return $disjoint;
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
          "GetPrefixes" => [],
          "GetOntologyIRI" => [],
          "DL" => []
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
                if ($name_response = "BooleanResponse"){
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
                  if ($name_response = "BooleanResponse"){
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
                if ($name_response = "ClassSynsets"){
                  $class_query = $this->get_current_owlclass();
                  if ($class_query->count() > 0){
                    $class_name = $class_query[0]->attributes()["IRI"];
                    $disjoint = $this->parse_owllinkdisjoint();
                    $class_d = $class_name->__toString();
                    array_push($bool_responses[$name_query], $disjoint);
                  }
                }
                foreach ($disjoint as $disjoint_class) {
                  array_push($bool_responses["DL"],["disjointclasses" => [
                                                      ["class" => $class_d],
                                                      ["class" => $disjoint_class]]]);
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

              case "GetPrefixes":
                $name_response = $this->owllink_responses->current()->getName();
                if ($name_response = "Prefixes"){
                  $prefixes = $this->parse_prefixes();

                  foreach ($prefixes as $prefix){
                    array_push($bool_responses[$name_query],["prefix" => $prefix[0],"iri" => $prefix[1]]);
                  }
                }
                break;
            }
            $this->owllink_responses->next();
            $this->owllink_queries->next();
      }


      return $bool_responses;
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
        $this->answer->add_equivalences($responses["GetEquivalentClasses"]);

        $ontologyIRI = $responses["GetOntologyIRI"][0];
        $prefixes = $responses["GetPrefixes"];

        // $this->answer->start_owl2_answer($ontologyIRI, [], $prefixes);
        $this->answer->translate_responses($responses["DL"]);
        $this->answer->copyowl2_to_response();
        $this->answer->end_owl2_answer();

        return $this->answer;
    }


	  function incorporate_inferredSubs($iSubs){
		    $this->answer->incorporate_inferredSubs($iSubs);
	  }

    function incorporate_inferredCards($iCards){
        $this->answer->incorporate_inferredCards($iCards);
    }

    function get_equiv($primitive){
      return $this->answer->get_equiv($primitive);
    }

    function get_unsatClasses(){
      return $this->answer->get_unsatClasses();
    }

}
