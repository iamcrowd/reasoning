<?php
/*

   Copyright 2020

   Author: GILIA

   kf_wicomtest.php

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

class KFWicomKoncludeTest extends PHPUnit\Framework\TestCase
{

    /**
    @testdox tests reasoning services using konclude reasoner with all possible queries
    */
    public function test_full_reasoning_KF_konclude(){
        $input = file_get_contents('wicom/data/testKFtoOWLlinkAllQueries.json');
        $expected = file_get_contents('wicom/data/testKFtoOWLlinkAllQueriesBeautyOutInferredKonclude.json');
        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input, 'alcin', 'Konclude', false);
        //var_dump($answer);
        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using konclude reasoner for looking possible inferred subs
    */
    public function test_full_reasoning_KF_subsumptions_konclude(){
        $input = file_get_contents('wicom/data/testKFReasoningSubsumptions.json');
        $expected = file_get_contents('wicom/data/testKFReasoningSubsumptionsExpectedKonclude.json');
        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input, 'alcin', 'Konclude', false);
        //var_dump($answer);
        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using konclude reasoner with some unsat classes and roles
    */
    public function test_full_reasoning_KF_Unsat_Class_Roles_konclude(){
        $input = file_get_contents('wicom/data/testKFReasoningUnsatClassesAndRoles.json');
        $expected = file_get_contents('wicom/data/testKFReasoningUnsatClassesAndRolesExpectedKonclude.json');
        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input, 'alcin', 'Konclude', false);
        //var_dump($answer);
        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using konclude reasoner looking for stricter cardinalities
    */
    public function test_full_reasoning_KF_With_Cardinalities(){
        $input = file_get_contents('wicom/data/testKFwithCardinalitiesTrue.json');
        $expected = file_get_contents('wicom/data/testKFwithCardinalitiesTrueKoncludeOut.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input, 'alcin', 'Konclude', false);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }
    /**
    @testdox tests reasoning services using konclude reasoner with all unsat classes and roles
    */
    public function test_full_reasoning_KF_Unsat_KB_konclude(){
        $input = file_get_contents('wicom/data/testKFReasoningUNSATKB.json');
        $expected = file_get_contents('wicom/data/testKFReasoningUNSATKBExpectedKonclude.json');
        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input, 'alcin', 'Konclude', false);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }
}
