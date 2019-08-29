<?php
/*

   Copyright 2018 GILIA

   Author: GILIA

   ontoextractor.php

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

namespace Wicom\Translator\Strategies\SPARQLDL;


load("graphicalaxioms.php");
load("sparqldlconnector.php", "../../../reasoner/");
load("runner.php", "../../../reasoner/");

use \XMLReader;
use \SimpleXMLElement;
use \SimpleXMLIterator;
use \XMLWriter;
use Wicom\Translator\Strategies\Sparqldl\GraphicalAxioms;
use Wicom\Reasoner\Runner;
use Wicom\Reasoner\SparqldlConnector;

/**
  This class uses the SPARQL-DL language to extract an OWL 2 Ontology from an OWL Document and returns
  an intermediate representation for each OWL 2 axiom. It also parses graphical annotations.

  SPARQL-DL expresiveness allows the following Query Patterns:
  - Class(a)                      (supported by crowd importer)
  - Property(a)                   (supported by crowd importer)
  - Individual(a)
  - Type(a, b)
  - PropertyValue(a, b, c)
  - EquivalentClass(a, b)         (supported by crowd importer)
  - SubClassOf(a, b)              (supported by crowd importer)
  - EquivalentProperty(a, b)      (supported by crowd importer)
  - SubPropertyOf(a, b)           (supported by crowd importer)
  - InverseOf(a, b)               (supported by crowd importer)
  - ObjectProperty(a)             (supported by crowd importer)
  - DataProperty(a)               (supported by crowd importer)
  - Functional(a)
  - InverseFunctional(a)
  - Transitive(a)
  - Symmetric(a)
  - Reflexive(a)
  - Irreflexive(a)
  - SameAs(a, b)
  - DisjointWith(a, b)            (supported by crowd importer)
  - DifferentFrom(a, b)
  - ComplementOf(a, b)
  - Annotation(a, b, c)           (supported by crowd importer)
  - StrictSubClassOf(a, b)        (supported by crowd importer)
  - DirectSubClassOf(a, b)        (supported by crowd importer)
  - DirectType(a, b)
  - StrictSubPropertyOf(a, b)     (supported by crowd importer)
  - DirectSubPropertyOf(a, b)     (supported by crowd importer)

  @see http://derivo.de/en/resources/sparql-dl-api/sparql-dl-syntax/
  @todo refactor removing '#', '/' delimiters in IRI into a protected function()
*/

class OntoExtractor {
  function __construct(){
    $this->intermediate = new GraphicalAxioms();
    $this->sparqldl = null;
  }

  function run_sparqldl($owl_string){
    $this->sparqldl = new Runner(new SparqldlConnector());
    $this->sparqldl->run($owl_string);
    return $this->get_sparqldl_answers();
  }

  function get_sparqldl_answers(){
    return $this->sparqldl->get_answers();
  }

  function get_graphicalAxioms(){
    return $this->intermediate->get_axioms();
  }

  /**
  This function returns a short name for a OWL 2 entity removing prefix expansions
  */

  public function remove_prefixExpansion($value){
    $hash_pos = stripos($value, '#');  //looking for hash to remove prefix

    if ($hash_pos !== false){
      $short_name = mb_substr($value, $hash_pos + 1);
    } else {
      $slash_pos = strrpos($value, '/'); //looking for the latest slash to remove prefix

      if ($slash_pos !== false){
        $short_name = mb_substr($value, $slash_pos + 1);
      }elseif ((!strcmp($value, "") == 0)) {
        $short_name = $value; // if value does not have an expanded prefix
      }
    }
    return $short_name;
  }

