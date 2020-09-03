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
load("metajsonbuilder.php", "../../builders/");

use Wicom\Translator\Strategies\QAPackages\CrowdMetaPack;
use Wicom\Translator\Strategies\Strategy;
use Wicom\Translator\Builders\MetaJSONBuilder;


class DLCheckMeta extends Strategy{

    protected $strategy = null;
    protected $metabuilder = null;
    protected $responses = null;
    protected $json_input = null;
    protected $out_reasoning = null;

    function __construct($json, $strategy, $responses){
      $this->json_input = $json;
      $this->strategy = $strategy;
      $this->responses = $responses;
      $this->metabuilder = new MetaJSONBuilder($this->json_input);
      $this->out_reasoning = [];
    }

    /**
      This function returns the final reasoning output to be visualised

      @// NOTE:
      {
  "KF": {
    "Entity type": {
      "Value property": {
        "Value type": [
          "http://crowd.fi.uncoma.edu.ar/kb1#N"
        ]
      },
      "Data type": [
        "string"
      ],
      "Object type": [
        "http://crowd.fi.uncoma.edu.ar/kb1#A",
      ]
    },
  },
  "Subsumptions": [],
  "Equivalent Axioms": [
    [
      "http://crowd.fi.uncoma.edu.ar/kb1#A",
      "http://crowd.fi.uncoma.edu.ar/kb1#A"
    ]
  ],
  "Disjoint Axioms": [
    [
      "http://crowd.fi.uncoma.edu.ar/kb1#A",
      "http://www.w3.org/2002/07/owl#Nothing"
    ]
  ]
}
      @// NOTE: Notice that Subsumptions and Object type cardinalities are given in terms of KF definitions. However,
      Equivalent and Disjoint axioms could be defined as KF primitives (only Disjointness defined in the context of subsumptions) so that
      they are given as OWL primitives (ex. Equivalent Class Axioms, etc)
    */
    function built_output(){
      $this->out_reasoning = [
        "KF" => $this->metabuilder->get_product(),
        "Subsumptions" => $this->inferred_subclasses(),
        "Equivalent Class Axioms" => $this->inferred_all_equivalent_classes(),
        "Equivalent ObjectProperty Axioms" => [],
        "Equivalent DataProperty Axioms" => [],
        "Disjoint Class Axioms" => $this->inferred_all_disjoint_classes(),
        "Disjoint ObjectProperty Axioms" => [],
        "Disjoint DataProperty Axioms" => [],
        "Object types cardinalities" => []
      ];
      return json_encode($this->out_reasoning, true);
    }

    function translate($json, $build){

    }

    function decode($owl, $jsonbuild){

    }

    /**
       Looking for inferred subclasses (or KF subsumptions).

       @param
       @param

       @see
    */
    public function inferred_subclasses(){
      $this->strategy->get_qa_pack()->get_unsatClasses();
      $classes = $this->strategy->get_classes();

      $inferred_subs = [];

      foreach ($classes as $jelem) {
        $subclasses = $this->strategy->get_qa_pack()->get_subclass($jelem);

        foreach ($subclasses as $sub) {

          if (!$this->metabuilder->subsumption_in_instance($sub, $jelem)){
            $name = $this->metabuilder->insert_subsumption($sub, $jelem);
            \array_push($inferred_subs, $name);
          }
        }
      }
      return $inferred_subs;
    }

    /**
       Looking for inferred equivalent classes. At the beginning we will consider each "new" equivalence as inferred one because
       we do not have primitives for such axiom in KF. So that we will not compare them against the input model.
       Every class is self-equivalent so that we will not consider them as an inference.

       @param
       @param

       @see
    */
    public function inferred_all_equivalent_classes(){
      return $this->strategy->get_qa_pack()->get_all_equiv_class();
    }

    /**
       Looking for inferred disjoint classes.

       @// TODO:  Here we should remove disjointness involved into subsumptions
       @// TODO: we also should remove classes which are disjoint with nothing!
       @// TODO: we also should remove classes which are disjoint with itself!

       @param
       @param

       @see
    */
    public function inferred_all_disjoint_classes(){
      return $this->strategy->get_qa_pack()->get_all_disjoint_class();
    }
}
?>
