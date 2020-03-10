<?php
/* 

   Copyright 2020 GILIA

   Author: GILIA

   filename.php

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
load("berardistrat.php", "wicom/translator/strategies/");
load("owlbuilder.php", "wicom/translator/builders/");
load("translator.php", "wicom/translator/");

use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Translator;

/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.
 */
class BerardiTest extends PHPUnit\Framework\TestCase
{

    /**
       @testdox Translate a simple class diagram in JSON to OWL/XML
     */
    public function testOwlTranslation(){
        $json = file_get_contents(__DIR__ .
                                  '/data/owl_berarditest/simple1.json');
        $expected = file_get_contents(__DIR__ .
                                      '/data/owl_berarditest/simple1.owl');

        $strat = new Berardi();
        $trans = new Translator($strat, new OWLBuilder());
        $actual = $trans->to_owl2($json);

        $this->assertXMLStringEqualsXMLString($expected, $actual, true);
    }
}
