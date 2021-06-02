<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   crowdpack.php

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

namespace Wicom\Translator\Strategies\QAPackages\crowd20;

use function \load;
load("qapack.php", '../');
load("crowdmetapack.php", '../');
load("crowdalcqimetaqueries.php", "../queriesgenerators/crowd20/");
load("crowdmetaanalizer.php", "../answeranalizers/");

use Wicom\Translator\Strategies\QAPackages\QAPack;
use Wicom\Translator\Strategies\QAPackages\CrowdMetaPack;
use Wicom\Translator\Strategies\QAPackages\QueriesGenerators\crowd20\CrowdALCQIMetaQueries;
use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\CrowdMetaAnalizer;


/**
   Question and Answer Pack for the Crowd translation strategy.
 */
class CrowdALCQIMetaPack extends CrowdMetaPack{
    function __construct(){
        $this->query_generator = new CrowdALCQIMetaQueries();
        $this->ans_analizer = new CrowdMetaAnalizer();
    }

}
?>
