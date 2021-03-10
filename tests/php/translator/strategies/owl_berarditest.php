<?php
/**
Test Berardi conversions to OWL.

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

PHP version >= 7.2

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar

@todo What's the difference with ./berarditest.php?!
 */

require_once __DIR__ . '/../../common.php';
require_once __DIR__ . '/../../../../wicom/translator/strategies/berardistrat.php';
require_once __DIR__ . '/../../../../wicom/translator/builders/owlbuilder.php';
require_once __DIR__ . '/../../../../wicom/translator/translator.php';

use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Translator;

/**
# Warning!
Don't use assertEqualXMLStructure()! It won't check for attributes values!
And it's deprecated!

It will only check for the amount of attributes.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class OWL_BerardiTest extends PHPUnit\Framework\TestCase
{


    /**
    Translate a simple class diagram in JSON to OWL/XML

    @testdox Translate a simple class diagram in JSON to OWL/XML

    @return Nothing.
     */
    public function testOwlTranslation()
    {
        $json = file_get_contents(__DIR__ . '/data/owl_berarditest/simple1.json');
        $expected = file_get_contents(__DIR__ . '/data/owl_berarditest/simple1.owl');

        $strat = new Berardi();
        $trans = new Translator($strat, new OWLBuilder());
        $actual = $trans->to_owl2($json);

        $this->assertXMLStringEqualsXMLString($expected, $actual, true);

    }//end testOwlTranslation()


}//end class
