<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   htmlbuildertesst.php

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
load("htmlbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Builders\HTMLBuilder;

class HTMLBuilderTest extends PHPUnit\Framework\TestCase
{

    public function testTranslate(){
        $expected = trim(file_get_contents('translator/data/html_translate.html'));

        $builder = new HTMLBuilder();

        $builder->insert_header();
        $builder->translate_DL([
            ["subclass" => [
                ["class" => "Persona"],
                ["class" => "owl:Thing"],
            ]],
            ["subclass" => [
                ["class" => "Cellphone"],
                ["class" => "owl:Thing"]]],
            ["subclass" => [
                ["exists" => ["role" => "hasCellphone"]],
                ["class" => "Person"]]],
            ["subclass" => [
                ["exists" => ["inverse" =>
                              ["role" => "hasCellphone"]]],
                ["class" => "Cellphone"]]],
            ["subclass" => [
                ["class" => "Person"],
                ["mincard" =>
                 [1,
                  ["role" => "hasCellphone"]]]]],
            ["subclass" => [
                ["class" => "Cellphone"],
                ["intersection" => [
                    ["mincard" =>
                     [1,
                      ["inverse" => ["role" => "hasCellphone"]]]],
                    ["maxcard" =>
                     [1,
                      ["inverse" => ["role" => "hasCellphone"]]]]
                ]]]],
            ["subclass" => [
                ["class" => "owl:Thing"],
                ["intersection" => [
                    ["forall" => [
                        ["role" => "Rolename"],
                        ["class" => "Classname"]]],
                    ["forall" => [
                        ["inverse" =>
                         ["role" => "Rolename"]],
                        ["class" => "Class2"]]]
                ]] //intersection
            ]] //subclass
        ]);

        $builder->insert_footer();
        $actual = $builder->get_product();
        $actual = $actual->to_string();

        # $expected = process_xmlspaces($expected);
        # $actual = process_xmlspaces($actual);
        $this->assertEquals($expected, $actual, true);
    }

}

?>
