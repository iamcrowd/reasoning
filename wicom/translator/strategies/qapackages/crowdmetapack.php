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

namespace Wicom\Translator\Strategies\QAPackages;

require_once __DIR__ . '/qapack.php';
require_once __DIR__ . '/queriesgenerators/crowdmetaqueries.php';
require_once __DIR__ . '/answeranalizers/crowdmetaanalizer.php';

use Wicom\Translator\Strategies\QAPackages\QueriesGenerators\CrowdMetaQueries;
use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\CrowdMetaAnalizer;
use Wicom\Translator\Strategies\QAPackages\QAPack;

/**
   Question and Answer Pack for the Crowd translation strategy.
 */
class CrowdMetaPack extends QAPack{
    function __construct(){
        $this->query_generator = new CrowdMetaQueries();
        $this->ans_analizer = new CrowdMetaAnalizer();
    }

}
?>
