<?php
/*

   Copyright 2016 Giménez, Christian. Germán Braun.

   Author: Giménez, Christian

   wicomtest.php

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

require_once("common.php");

// use function \load;
load("wicom.php", "common/");
load("kf.php", "common/");
load("config.php", "config/");

use Wicom\Wicom;
use Wicom\KF_Wicom;

class KFWicomTest extends PHPUnit\Framework\TestCase
{
    public function test_full_reasoning_KF(){
        $input = file_get_contents('wicom/data/testKFtoOWLlinkAllQueries.json');
        $expected = file_get_contents('wicom/data/testKFtoOWLlinkAllQueriesBeautyOutInferred.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input);

        //var_dump($answer);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

}
