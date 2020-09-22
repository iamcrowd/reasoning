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
load("metajsonbuilder.php", "../../builders/");

use Wicom\Translator\Strategies\QAPackages\CrowdMetaPack;
use Wicom\Translator\Strategies\Strategy;
use Wicom\Translator\Strategies\Metamodel;
use Wicom\Translator\Builders\MetaJSONBuilder;


class DLCheckMeta {

    /**
      This class implements all the functions to build the reasoning output.

      @param $json {json} is the original KF instance given as a JSON object
      @param $strategy {object} is the strategy used to encode the JSON KF instance
    */

    protected $strategy = null;
    protected $metabuilder = null;
    protected $json_input = null;
    protected $out_reasoning = null;

    function __construct($json, $strategy){
      $this->json_input = $json;
      $this->strategy = $strategy;
      $this->metabuilder = new MetaJSONBuilder();
      $this->metabuilder->instantiate_MM($this->json_input);
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
      $kf_out = [
          "KB Status" => $this->strategy->get_qa_pack()->get_kb_status(),
          "SATisfiable Entity types" => $this->strategy->get_qa_pack()->get_satClasses(),
          "UNSATisfiable Entity types" => $this->strategy->get_qa_pack()->get_unsatClasses(),
          "SATisfiable Roles" => $this->strategy->get_qa_pack()->get_satObjectProperties(),
          "UNSATisfiable Roles" => $this->strategy->get_qa_pack()->get_unsatObjectProperties(),
          "Subsumptions" => $this->inferred_subclasses(),
          "Object types cardinalities" => []
        ];
      $owl_ax = [
          "Equivalent Class Axioms" => $this->inferred_all_equivalent_classes(),
          "Equivalent ObjectProperty Axioms" => [],
          "Equivalent DataProperty Axioms" => [],
          "Disjoint Class Axioms" => $this->inferred_all_disjoint_classes(),
          "Disjoint ObjectProperty Axioms" => [],
          "Disjoint DataProperty Axioms" => []
        ];

      $kf = $this->metabuilder->get_product();

      $this->out_reasoning = [
        "KF" => $kf,
        "KF output" => $kf_out,
        "OWL Axioms" => $owl_ax,
        "Reasoner warning" => $this->strategy->get_qa_pack()->get_reasoning_warning()
      ];

      return json_encode($this->out_reasoning, true);
    }

    /**
       Looking for inferred subclasses (or KF subsumptions). This function inserts new subsumptions in the original KF instance
       and returns an array of ids of such subsumptions to indicate that were implicit in the instance.

       @// TODO: here we should filter unsat classes because unsat classes are subclass of nothing
       @// TODO: consider other special cases
    */
    protected function inferred_subclasses(){
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
    */
    protected function inferred_all_equivalent_classes(){
      return $this->strategy->get_qa_pack()->get_all_equiv_class();
    }

    /**
       Looking for inferred disjoint classes.

       @// TODO: Here we should remove disjointness involved into subsumptions
       @// TODO: should we also remove classes which are disjoint with nothing? each sat class is disjoint with nothing by definition
    */
    protected function inferred_all_disjoint_classes(){
      return $this->strategy->get_qa_pack()->get_all_disjoint_class();
    }

    /**
      @// TODO: implement here a function to look for stricter cardinalities
    */
    protected function stricter_cardinalities(){}

}
?>
