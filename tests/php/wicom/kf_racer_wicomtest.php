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

class KFWicomRacerTest extends PHPUnit\Framework\TestCase
{
    /**
    @testdox tests reasoning services using racer reasoner with all possible queries
    */
    public function test_full_reasoning_KF(){
        $input = file_get_contents('wicom/data/testKFtoOWLlinkAllQueries.json');
        $expected = file_get_contents('wicom/data/testKFtoOWLlinkAllQueriesBeautyOutInferred.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using racer reasoner for looking possible inferred subs
    */
    public function test_full_reasoning_KF_subsumptions(){
        $input = file_get_contents('wicom/data/testKFReasoningSubsumptions.json');
        $expected = file_get_contents('wicom/data/testKFReasoningSubsumptionsExpected.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using racer reasoner with some unsat classes and roles
    */
    public function test_full_reasoning_KF_Unsat_Class_Roles(){
        $input = file_get_contents('wicom/data/testKFReasoningUnsatClassesAndRoles.json');
        $expected = file_get_contents('wicom/data/testKFReasoningUnsatClassesAndRolesExpected.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using racer reasoner looking for stricter cardinalities
    */
    public function test_full_reasoning_KF_With_Cardinalities(){
        $input = file_get_contents('wicom/data/testKFwithCardinalitiesTrue.json');
        $expected = file_get_contents('wicom/data/testKFwithCardinalitiesTrueRacerOut.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input, 'metamodel', 'Racer', true);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }

    /**
    @testdox tests reasoning services using racer reasoner with all unsat classes and roles
    */
    public function test_full_reasoning_KF_Unsat_KB(){
        $input = file_get_contents('wicom/data/testKFReasoningUNSATKB.json');
        $expected = file_get_contents('wicom/data/testKFReasoningUNSATKBExpected.json');

        $wicom = new KF_Wicom();
        $answer = $wicom->full_reasoning($input);

        $this->assertJsonStringEqualsJsonString($expected, $answer, true);
    }
}
