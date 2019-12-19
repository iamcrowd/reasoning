<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   berarditest.php

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

load("crowd_dlmeta.php", "wicom/translator/strategies/strategydlmeta/");
load("owllinkbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Strategies\Strategydlmeta\DLMeta;
use Wicom\Translator\Builders\OWLlinkBuilder;

/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.
 */
class DLMetaTest extends PHPUnit\Framework\TestCase
{


    /**
       @testdox Translate a simple model with some KF OBJECT TYPES into OWLlink with SAT queries
     */
    public function testObjTypeIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testObjTypeIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testObjTypeIntoOWLlink.owllink");

        $strategy = new DLMeta();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $strategy->translate_queries($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
    }

    /**
       @testdox Translate a simple model with some KF SUBSUMPTIONS into OWLlink with SAT queries
     */
    public function testSubsumptionNoConstIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testSubIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testSubIntoOWLlink.owllink");

        $strategy = new DLMeta();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $strategy->translate_queries($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
    }

}
