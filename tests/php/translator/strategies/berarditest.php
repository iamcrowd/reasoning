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

// use function \load;
load("berardistrat.php", "wicom/translator/strategies/");
load("owllinkbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Builders\OWLlinkBuilder;

/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.
 */
class BerardiTest extends PHPUnit\Framework\TestCase
{

    public function testTranslate(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{
"classes": [{"attrs":[], "methods":[], "name": "Hi World"}],
"links": []
}
EOT;
        //TODO: Complete XML!
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\">
       <CreateKB kb=\"http://localhost/kb1\" />
       <Tell kb=\"http://localhost/kb1\">
       <owl:SubClassOf>
       <owl:Class IRI=\"Hi World\" />
       <owl:Class abbreviatedIRI=\"owl:Thing\" />
       </owl:SubClassOf>
       </Tell>
</RequestMessage>";

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        //$expected = process_xmlspaces($expected);
        //$actual = process_xmlspaces($actual);
        // Don't use assertEqualXMLStructure()! It won't check for attributes values!
        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
    }

    ##
    # Test if translate works properly with binary roles.
    public function testTranslateBinaryRoles(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "Person"},
    {"attrs":[], "methods":[], "name": "Cellphone"}],
 "links": [
     {"classes": ["Person", "Cellphone"],
      "multiplicity": ["1..1", "1..*"],
      "name": "hasCellphone",
      "type": "association"}
	]
}
EOT;
        //TODO: Complete XML!
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd">
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
    <owl:SubClassOf>
      <owl:Class IRI="Cellphone" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <!-- One person can has lots of cellphones -->

    <owl:SubClassOf>
      <owl:Class abbreviatedIRI="owl:Thing" />
      <owl:ObjectIntersectionOf>
	<owl:ObjectAllValuesFrom>
	  <owl:ObjectProperty IRI="hasCellphone" />
	  <owl:Class IRI="Person" />
	</owl:ObjectAllValuesFrom>
	<owl:ObjectAllValuesFrom>
	  <owl:ObjectInverseOf>
	    <owl:ObjectProperty IRI="hasCellphone" />
	  </owl:ObjectInverseOf>
	  <owl:Class IRI="Cellphone" />
	</owl:ObjectAllValuesFrom>
      </owl:ObjectIntersectionOf>
    </owl:SubClassOf>

    <!-- Multiplicity -->
    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:ObjectMinCardinality cardinality="1">
        <owl:ObjectProperty IRI="hasCellphone" />
      </owl:ObjectMinCardinality>
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="Cellphone" />
      <owl:ObjectIntersectionOf>
	<owl:ObjectMinCardinality cardinality="1">
	  <owl:ObjectInverseOf>
	    <owl:ObjectProperty IRI="hasCellphone" />
	  </owl:ObjectInverseOf>
	</owl:ObjectMinCardinality>
	<owl:ObjectMaxCardinality cardinality="1">
	  <owl:ObjectInverseOf>
	    <owl:ObjectProperty IRI="hasCellphone" />
	  </owl:ObjectInverseOf>
	</owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
    </owl:SubClassOf>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        /*$expected = process_xmlspaces($expected);
          $actual = process_xmlspaces($actual);*/
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    # Test if 0..* to 0..* associations is translated properly.
    public function testTranslateRolesManyToMany(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "Person"},
    {"attrs":[], "methods":[], "name": "Cellphone"}],
 "links": [
     {"classes": ["Person", "Cellphone"],
      "multiplicity": ["0..*", "0..*"],
      "name": "hasCellphone",
      "type": "association"}
	]
}
EOT;
        //TODO: Complete XML!
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Cellphone" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <!-- One person can has lots of cellphones -->

    <owl:SubClassOf>
      <owl:Class abbreviatedIRI="owl:Thing" />
      <owl:ObjectIntersectionOf>
	<owl:ObjectAllValuesFrom>
	  <owl:ObjectProperty IRI="hasCellphone" />
	  <owl:Class IRI="Person" />
	</owl:ObjectAllValuesFrom>
	<owl:ObjectAllValuesFrom>
	  <owl:ObjectInverseOf>
	    <owl:ObjectProperty IRI="hasCellphone" />
	  </owl:ObjectInverseOf>
	  <owl:Class IRI="Cellphone" />
	</owl:ObjectAllValuesFrom>
      </owl:ObjectIntersectionOf>
    </owl:SubClassOf>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();


        /*$expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);*/
        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }


    # Test generalization is translated properly.
    public function testTranslateGeneralization(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "Person"},
    {"attrs":[], "methods":[], "name": "Employee"}],
 "links": [
     {"classes": ["Employee"],
      "multiplicity": null,
      "name": "r1",
      "type": "generalization",
      "parent": "Person",
      "constraint": []}
	]
}
EOT;
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();


        /*$expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);*/
        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }

    # Test generalization with disjoint constraint is translated properly.
    public function testTranslateGenDisjoint(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "Person"},
    {"attrs":[], "methods":[], "name": "Employee"},
    {"attrs":[], "methods":[], "name": "Employer"},
    {"attrs":[], "methods":[], "name": "Director"}],
 "links": [
     {"classes": ["Employee", "Employer", "Director"],
      "multiplicity": null,
      "name": "r1",
      "type": "generalization",
      "parent": "Person",
      "constraint": ["disjoint"]}
	]
}
EOT;
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employer" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Director" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employer" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Director" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
      <owl:ObjectIntersectionOf>
        <owl:ObjectComplementOf>
          <owl:Class IRI="Employer" />
        </owl:ObjectComplementOf>
        <owl:ObjectComplementOf>
          <owl:Class IRI="Director" />
        </owl:ObjectComplementOf>
      </owl:ObjectIntersectionOf>
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="Employer" />
      <owl:ObjectComplementOf>
        <owl:Class IRI="Director" />
      </owl:ObjectComplementOf>
    </owl:SubClassOf>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        /*$expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);*/
        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }

    # Test generalization with covering constraint is translated properly.
    public function testTranslateGenCovering(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "Person"},
    {"attrs":[], "methods":[], "name": "Employee"},
    {"attrs":[], "methods":[], "name": "Employer"},
    {"attrs":[], "methods":[], "name": "Director"}],
 "links": [
     {"classes": ["Employee", "Employer", "Director"],
      "multiplicity": null,
      "name": "r1",
      "type": "generalization",
      "parent": "Person",
      "constraint": ["covering"]}
	]
}
EOT;
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd">
  <CreateKB kb="http://localhost/kb1" />
  <Tell kb="http://localhost/kb1">

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employer" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Director" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="Employee" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Employer" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Director" />
	  <owl:Class IRI="Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:ObjectUnionOf>
          <owl:Class IRI="Employee" />
          <owl:Class IRI="Employer" />
          <owl:Class IRI="Director" />
      </owl:ObjectUnionOf>
    </owl:SubClassOf>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        /*$expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);*/
        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }

    /**
      Test for checking Strategy::translate_queries method only for class.
    */
    public function test_translate_queries(){
        //TODO: Complete JSON!
        $json = <<< EOT
{"classes": [
    {"attrs":[], "methods":[], "name": "PhoneCall"},
    {"attrs":[], "methods":[], "name": "MobileCall"}],
 "links": [
     {"classes": ["MobileCall"],
      "multiplicity": null,
      "name": "r1",
      "type": "generalization",
      "parent": "PhoneCall",
      "constraint": []}
	]
}
EOT;
        $expected = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd">
    <CreateKB kb="http://localhost/kb1" />
    <Tell kb="http://localhost/kb1"/>
    <IsKBSatisfiable kb="http://localhost/kb1"/>
    <IsClassSatisfiable kb="http://localhost/kb1"><owl:Class IRI="PhoneCall"/></IsClassSatisfiable>
    <IsClassSatisfiable kb="http://localhost/kb1"><owl:Class IRI="MobileCall"/></IsClassSatisfiable>
</RequestMessage>
EOT;

        $strategy = new Berardi();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate_queries($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }
}
