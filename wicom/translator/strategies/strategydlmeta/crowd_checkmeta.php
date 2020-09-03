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

    function __construct($json, $strategy, $responses){
      $this->json_input = $json;
      $this->strategy = $strategy;
      $this->responses = $responses;
      $this->metabuilder = new MetaJSONBuilder($this->json_input);;
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
    public function inferred_equivalent_classes($json_input){
      $this->strategy->get_qa_pack()->get_unsatClasses();
      $classes = $this->strategy->get_classes();

      foreach ($classes as $jelem) {
        $eq_arr = $this->strategy->get_qa_pack()->get_equiv($jelem);
      }
    }

    /**
       Looking for inferred disjoint classes.

       @param
       @param

       @see
    */
    public function inferred_disjoint_classes($json_input){
      $this->strategy->get_qa_pack()->get_unsatClasses();
      $classes = $this->strategy->get_classes();
      $disj_arr = $this->strategy->get_qa_pack()->get_disjoint_classes();
      //var_dump($disj_arr);
    }
}
?>
