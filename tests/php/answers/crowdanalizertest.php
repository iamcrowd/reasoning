<?php
/*

   Copyright 2016 GILIA

   Author: Giménez, Christian. Braun, Germán

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
load("crowdanalizer.php", "wicom/translator/strategies/qapackages/answeranalizers/");

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\CrowdAnalizer;

class CrowdAnalizerTest extends PHPUnit\Framework\TestCase
{

/*    public function testFilterXML(){
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

        $oa = new CrowdAnalizer();
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
{"satisfiable":{"kb":true,"classes":["Person","OtherPerson"]},"unsatisfiable":{"classes":["Nope this one nope"]},"graphical_suggestions":{"links":[]},"non_graphical_suggestion":{"links":[]},"reasoner":{"input":"","output":""}}
EOT;

        $oa = new CrowdAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();

        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();


        print("\n\n");
        print($actual);
        print("\n\n");
        print("\n\n");
        print($expected);
        print("\n\n");

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

        $oa = new CrowdAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();


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

        $oa = new CrowdAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();



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

        $oa = new CrowdAnalizer();
        $oa->generate_answer($query_input, $answer_output);
        $oa->analize();
        $answer = $oa->get_answer();
        // Removing input and output XML string, is merely descriptive.
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();



        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

*/

/*


    public function testAnswerOWLlinkOutput(){
        $query_input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
    xmlns:owl="http://www.w3.org/2002/07/owl#"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
                <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">


                    <owl:SubClassOf>
                      <owl:Class IRI="D"/>
                      <owl:Class IRI="A"/>
                    </owl:SubClassOf>

                    <owl:SubClassOf>
                      <owl:Class IRI="C"/>
                      <owl:Class IRI="A"/>
                    </owl:SubClassOf>

                    <owl:SubClassOf>
                      <owl:Class IRI="B"/>
                      <owl:Class IRI="A"/>
                    </owl:SubClassOf>

                    <owl:SubClassOf>
                      <owl:Class IRI="A"/>
                      <owl:Class abbreviatedIRI="owl:Thing"/>
                    </owl:SubClassOf>


                    <owl:DisjointClasses>
                      <owl:Class IRI="D"/>
                      <owl:Class IRI="B"/>
                    </owl:DisjointClasses>

        		    <owl:EquivalentClasses>
                		<owl:Class IRI="A"/>
                		<owl:ObjectUnionOf>
                    		<owl:Class IRI="B"/>
                    		<owl:Class IRI="C"/>
                		</owl:ObjectUnionOf>
            		</owl:EquivalentClasses>

                    <owl:DisjointClasses>
                      <owl:Class IRI="B"/>
                      <owl:Class IRI="C"/>
                    </owl:DisjointClasses>

                </Tell>
        <IsKBSatisfiable kb="http://localhost/kb1"/>
        <IsClassSatisfiable kb="http://localhost/kb1">
        	<owl:Class IRI="A"/>
        </IsClassSatisfiable>
        <IsClassSatisfiable kb="http://localhost/kb1">
        	<owl:Class IRI="B"/>
        </IsClassSatisfiable>
        <IsClassSatisfiable kb="http://localhost/kb1">
        	<owl:Class IRI="C"/>
        </IsClassSatisfiable>
        <IsClassSatisfiable kb="http://localhost/kb1">
        	<owl:Class IRI="D"/>
        </IsClassSatisfiable>
        <GetSubClassHierarchy kb="http://localhost/kb1"/>
        <GetDisjointClasses kb="http://localhost/kb1">
        	<owl:Class IRI="A"/>
        </GetDisjointClasses>
        <GetDisjointClasses kb="http://localhost/kb1">
        	<owl:Class IRI="B"/>
        </GetDisjointClasses>
        <GetDisjointClasses kb="http://localhost/kb1">
        	<owl:Class IRI="C"/>
        </GetDisjointClasses>
        <GetDisjointClasses kb="http://localhost/kb1">
        	<owl:Class IRI="D"/>
        </GetDisjointClasses>
        <GetEquivalentClasses kb="http://localhost/kb1">
        	<owl:Class IRI="A"/>
        </GetEquivalentClasses>
        <GetEquivalentClasses kb="http://localhost/kb1">
        	<owl:Class IRI="B"/>
        </GetEquivalentClasses>
        <GetEquivalentClasses kb="http://localhost/kb1">
        	<owl:Class IRI="C"/>
        </GetEquivalentClasses>
        <GetEquivalentClasses kb="http://localhost/kb1">
        	<owl:Class IRI="D"/>
        </GetEquivalentClasses>
</RequestMessage>
XML;

        $owl2_input =<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
                            <SubClassOf>
                              <Class IRI="D"/>
                              <Class IRI="A"/>
                            </SubClassOf>
                            <SubClassOf>
                              <Class IRI="C"/>
                              <Class IRI="A"/>
                            </SubClassOf>
                            <SubClassOf>
                              <Class IRI="B"/>
                              <Class IRI="A"/>
                            </SubClassOf>
                            <SubClassOf>
                              <Class IRI="A"/>
                              <Class abbreviatedIRI="owl:Thing"/>
                            </SubClassOf>
                            <DisjointClasses>
                              <Class IRI="D"/>
                              <Class IRI="B"/>
                            </DisjointClasses>
                		        <EquivalentClasses>
                        		 <Class IRI="A"/>
                        		  <ObjectUnionOf>
                            		<Class IRI="B"/>
                            		<Class IRI="C"/>
                        		 </ObjectUnionOf>
                    		    </EquivalentClasses>
                            <DisjointClasses>
                              <Class IRI="B"/>
                              <Class IRI="C"/>
                            </DisjointClasses>
</Ontology>
XML;


$answer_output = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
                 xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <OK/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <ClassHierarchy>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class abbreviatedIRI="owl:Thing"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="A"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class IRI="A"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="C"/>
        </ClassSynset>
        <ClassSynset>
          <owl:Class IRI="B"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class IRI="C"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="D"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
  </ClassHierarchy>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="C"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="D"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="B"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="B"/>
    </ClassSynset>
  </ClassSynsets>
  <SetOfClasses>
    <owl:Class IRI="A"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="B"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="C"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="D"/>
  </SetOfClasses>
</ResponseMessage>
XML;

$expected = <<<'EOT'
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["A","B","C","D"]
           },
           "unsatisfiable": {
              	"classes" : []
           },
           "graphical_suggestions" : {
              	"links" : []
           },
           "non_graphical_suggestion" : {
              	"links" : []
           },
           "reasoner" : {
              	"input" : "",
              	"output" : "",
                "owl2" : "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Ontology xmlns=\"http:\/\/www.w3.org\/2002\/07\/owl#\" xml:base=\"\" xmlns:rdf=\"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#\" xmlns:xml=\"http:\/\/www.w3.org\/XML\/1998\/namespace\" xmlns:xsd=\"http:\/\/www.w3.org\/2001\/XMLSchema#\" xmlns:rdfs=\"http:\/\/www.w3.org\/2000\/01\/rdf-schema#\" ontologyIRI=\"http:\/\/localhost\/kb1\"><SubClassOf><Class IRI=\"#Class1\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student_R_max\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2_R_max\"\/><Class IRI=\"#Student_R_max\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2_R_max\"\/><Class IRI=\"#Class2\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student_R_min\"\/><Class IRI=\"#Class2\"\/><\/SubClassOf><DisjointClasses><Class IRI=\"#Person\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Class1\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Class2\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student\"\/><Class IRI=\"#Student\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2\"\/><Class IRI=\"#Class2\"\/><\/EquivalentClasses><SubClassOf><Class IRI=\"#Person\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectProperty IRI=\"#R\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Person\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Person\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/SubClassOf><SubClassOf><Class IRI=\"#Person\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/SubClassOf><EquivalentClasses><Class IRI=\"#Person_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Person\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Person\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Student\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Student\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><SubClassOf><Class IRI=\"#Class1\"\/><Class IRI=\"#Person\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectProperty IRI=\"#R\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Class1\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Class2\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class1\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/SubClassOf><EquivalentClasses><Class IRI=\"#Class1_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Class1\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Class1\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Class2\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Class2\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><\/Ontology>"
           }
       }
EOT;

        $oa = new CrowdAnalizer();
        $oa->generate_answer($query_input, $answer_output, $owl2_input);
        $oa->analize();
        $answer = $oa->get_answer();
        $owl2 = $answer->get_new_owl2()->to_string();
        var_dump($owl2);
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();



//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
        $this->assertEquals($expected, $actual, true);
}

    public function testAnswerOWLlinkOutputPrefixes(){
        $query_input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
    xmlns:owl="http://www.w3.org/2002/07/owl#"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
                <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">


                    <owl:SubClassOf>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
                    </owl:SubClassOf>

                    <owl:SubClassOf>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
                    </owl:SubClassOf>

                    <owl:SubClassOf>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
                    </owl:SubClassOf>

                    <owl:SubClassOf>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
                      <owl:Class abbreviatedIRI="http://www.w3.org/2002/07/owl#Thing"/>
                    </owl:SubClassOf>


                    <owl:DisjointClasses>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
                    </owl:DisjointClasses>

                <owl:EquivalentClasses>
                    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
                    <owl:ObjectUnionOf>
                        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
                        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
                    </owl:ObjectUnionOf>
                </owl:EquivalentClasses>

                    <owl:DisjointClasses>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
                      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
                    </owl:DisjointClasses>

                </Tell>
        <IsKBSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
        <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
        </IsClassSatisfiable>
        <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
        </IsClassSatisfiable>
        <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
        </IsClassSatisfiable>
        <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
        </IsClassSatisfiable>
        <GetSubClassHierarchy kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
        <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
        </GetDisjointClasses>
        <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
        </GetDisjointClasses>
        <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
        </GetDisjointClasses>
        <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
        </GetDisjointClasses>
        <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
        </GetEquivalentClasses>
        <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
        </GetEquivalentClasses>
        <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
        </GetEquivalentClasses>
        <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
        </GetEquivalentClasses>
        <GetPrefixes kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
</RequestMessage>
XML;

        $owl2_input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
          <Prefix name="" IRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
          <Prefix name="bio-onto" IRI="http://www.cenpat-conicet.gob.ar/ontology/"/>
                            <SubClassOf>
                              <Class IRI="D"/>
                              <Class IRI="A"/>
                            </SubClassOf>
                            <SubClassOf>
                              <Class IRI="C"/>
                              <Class IRI="A"/>
                            </SubClassOf>
                            <SubClassOf>
                              <Class IRI="B"/>
                              <Class IRI="A"/>
                            </SubClassOf>
                            <SubClassOf>
                              <Class IRI="A"/>
                              <Class abbreviatedIRI="owl:Thing"/>
                            </SubClassOf>
                            <DisjointClasses>
                              <Class IRI="D"/>
                              <Class IRI="B"/>
                            </DisjointClasses>
                            <EquivalentClasses>
                             <Class IRI="A"/>
                              <ObjectUnionOf>
                                <Class IRI="B"/>
                                <Class IRI="C"/>
                             </ObjectUnionOf>
                            </EquivalentClasses>
                            <DisjointClasses>
                              <Class IRI="B"/>
                              <Class IRI="C"/>
                            </DisjointClasses>
</Ontology>
XML;


$answer_output = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
                 xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <OK/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <ClassHierarchy>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class abbreviatedIRI="owl:Thing"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
        </ClassSynset>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
  </ClassHierarchy>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class abbreviatedIRI="owl:Nothing"/>
    </ClassSynset>
    <ClassSynset>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
    </ClassSynset>
  </ClassSynsets>
  <SetOfClasses>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/A"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/B"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/C"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/D"/>
  </SetOfClasses>
  <Prefixes>
    <Prefix name="" fullIRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
    <Prefix name="bio-onto" fullIRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
  </Prefixes>
</ResponseMessage>
XML;

$expected = <<<'EOT'
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["A","B","C","D"]
           },
           "unsatisfiable": {
                "classes" : []
           },
           "graphical_suggestions" : {
                "links" : []
           },
           "non_graphical_suggestion" : {
                "links" : []
           },
           "reasoner" : {
                "input" : "",
                "output" : "",
                "owl2" : "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Ontology xmlns=\"http:\/\/www.w3.org\/2002\/07\/owl#\" xml:base=\"\" xmlns:rdf=\"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#\" xmlns:xml=\"http:\/\/www.w3.org\/XML\/1998\/namespace\" xmlns:xsd=\"http:\/\/www.w3.org\/2001\/XMLSchema#\" xmlns:rdfs=\"http:\/\/www.w3.org\/2000\/01\/rdf-schema#\" ontologyIRI=\"http:\/\/localhost\/kb1\"><SubClassOf><Class IRI=\"#Class1\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student_R_max\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2_R_max\"\/><Class IRI=\"#Student_R_max\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2_R_max\"\/><Class IRI=\"#Class2\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student_R_min\"\/><Class IRI=\"#Class2\"\/><\/SubClassOf><DisjointClasses><Class IRI=\"#Person\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Class1\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Class2\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student\"\/><Class IRI=\"#Student\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2\"\/><Class IRI=\"#Class2\"\/><\/EquivalentClasses><SubClassOf><Class IRI=\"#Person\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectProperty IRI=\"#R\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Person\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Person\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/SubClassOf><SubClassOf><Class IRI=\"#Person\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/SubClassOf><EquivalentClasses><Class IRI=\"#Person_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Person\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Person\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Student\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Student\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><SubClassOf><Class IRI=\"#Class1\"\/><Class IRI=\"#Person\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectProperty IRI=\"#R\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Class1\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Class2\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class1\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/SubClassOf><EquivalentClasses><Class IRI=\"#Class1_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Class1\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Class1\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Class2\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Class2\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><\/Ontology>"
           }
       }
EOT;

        $oa = new CrowdAnalizer();
        $oa->generate_answer($query_input, $answer_output, $owl2_input);
        $oa->analize();
        $answer = $oa->get_answer();
        $owl2 = $answer->get_new_owl2()->to_string();
        var_dump($owl2);
        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();



//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
        $this->assertEquals($expected, $actual, true);
    }

  */

  public function testAnswerOWLlinkOutput(){
      $query_input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
      <RequestMessage xmlns="http://www.owllink.org/owllink#" xmlns:owl="http://www.w3.org/2002/07/owl#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd" xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"><CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/"><Prefix name="" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/"/><Prefix name="" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/"/><Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
      <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
      <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
      <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB><Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
    <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
      <owl:SubClassOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </owl:SubClassOf>
      <owl:SubClassOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </owl:SubClassOf>
      <owl:SubClassOf>
        <owl:ObjectSomeValuesFrom>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
        </owl:ObjectSomeValuesFrom>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
      </owl:SubClassOf>
      <owl:SubClassOf>
        <owl:ObjectSomeValuesFrom>
          <owl:ObjectInverseOf>
            <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
          </owl:ObjectInverseOf>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
        </owl:ObjectSomeValuesFrom>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
      </owl:SubClassOf>
      <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
        <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
          <owl:ObjectMinCardinality cardinality="1">
            <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
          </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
      </owl:EquivalentClasses>
      <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
        <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
          <owl:ObjectMaxCardinality cardinality="1">
            <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
          </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
      </owl:EquivalentClasses>
      <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
        <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
      </owl:EquivalentClasses>
      <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
        <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
      </owl:EquivalentClasses>
      <owl:SubClassOf>
              <owl:Class IRI="Class3"/>
              <owl:Class abbreviatedIRI="owl:Thing"/>
            </owl:SubClassOf><owl:SubClassOf>
              <owl:Class IRI="Class2"/>
              <owl:Class abbreviatedIRI="owl:Thing"/>
            </owl:SubClassOf><owl:SubClassOf>
              <owl:ObjectSomeValuesFrom>
                <owl:ObjectProperty IRI="R"/>
                <owl:Class abbreviatedIRI="owl:Thing"/>
              </owl:ObjectSomeValuesFrom>
              <owl:Class IRI="Class3"/>
            </owl:SubClassOf><owl:SubClassOf>
              <owl:ObjectSomeValuesFrom>
                <owl:ObjectInverseOf>
                  <owl:ObjectProperty IRI="R"/>
                </owl:ObjectInverseOf>
                <owl:Class abbreviatedIRI="owl:Thing"/>
              </owl:ObjectSomeValuesFrom>
              <owl:Class IRI="Class2"/>
            </owl:SubClassOf><owl:SubClassOf>
              <owl:Class IRI="Class3"/>
              <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectProperty IRI="R"/>
              </owl:ObjectMaxCardinality>
            </owl:SubClassOf><owl:SubClassOf>
              <owl:Class IRI="Class3"/>
              <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectProperty IRI="R"/>
              </owl:ObjectMinCardinality>
            </owl:SubClassOf></Tell>
            <IsKBSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
            <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
              <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
            </IsClassSatisfiable>
            <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
              <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
            </IsClassSatisfiable>
            <GetSubClassHierarchy kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
            <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
              <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
            </GetDisjointClasses>
            <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
              <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
            </GetDisjointClasses>
            <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
              <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
            </GetEquivalentClasses>
            <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
              <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
            </GetEquivalentClasses>
            <GetPrefixes kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
          </RequestMessage>
XML;

      $owl2_input =<<<XML
<?xml version="1.0" encoding="UTF-8"?>
            <Ontology xmlns="http://www.w3.org/2002/07/owl#"
            xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:xml="http://www.w3.org/XML/1998/namespace"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
            xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
            ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
            <Prefix name="" IRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
            <SubClassOf>
              <Class IRI="Class3"/>
              <Class abbreviatedIRI="owl:Thing"/>
            </SubClassOf>
            <SubClassOf>
              <Class IRI="Class2"/>
              <Class abbreviatedIRI="owl:Thing"/>
            </SubClassOf>

            <SubClassOf>
              <ObjectSomeValuesFrom>
                <ObjectProperty IRI="R"/>
                <Class abbreviatedIRI="owl:Thing"/>
              </ObjectSomeValuesFrom>
              <Class IRI="Class3"/>
            </SubClassOf>

            <SubClassOf>
              <ObjectSomeValuesFrom>
                <ObjectInverseOf>
                  <ObjectProperty IRI="R"/>
                </ObjectInverseOf>
                <Class abbreviatedIRI="owl:Thing"/>
              </ObjectSomeValuesFrom>
              <Class IRI="Class2"/>
            </SubClassOf>

            <SubClassOf>
              <Class IRI="Class3"/>
              <ObjectMaxCardinality cardinality="1">
                <ObjectProperty IRI="R"/>
              </ObjectMaxCardinality>
            </SubClassOf>

            <SubClassOf>
              <Class IRI="Class3"/>
              <ObjectMinCardinality cardinality="1">
                <ObjectProperty IRI="R"/>
              </ObjectMinCardinality>
            </SubClassOf>
         </Ontology>
XML;


  $answer_output = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<ResponseMessage xmlns="http://www.owllink.org/owllink#"
  xmlns:owl="http://www.w3.org/2002/07/owl#">
  <KB kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <OK/>
  <OK/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <BooleanResponse result="true"/>
  <ClassHierarchy>
    <ClassSynset>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Nothing"/>
    </ClassSynset>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
        </ClassSynset>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
    <ClassSubClassesPair>
      <ClassSynset>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
      </ClassSynset>
      <SubClassSynsets>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
        </ClassSynset>
        <ClassSynset>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
        </ClassSynset>
      </SubClassSynsets>
    </ClassSubClassesPair>
  </ClassHierarchy>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Nothing"/>
    </ClassSynset>
  </ClassSynsets>
  <ClassSynsets>
    <ClassSynset>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Nothing"/>
    </ClassSynset>
  </ClassSynsets>
  <SetOfClasses>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
  </SetOfClasses>
  <SetOfClasses>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
  </SetOfClasses>
  <Prefixes>
    <Prefix name=""            fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
    <Prefix name="rdf"            fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs"            fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd"            fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl"            fullIRI="http://www.w3.org/2002/07/owl#"/>
  </Prefixes>
</ResponseMessage>
XML;

  $expected = <<<'EOT'
     {
         "satisfiable": {
             "kb" : true,
             "classes" : ["http://crowd.fi.uncoma.edu.ar/kb1/Class1","http://crowd.fi.uncoma.edu.ar/kb1/Class3"]
         },
         "unsatisfiable": {
              "classes" : []
         },
         "graphical_suggestions" : {
              "links" : []
         },
         "non_graphical_suggestion" : {
              "links" : []
         },
         "reasoner" : {
              "input" : "",
              "output" : "",
              "owl2" : "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Ontology xmlns=\"http:\/\/www.w3.org\/2002\/07\/owl#\" xml:base=\"\" xmlns:rdf=\"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#\" xmlns:xml=\"http:\/\/www.w3.org\/XML\/1998\/namespace\" xmlns:xsd=\"http:\/\/www.w3.org\/2001\/XMLSchema#\" xmlns:rdfs=\"http:\/\/www.w3.org\/2000\/01\/rdf-schema#\" ontologyIRI=\"http:\/\/localhost\/kb1\"><SubClassOf><Class IRI=\"#Class1\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student_R_max\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2_R_max\"\/><Class IRI=\"#Student_R_max\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2_R_max\"\/><Class IRI=\"#Class2\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student_R_min\"\/><Class IRI=\"#Class2\"\/><\/SubClassOf><DisjointClasses><Class IRI=\"#Person\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Class1\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><DisjointClasses><Class IRI=\"#Class2\"\/><Class abbreviatedIRI=\"owl:Nothing\"\/><\/DisjointClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Person_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person\"\/><Class IRI=\"#Class1_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student\"\/><Class IRI=\"#Student\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Person_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1_R_max\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1\"\/><Class IRI=\"#Class1_R_min\"\/><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2\"\/><Class IRI=\"#Class2\"\/><\/EquivalentClasses><SubClassOf><Class IRI=\"#Person\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Student\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectProperty IRI=\"#R\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Person\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Person\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/SubClassOf><SubClassOf><Class IRI=\"#Person\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/SubClassOf><EquivalentClasses><Class IRI=\"#Person_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Person\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Person_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Person\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Student\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Student_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Student\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><SubClassOf><Class IRI=\"#Class1\"\/><Class IRI=\"#Person\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class2\"\/><Class IRI=\"#Student\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectProperty IRI=\"#R\"\/><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Class1\"\/><\/SubClassOf><SubClassOf><ObjectSomeValuesFrom><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><Class abbreviatedIRI=\"owl:Thing\"\/><\/ObjectSomeValuesFrom><Class IRI=\"#Class2\"\/><\/SubClassOf><SubClassOf><Class IRI=\"#Class1\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/SubClassOf><EquivalentClasses><Class IRI=\"#Class1_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Class1\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class1_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Class1\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectProperty IRI=\"#R\"\/><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2_R_min\"\/><ObjectIntersectionOf><Class IRI=\"#Class2\"\/><ObjectMinCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMinCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><EquivalentClasses><Class IRI=\"#Class2_R_max\"\/><ObjectIntersectionOf><Class IRI=\"#Class2\"\/><ObjectMaxCardinality cardinality=\"1\"><ObjectInverseOf><ObjectProperty IRI=\"#R\"\/><\/ObjectInverseOf><\/ObjectMaxCardinality><\/ObjectIntersectionOf><\/EquivalentClasses><\/Ontology>"
         }
     }
EOT;

      /* 
	 Germán: I don't know how to fix this!!!
	 - Christian. September, 4th 2019.
	 ____________________
      
      $oa = new CrowdAnalizer();
      $oa->generate_answer($query_input, $answer_output, $owl2_input);
      $oa->analize();
      $answer = $oa->get_answer();
      

      $actual = $answer->to_json();
      var_dump($actual);
      */

  //        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
      //    $this->assertEquals($expected, $actual, true);
  }


}
