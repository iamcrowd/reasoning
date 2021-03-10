<?php
/**
Test the main WICOM class.

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

PHP version >= 7.2

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */

require_once __DIR__ . '/../common.php';

require_once __DIR__ . '/../../../common/wicom.php';
require_once __DIR__ . '/../../../common/uml.php';
require_once __DIR__ . '/../../../config/config.php';

use Wicom\Wicom;
use Wicom\UML_Wicom;

/**
Test the main WICOM class.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class UML_WicomTest extends PHPUnit\Framework\TestCase
{

    /**
    Process the given UML satisfiability.
    
    @return Nothing.
     */
    public function test_is_satisfiable_UML()
    {
        $input = file_get_contents('wicom/data/uml_satisfiable.json');
        $expected = file_get_contents('wicom/data/uml_satisfiable_answer.json');

        $wicom = new UML_Wicom();
        $answer = $wicom->is_satisfiable($input);

        // Erase the reasoner input and output in the JSON answer
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Process the given UML with full reasoning.
    
    @return Nothing.
     */
    public function test_full_reasoning_UML()
    {
        $input = file_get_contents('wicom/data/uml_full.json');
        $expected = file_get_contents('wicom/data/uml_full_answer.json');

        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);

        // Erase the reasoner input and output in the JSON answer
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Process the given inconsistent UML with full reasoning.
    
    @return Nothing.
     */
    public function test_full_reasoning_UML_Inconsistent()
    {
        $input = file_get_contents('wicom/data/uml_inconsistent.json');
        $expected = file_get_contents('wicom/data/uml_inconsistent_answer.json');

        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);

        // Erase the reasoner input and output in the JSON answer
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    /**
    Process the given UML full reasoning and JSON comparison.
    
    @return Nothing.
     */
    public function test_full_reasoning_UML_Class_CompareJSON()
    {
        $input = file_get_contents('wicom/data/uml_class_compare.json');
        $expected = file_get_contents('wicom/data/uml_class_compare_answer.json');

        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);

        // Erase the reasoner input and output in the JSON answer
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Can do a UML full reasoning with a subsumption

    @testdox Can do a UML full reasoning with a subsumption

    @return Nothing.
     */
    public function test_full_reasoning_UML_Subsumption_CompareJSON()
    {
        $input = file_get_contents('wicom/data/uml_subsumption.json');
        $expected = file_get_contents('wicom/data/uml_subsumption_answer.json');

        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);


        // Erase the reasoner input and output in the JSON answer
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Can do a UML full reasoning with a binary associacion

    @testdox Can do a UML full reasoning with a binary associacion

    @return Nothing.
     */
    public function test_full_reasoning_UML_BinaryAssocWithoutClass0N_CompareJSON()
    {
        $input = file_get_contents('wicom/data/full_reasoning_input.json');
        $expected = file_get_contents('wicom/data/full_reasoning_expected.json');

        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);

        // Erase the reasoner input and output in the JSON answer
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

}
