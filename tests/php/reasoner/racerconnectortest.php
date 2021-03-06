<?php
/**
Test the connection between crowd and the Racer reasoner.

Copyright 2016 Giménez, Christian

Author: Giménez, Christian

reasonertest.php

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
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../wicom/reasoner/racerconnector.php';

use Wicom\Reasoner\RacerConnector;

/**
Test the connection between crowd and the Racer reasoner.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class RacerConnectorTest extends PHPUnit\Framework\TestCase
{

    /**
    Test the connection to the reasoner.

    @return Nothing.
     */
    public function testReasoner()
    {
        $input = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
                xmlns:owl="http://www.w3.org/2002/07/owl#"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://www.owllink.org/owllink#
                                    http://www.owllink.org/owllink-20091116.xsd">
<CreateKB kb="http://localhost/kb1" />
<Tell kb="http://localhost/kb1">
  <!-- <owl:ClassAssertion>
      <owl:Class IRI="Person" />
      <owl:NamedIndividual IRI="Mary" />
      </owl:ClassAssertion>
      -->

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
</Tell>
<!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        //Expected obtained directly through racer -- -owllink owllinkfile.owllink
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
  <ResponseMessage xmlns="http://www.owllink.org/owllink#"
              xmlns:owl="http://www.w3.org/2002/07/owl#">
    <KB kb="http://localhost/kb1"/>
    <OK/>
  </ResponseMessage>
EOT;

        $racer = new RacerConnector();

        $racer->run($input);
        $actual = $racer->get_col_answers()[0];

        $this->assertXMLStringEqualsXMLString($expected, $actual, true);
    }
}