  /**
    $axiom = [value,...,value]
  */
  function getClassAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $class){
      $classaxiom = $class[$vars]["value"];

      if (!in_array($classaxiom, $axioms)){
        array_push($axioms, $classaxiom);
      }
    }
    return $axioms;
  }

  /**
    $axiom = [value,...,value]
  */
  function getObjectPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $objprop){
      $objpropaxiom = $objprop[$vars]["value"];

      if (!in_array($objpropaxiom, $axioms)){
        array_push($axioms, $objpropaxiom);
      }
    }
    return $axioms;
  }

  /**
    $axiom = [value,...,value]
  */
  function getDataPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $dataprop){
      $datapropaxiom = $dataprop[$vars]["value"];

      if (!in_array($datapropaxiom, $axioms)){
        array_push($axioms, $datapropaxiom);
      }
    }
    return $axioms;
  }

  /**
    $axiom = [objprop => domain]
  */
  function getDomainAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $domain){
      $classdomain = $domain[$vars[0]]["value"];
      $objpropdomain = $domain[$vars[1]]["value"];

      if (!in_array([$objpropdomain => $classdomain], $axioms)){
        array_push($axioms, [$objpropdomain => $classdomain]);
      }
    }
    return $axioms;
  }

  /**
    $axiom = [dataprop => domain]
  */
  function getDomainDataPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $domaindp){
      $classdomain = $domaindp[$vars[0]]["value"];
      $datapropdomain = $domaindp[$vars[1]]["value"];

      if (!in_array([$datapropdomain => $classdomain], $axioms)){
        array_push($axioms, [$datapropdomain => $classdomain]);
      }
    }
    return $axioms;
  }

  /**
    $axiom = [objprop => range]
  */
  function getRangeAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $range){
      $classrange = $range[$vars[0]]["value"];
      $objproprange = $range[$vars[1]]["value"];

      if (!in_array([$objproprange => $classrange], $axioms)){
        array_push($axioms, [$objproprange => $classrange]);
      }
    }
    return $axioms;
  }

  /**
    $axiom = [dataprop => range]
  */
  function getRangeDataPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $rangedp){
      $classrange = $rangedp[$vars[0]]["value"];
      $dataproprange = $rangedp[$vars[1]]["value"];

      if (!in_array([$dataproprange => $classrange], $axioms)){
        array_push($axioms, [$dataproprange => $classrange]);
      }
    }
    return $axioms;
  }

  /**
    $axiom = ["subclass" => [$subclass, $parentclass]]
  */
  function getStrictSubClassAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $sub){
      $subclass = $sub[$vars[0]]["value"];
      $parentclass = $sub[$vars[1]]["value"];

      if (!in_array(["subclass" => [$subclass, $parentclass]], $axioms)){
        array_push($axioms, ["subclass" => [$subclass, $parentclass]]);
      }
    }
    return $axioms;
  }

  /**
    $axiom = ["subobjectproperty" => [$subobjectproperty, $parentobjectproperty]]
  */
  function getStrictSubObjectPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $sub){
      $subobjectproperty = $sub[$vars[0]]["value"];
      $parentobjectproperty = $sub[$vars[1]]["value"];

      if (!in_array(["subobjectproperty" => [$subobjectproperty, $parentobjectproperty]], $axioms)){
        array_push($axioms, ["subobjectproperty" => [$subobjectproperty, $parentobjectproperty]]);
      }
    }
    return $axioms;
  }

  /**
    $axiom = ["equivalentclasses" => [$class1, $class2]]
  */
  function getEquivalentClassAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $eq){
      $eq1 = $eq[$vars[0]]["value"];
      $eq2 = $eq[$vars[1]]["value"];

      if (strcmp($eq1, $eq2) != 0){
        if (!in_array(["equivalentclasses" => [$eq1, $eq2]], $axioms)){
          if (!in_array(["equivalentclasses" => [$eq2, $eq1]], $axioms)){

            array_push($axioms, ["equivalentclasses" => [$eq1, $eq2]]);
          }
        }
      }
    }
    return $axioms;
  }

  /**
    $axiom = ["disjointclasses" => [$class1, $class2]]
  */
  function getDisjointClassAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $disj){
      $d1 = $disj[$vars[0]]["value"];
      $d2 = $disj[$vars[1]]["value"];

      if (strcmp($d1, $d2) != 0){
        if (!in_array(["disjointclasses" => [$d1, $d2]], $axioms)){
          if (!in_array(["disjointclasses" => [$d2, $d1]], $axioms)){

            array_push($axioms, ["disjointclasses" => [$d1, $d2]]);
          }
        }
      }
    }
    return $axioms;
  }

  /**
    $axiom = ["equivalentobjectproperty" => [$op1, $op2]]
  */
  function getEquivalentObjectPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $eq){
      $eq1 = $eq[$vars[0]]["value"];
      $eq2 = $eq[$vars[1]]["value"];

      if (strcmp($eq1, $eq2) != 0){
        if (!in_array($axioms, ["equivalentobjectproperty" => [$eq1, $eq2]])){
          if (!in_array($axioms, ["equivalentobjectproperty" => [$eq2, $eq1]])){

            array_push($axioms, ["equivalentobjectproperty" => [$eq1, $eq2]]);
          }
        }
      }
    }
    return $axioms;
  }

  /**
    $axiom = ["disjointobjectproperty" => [$op1, $op2]]
    @comment Currently, SPARQL-DL does not support DisjointWith for objectproperties.
    "Query engine error: Given entity in first argument of atom DisjointWith() is not a class."

  */
  function getDisjointObjectPropertyAxioms($vars, $results){
    $axioms = [];

    foreach ($results as $disj){
      $d1 = $disj[$vars[0]]["value"];
      $d2 = $disj[$vars[1]]["value"];

      if (strcmp($d1, $d2) != 0){
        if (!in_array($axioms, ["disjointobjectproperty" => [$d1, $d2]])){
          if (!in_array($axioms, ["disjointobjectproperty" => [$d2, $d1]])){

            array_push($axioms, ["disjointobjectproperty" => [$d1, $d2]]);

          }
        }
      }
    }
    return $axioms;
  }



  public function extractor($owl_string){
    $sparqldl_res = $this->run_sparqldl($owl_string);

    $sparqldl_an = [];

    foreach ($sparqldl_res as $elem_res){
      $head = $elem_res["head"];
      $result = $elem_res["results"];

      if (!empty($head)){
        $vars = $head["vars"][0];
        $varlength = count($head["vars"]);

        switch ($vars) {
          case "class" :
            if ($varlength <= 1){
              $querypattern = ["Class", "class"];
              $classaxioms = $this->getClassAxioms("class", $result["bindings"]);
              $this->intermediate->prepareClassAxioms($querypattern[0], $classaxioms);
              break;
            }
          case "objectproperty" :
            if ($varlength <= 1){
              $querypattern = ["ObjectProperty", "objectproperty"];
              $objpropaxioms = $this->getObjectPropertyAxioms("objectproperty", $result["bindings"]);
              $this->intermediate->prepareObjectPropertyAxioms($querypattern[0], $objpropaxioms);
              break;
            }
          // Domain and Range for ObjectProperties
          case "domainop" :
            if (strcmp($head["vars"][1], "objectproperty") == 0){
              $querypattern = ["Domain", "domainop", "objectproperty"];
              $domainaxioms = $this->getDomainAxioms(["domainop", "objectproperty"], $result["bindings"]);
              $this->intermediate->prepareDomainAxioms($querypattern[0], $domainaxioms);
              break;
            }
          case "objectproperty" :
            if (strcmp($head["vars"][1], "domainop") == 0){
              $querypattern = ["Domain", "domainop", "objectproperty"];
              $domainaxioms = $this->getDomainAxioms(["domainop", "objectproperty"], $result["bindings"]);
              $this->intermediate->prepareDomainAxioms($querypattern[0], $domainaxioms);
              break;
            }
          case "rangeop" :
            if (strcmp($head["vars"][1], "objectproperty") == 0){
              $querypattern = ["Range", "rangeop", "objectproperty"];
              $rangeaxioms = $this->getRangeAxioms(["rangeop", "objectproperty"], $result["bindings"]);
              $this->intermediate->prepareRangeAxioms($querypattern[0], $rangeaxioms);
              break;
            }
          case "objectproperty" :
            if (strcmp($head["vars"][1], "rangeop") == 0){
              $querypattern = ["Range", "rangeop", "objectproperty"];
              $rangeaxioms = $this->getRangeAxioms(["rangeop", "objectproperty"], $result["bindings"]);
              $this->intermediate->prepareRangeAxioms($querypattern[0], $rangeaxioms);
              break;
            }
          // StrictSubClassOf for Classes
          case "strictsub" :
            if (strcmp($head["vars"][1], "strictsupclass") == 0){
              $querypattern = ["StrictSubClassOf"];
              $strictsubclassaxioms = $this->getStrictSubClassAxioms(["strictsub", "strictsupclass"], $result["bindings"]);
              $this->intermediate->prepareStrictSubClassAxioms($querypattern[0], $strictsubclassaxioms);
              break;
            }
          case "strictsupclass" :
            if (strcmp($head["vars"][1], "strictsub") == 0){
              $querypattern = ["StrictSubClassOf"];
              $strictsubclassaxioms = $this->getStrictSubClassAxioms(["strictsub", "strictsupclass"], $result["bindings"]);
              $this->intermediate->prepareStrictSubClassAxioms($querypattern[0], $strictsubclassaxioms);
              break;
            }
          // StrictSubPropertyOf for ObjectProperty
          case "subobjectproperty" :
            if (strcmp($head["vars"][1], "strictsupobjectproperty") == 0){
              $querypattern = ["StrictSubPropertyOf", "subobjectproperty", "strictsupobjectproperty"];
              $strictsubopaxioms = $this->getStrictSubObjectPropertyAxioms(["subobjectproperty", "strictsupobjectproperty"], $result["bindings"]);
              $this->intermediate->prepareStrictSubObjectPropertyAxioms($querypattern[0], $strictsubopaxioms);
              break;
            }
          case "strictsupobjectproperty" :
            if (strcmp($head["vars"][1], "subobjectproperty") == 0){
              $querypattern = ["StrictSubPropertyOf", "subobjectproperty", "strictsupobjectproperty"];
              $strictsubopaxioms = $this->getStrictSubObjectPropertyAxioms(["subobjectproperty", "strictsupobjectproperty"], $result["bindings"]);
              $this->intermediate->prepareStrictSubObjectPropertyAxioms($querypattern[0], $strictsubopaxioms);
              break;
            }
          // EquivalentClasses
          case "classeq" :
            if (strcmp($head["vars"][1], "classeq1") == 0){
              $querypattern = ["EquivalentClass", "classeq", "classeq1"];
              $eqclassaxioms = $this->getEquivalentClassAxioms(["classeq", "classeq1"], $result["bindings"]);
              if (!empty($eqclassaxioms)){
                $this->intermediate->prepareEquivalentClassAxioms($querypattern[0], $eqclassaxioms);
              }
              break;
            }
          case "classeq1" :
            if (strcmp($head["vars"][1], "classeq") == 0){
              $querypattern = ["EquivalentClass", "classeq", "classeq1"];
              $eqclassaxioms = $this->getEquivalentClassAxioms(["classeq", "classeq1"], $result["bindings"]);
              if (!empty($eqclassaxioms)){
                $this->intermediate->prepareEquivalentClassAxioms($querypattern[0], $eqclassaxioms);
              }
              break;
            }
          // DisjointClasses
          case "classdis" :
            if (strcmp($head["vars"][1], "classdis1") == 0){
              $querypattern = ["DisjointWithClass", "classdis", "classdis1"];
              $disclassaxioms = $this->getDisjointClassAxioms(["classdis", "classdis1"], $result["bindings"]);
              if (!empty($disclassaxioms)){
                $this->intermediate->prepareDisjointClassAxioms($querypattern[0], $disclassaxioms);
              }
              break;
            }
          case "classdis1" :
            if (strcmp($head["vars"][1], "classdis") == 0){
              $querypattern = ["DisjointWithClass", "classdis", "classdis1"];
              $disclassaxioms = $this->getDisjointClassAxioms(["classdis", "classdis1"], $result["bindings"]);
              if (!empty($disclassaxioms)){
                $this->intermediate->prepareDisjointClassAxioms($querypattern[0], $disclassaxioms);
              }
              break;
            }
          // EquivalentObjectProperties
          case "objectpropertyeq" :
            if (strcmp($head["vars"][1], "objectpropertyeq1") == 0){
              $querypattern = ["EquivalentProperty", "objectpropertyeq", "objectpropertyeq1"];
              $eqobjpropaxioms = $this->getEquivalentObjectPropertyAxioms(["objectpropertyeq", "objectpropertyeq1"], $result["bindings"]);
              if (!empty($eqobjpropaxioms)){
                $this->intermediate->prepareEquivalentObjectPropertyAxioms($querypattern[0], $eqobjpropaxioms);
              }
              break;
            }
          case "objectpropertyeq1" :
            if (strcmp($head["vars"][1], "objectpropertyeq") == 0){
              $querypattern = ["EquivalentProperty", "objectpropertyeq", "objectpropertyeq1"];
              $eqobjpropaxioms = $this->getEquivalentObjectPropertyAxioms(["objectpropertyeq", "objectpropertyeq1"], $result["bindings"]);
              if (!empty($eqobjpropaxioms)){
                $this->intermediate->prepareEquivalentObjectPropertyAxioms($querypattern[0], $eqobjpropaxioms);
              }
              break;
            }
          // DisjointObjectProperties
          case "objectpropertydis" :
            if (strcmp($head["vars"][1], "objectpropertydis1") == 0){
              $querypattern = ["DisjointWithObjectProperty", "objectpropertydis", "objectpropertydis1"];
              $disobjpropaxioms = $this->getDisjointObjectPropertyAxioms(["objectpropertydis", "objectpropertydis1"], $result["bindings"]);
              if (!empty($disobjpropaxioms)){
                $this->intermediate->prepareDisjointObjectPropertyAxioms($querypattern[0], $disobjpropaxioms);
              }
              break;
            }
          case "objectpropertydis1" :
            if (strcmp($head["vars"][1], "objectpropertydis") == 0){
              $querypattern = ["DisjointWithObjectProperty", "objectpropertydis", "objectpropertydis1"];
              $disobjpropaxioms = $this->getDisjointObjectPropertyAxioms(["objectpropertydis", "objectpropertydis1"], $result["bindings"]);
              if (!empty($disobjpropaxioms)){
                $this->intermediate->prepareDisjointObjectPropertyAxioms($querypattern[0], $disobjpropaxioms);
              }
              break;
            }
          // DataProperty
          case "dataproperty" :
            if ($varlength <= 1){
              $querypattern = ["DataProperty", "dataproperty"];
              $datapropaxioms = $this->getDataPropertyAxioms("dataproperty", $result["bindings"]);
              $this->intermediate->prepareDataPropertyAxioms($querypattern[0], $datapropaxioms);
              break;
            }
          // Domain and Range for DataProperty
          case "domaindp" :
            if (strcmp($head["vars"][1], "dataproperty") == 0){
              $querypattern = ["DataPropertyDomain", "domaindp", "dataproperty"]; //own pattern DataPropertyDomain
              $domaindpaxioms = $this->getDomainDataPropertyAxioms(["domaindp", "dataproperty"], $result["bindings"]);
              $this->intermediate->prepareDomainDataPropertyAxioms($querypattern[0], $domaindpaxioms);
              break;
            }
          case "dataproperty" :
            if (strcmp($head["vars"][1], "domaindp") == 0){
              $querypattern = ["DataPropertyDomain", "domaindp", "dataproperty"]; //own pattern DataPropertyDomain
              $domaindpaxioms = $this->getDomainDataPropertyAxioms(["domaindp", "dataproperty"], $result["bindings"]);
              $this->intermediate->prepareDomainDataPropertyAxioms($querypattern[0], $domaindpaxioms);
              break;
            }
          case "rangedp" :
            if (strcmp($head["vars"][1], "dataproperty") == 0){
              $querypattern = ["DataPropertyRange", "rangedp", "dataproperty"]; //own pattern DataPropertyRange
              $rangedpaxioms = $this->getRangeDataPropertyAxioms(["rangedp", "dataproperty"], $result["bindings"]);
              $this->intermediate->prepareRangeDataPropertyAxioms($querypattern[0], $rangedpaxioms);
              break;
            }
          case "dataproperty" :
            if (strcmp($head["vars"][1], "rangedp") == 0){
              $querypattern = ["DataPropertyRange", "rangedp", "dataproperty"]; //own pattern DataPropertyRange
              $rangedpaxioms = $this->getRangeDataPropertyAxioms(["rangedp", "dataproperty"], $result["bindings"]);
              $this->intermediate->prepareRangeDataPropertyAxioms($querypattern[0], $rangedpaxioms);
              break;
            }
        }

      }

    }

  }

  public function getIntermediateSparqldl(){
    return $this->intermediate;
  }

  public function returnClassAxioms(){

    if (!empty($this->intermediate->getClass()["Class"])){
      return $this->intermediate->getClass()["Class"];
    } else {
      return null;
    }
  }

  public function returnStrictSubClassAxioms(){

    if (!empty($this->intermediate->getStrictSubClass()["StrictSubClassOf"])){
      return $this->intermediate->getStrictSubClass()["StrictSubClassOf"];
    } else {
      return null;
    }
  }

  public function returnDomain(){

    if (!empty($this->intermediate->getDomain()["Domain"])){
      return $this->intermediate->getDomain()["Domain"];
    } else {
      return null;
    }
  }

  public function returnRange(){

    if (!empty($this->intermediate->getRange()["Range"])){
      return $this->intermediate->getRange()["Range"];
    } else {
      return null;
    }
  }

  public function returnObjectProperties(){

    if (!empty($this->intermediate->getObjectProperty()["ObjectProperty"])){
      return $this->intermediate->getObjectProperty()["ObjectProperty"];
    } else {
      return null;
    }
  }

  public function returnEqClasses(){

    if (!empty($this->intermediate->getEqClasses()["EquivalentClass"])){
      return $this->intermediate->getEqClasses()["EquivalentClass"];
    } else {
      return null;
    }
  }

  public function returnDataPropertyDomain(){

    if (!empty($this->intermediate->getDataPropertyDomain()["DataPropertyDomain"])){
      return $this->intermediate->getDataPropertyDomain()["DataPropertyDomain"];
    } else {
      return null;
    }
  }

  public function returnDataPropertyRange(){

    if (!empty($this->intermediate->getDataPropertyRange()["DataPropertyRange"])){
      return $this->intermediate->getDataPropertyRange()["DataPropertyRange"];
    } else {
      return null;
    }
  }

  public function returnDataProperties(){

    if (!empty($this->intermediate->getDataProperty()["DataProperty"])){
      return $this->intermediate->getDataProperty()["DataProperty"];
    } else {
      return null;
    }
  }

  /**
  Get a new array with unsatisfiable classes by checking classes equivalent to Bottom

  @return array {Array} of unsatisfiable classes
  */
  public function return_unsatisfiableClasses(){
    $e_classes = $this->returnEqClasses();
    $unsat_classes = [];

    if ($e_classes != null){
      foreach ($e_classes as $e){
        $equiv = $e["equivalentclasses"];
        $st_name = $this->remove_prefixExpansion($equiv[0]);
        $st_name1 = $this->remove_prefixExpansion($equiv[1]);

          if (strcmp($st_name,"Nothing") == 0){
              array_push($unsat_classes, $equiv[1]);
          }
          elseif (strcmp($st_name1,"Nothing") == 0){
                array_push($unsat_classes, $equiv[0]);
          }
        }
    }
    return $unsat_classes;
  }

}
