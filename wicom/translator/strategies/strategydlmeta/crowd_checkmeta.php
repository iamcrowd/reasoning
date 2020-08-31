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

class DLCheckMeta extends Strategy{

    protected $strategy = null;
    protected $builder = null;
    protected $responses = null;

    function __construct($strategy, $responses, $builder){
      $this->strategy = $strategy;
      $this->responses = $responses;
      $this->builder = $builder;
    }

    function translate($json, $build){

    }

    function decode($owl, $jsonbuild){

    }

    /**
       Looking for inferred equivalent classes

       @param
       @param

       @see
    */
    public function inferred_equivalent_classes($json_input){
      $this->strategy->get_qa_pack()->get_unsatClasses();
      $classes = $this->strategy->get_classes();

      foreach ($classes as $jelem) {
        var_dump($jelem);
        var_dump($this->strategy->get_qa_pack()->get_equiv($jelem));
        $eq_arr = $this->strategy->get_qa_pack()->get_equiv($jelem);
        var_dump($eq_arr);
      }
    }
}
?>
