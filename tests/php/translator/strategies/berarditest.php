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


    /**
       @testdox Translate a simple class into OWL 2
     */
    public function testTranslate(){
        //TODO: Complete JSON!
        $json = file_get_contents(__DIR__ .
                                  '/data/berarditest/translate.json');
        //TODO: Complete XML!
        $expected = file_get_contents(__DIR__ .
                                      '/data/berarditest/translate.xml');
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


    /**
       Test if translate works properly with binary roles.

       @testdox Translate a binary role into OWL 2
     */
    public function testTranslateBinaryRoles(){
        //TODO: Complete JSON!
        $json = file_get_contents(__DIR__ .
                                  '/data/berarditest/translate_binary_roles.json');
        //TODO: Complete XML!
        $expected = file_get_contents(__DIR__ .
				      '/data/berarditest/translate_binary_roles.xml');

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

    /**
       Test if 0..* to 0..* associations is translated properly.

       @testdox Translate a many to many role into OWL 2
     */
    public function testTranslateRolesManyToMany(){
        //TODO: Complete JSON!
        $json = file_get_contents(
	    __DIR__ .
	    '/data/berarditest/translate_roles_many_to_many.json');
        //TODO: Complete XML!
        $expected = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_roles_many_to_many.xml');
	
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
       Test generalization is translated properly.
       
       @testdox Translate a generalization into OWL 2
     */
    public function testTranslateGeneralization(){
        //TODO: Complete JSON!
        $json = file_get_contents(
	    __DIR__ .
	    '/data/berarditest/translate_generalization.json');
        $expected = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_generalization.xml');
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
       Test generalization with disjoint constraint is translated properly.

       @testdox Translate a disjoint generalizatio into OWL 2
     */
    public function testTranslateGenDisjoint(){
        //TODO: Complete JSON!
        $json = file_get_contents(
	    __DIR__ .
	    '/data/berarditest/translate_gen_disjoint.json');
        $expected = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_gen_disjoint.xml');
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
       Test generalization with covering constraint is translated properly.

       @testdox Translate a covering generalization into OWL 2
     */
    public function testTranslateGenCovering(){
        //TODO: Complete JSON!
        $json = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_gen_covering.json');
        $expected = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_gen_covering.xml');

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

       @testdox Generate standard queries in OWL 2
     */
    public function test_translate_queries(){
        //TODO: Complete JSON!
        $json =file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_queries.json');
        $expected = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_queries.xml');
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

    /**      

       @testdox Insert OWNlink from JSON into XML.
     */
    public function test_translate_owllink(){
        //TODO: Complete JSON!
        $json =file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_owllink.json');
        $expected = file_get_contents(
	    __DIR__ .
            '/data/berarditest/translate_owllink.xml');
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
