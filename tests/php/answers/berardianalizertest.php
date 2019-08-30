<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   berardianalizertest.php

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
load("berardianalizer.php", "wicom/translator/strategies/qapackages/answeranalizers/");

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\BerardiAnalizer;

class BerardiAnalizerTest extends PHPUnit\Framework\TestCase
{

    /**
       Filtering the XML must remove the rest of XML tags and leave the
       important one with the desired data.

       Classes names are still IRI's suffix. For that reason we still leave the
       numeral char.
    */
    public function testFilterXML(){
        $query_input = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink#
				    http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">
    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="OtherPerson" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Nope this one nope" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
  </Tell>

  <!-- Queries -->

  <IsKBSatisfiable kb="http://localhost/kb1" />
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="Person" />
  </IsClassSatisfiable>
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="OtherPerson" />
  </IsClassSatisfiable>
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="Nope this one nope" />
  </IsClassSatisfiable>



  <ReleaseKB kb="http://localhost/kb1" />
</RequestMessage>
EOT;
        $answer_output = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
                 xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://localhost/kb1"/>
  <OK/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="false"/>
  <OK/>
</ResponseMessage>
EOT;

        $expected = [ "IsKBSatisfiable" => "true",
                      "IsClassSatisfiable" => [
                          ["true", "Person"],
                          ["true", "OtherPerson"],
                          ["false", "Nope this one nope"]
                      ]
        ];

        $oa = new BerardiAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $actual = $oa->filter_xml();

        $this->assertEquals($expected, $actual, true);
    }

    public function testAnswerJson(){
        $query_input = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink#
				    http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">
    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="OtherPerson" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Nope this one nope" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
  </Tell>

  <!-- Queries -->

  <IsKBSatisfiable kb="http://localhost/kb1" />
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="Person" />
  </IsClassSatisfiable>
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="OtherPerson" />
  </IsClassSatisfiable>
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="Nope this one nope" />
  </IsClassSatisfiable>



  <ReleaseKB kb="http://localhost/kb1" />
</RequestMessage>
EOT;
        $answer_output = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
                 xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://localhost/kb1"/>
  <OK/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="false"/>
  <OK/>
</ResponseMessage>
EOT;

        $expected = <<<'EOT'
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person", "OtherPerson"]
           },
           "unsatisfiable": {
              	"classes" : ["Nope this one nope"]
           },
           "suggestions" : {
              	"links" : []
           },
           "reasoner" : {
              	"input" : "",
              	"output" : ""
           }
       }
EOT;

        $oa = new BerardiAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();


        /*
        print("\n\n");
        print($actual);
        print("\n\n");
        */

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function testSimpleOWLlink(){
        $query_input = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink#
				    http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">
    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
  </Tell>

  <!-- Queries -->

  <IsKBSatisfiable kb="http://localhost/kb1" />
  <IsClassSatisfiable kb="http://localhost/kb1">
    <owl:Class IRI="Person" />
  </IsClassSatisfiable>

  <ReleaseKB kb="http://localhost/kb1" />
</RequestMessage>
EOT;
        $answer_output = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
                 xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://localhost/kb1"/>
  <OK/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <OK/>
</ResponseMessage>
EOT;

        $expected = <<<'EOT'
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person"]
           },
           "unsatisfiable": {
              	"classes" : []
           },
           "suggestions" : {
              	"links" : []
           },
           "reasoner" : {
              	"input" : "",
              	"output" : ""
           }
       }
EOT;

        $oa = new BerardiAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();


        /*
        print("\n\n");
        print($actual);
        print("\n\n");
        */

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
    public function testRealCase(){
        $query_input = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#" xmlns:owl="http://www.w3.org/2002/07/owl#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"><CreateKB kb="http://localhost/kb1"/><Tell kb="http://localhost/kb1"><owl:SubClassOf><owl:Class IRI="Hi World"/><owl:Class abbreviatedIRI="owl:Thing"/></owl:SubClassOf></Tell><IsKBSatisfiable kb="http://localhost/kb1"/><IsClassSatisfiable kb="http://localhost/kb1"><owl:Class IRI="Hi World"/></IsClassSatisfiable></RequestMessage>
EOT;
        $answer_output = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?><ResponseMessage xmlns="http://www.owllink.org/owllink#"                 xmlns:owl="http://www.w3.org/2002/07/owl#">  <KB kb="http://localhost/kb1"/>  <OK/>  <BooleanResponse result="true"/>  <BooleanResponse result="true"/></ResponseMessage>
EOT;

        $expected = <<<'EOT'
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Hi World"]
           },
           "unsatisfiable": {
              	"classes" : []
           },
           "suggestions" : {
              	"links" : []
           },
           "reasoner" : {
              	"input" : "",
              	"output" : ""
           }
       }
EOT;

        $oa = new BerardiAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();


        /*
        print("\n\n");
        print($actual);
        print("\n\n");
        */

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function testUnsatisfiableOWLlink(){
        $query_input = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#" xmlns:owl="http://www.w3.org/2002/07/owl#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"><CreateKB kb="http://localhost/kb1"/><Tell kb="http://localhost/kb1"><owl:SubClassOf><owl:Class IRI="Test Class"/><owl:Class abbreviatedIRI="owl:Thing"/></owl:SubClassOf></Tell><Tell kb="http://localhost/kb1">
<owl:SubClassOf>
  <owl:Class IRI="Test Class" />
  <owl:ObjectComplementOf>
    <owl:Class IRI="Test Class" />
  </owl:ObjectComplementOf>
</owl:SubClassOf>
</Tell><IsKBSatisfiable kb="http://localhost/kb1"/><IsClassSatisfiable kb="http://localhost/kb1"><owl:Class IRI="Test Class"/></IsClassSatisfiable></RequestMessage>
EOT;
        $answer_output = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
                 xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://localhost/kb1"/>
  <OK/>
  <OK/>
  <BooleanResponse result="true"
                   warning="Unsatisfiable classes: (*BOTTOM* BOTTOM file://owllink-unsatisfiable.xmlTest Class)"/>
  <BooleanResponse result="false"/>
</ResponseMessage>
EOT;

        $expected = <<<'EOT'
       {
           "satisfiable": {
               "kb" : true,
               "classes" : []
           },
           "unsatisfiable": {
              	"classes" : ["Test Class"]
           },
           "suggestions" : {
              	"links" : []
           },
           "reasoner" : {
              	"input" : "",
              	"output" : ""
           }
       }
EOT;

        $oa = new BerardiAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();


        /*
        print("\n\n");
        print($actual);
        print("\n\n");
        */

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
}
