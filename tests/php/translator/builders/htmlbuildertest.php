<?php
/**
HTML Builder tests.

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

PHP version >= 7.2

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */


require_once __DIR__ . '/../../common.php';
require_once __DIR__ . '/../../../../wicom/translator/builders/htmlbuilder.php';

use Wicom\Translator\Builders\HTMLBuilder;

/**
HTML Builder tests.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class HTMLBuilderTest extends PHPUnit\Framework\TestCase
{


    /**
    Create an HTML interpretation from a middle hashed representation

    @return Nothing.
     */
    public function testTranslate()
    {
        $expected = trim(
            file_get_contents(__DIR__ . '/../data/html_translate.html')
        );

        $subclass1 = [
            "subclass" => [
                [
                    "exists" => [
                        "inverse" => ["role" => "hasCellphone"],
                    ],
                ],
                ["class" => "Cellphone"],
            ],
        ];

        $subclass2 = [
            "subclass" => [
                ["class" => "Person"],
                [
                    "mincard" => [
                        1,
                        ["role" => "hasCellphone"],
                    ],
                ],
            ],
        ];

        $subclass3 = [
            "subclass" => [
                ["class" => "Cellphone"],
                [
                    "intersection" => [
                        [
                            "mincard" => [
                                1,
                                ["inverse" => ["role" => "hasCellphone"]],
                            ],
                        ],
                        [
                            "maxcard" => [
                                1,
                                ["inverse" => ["role" => "hasCellphone"]],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $subclass4 = [
            "subclass" => [
                ["class" => "owl:Thing"],
                [
                    "intersection" => [
                        [
                            "forall" => [
                                ["role" => "Rolename"],
                                ["class" => "Classname"],
                            ],
                        ],
                        [
                            "forall" => [
                                [
                                    "inverse" => ["role" => "Rolename"],
                                ],
                                ["class" => "Class2"],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $builder = new HTMLBuilder();

        $builder->insert_header();
        $builder->translate_DL(
            [
                [
                    "subclass" => [
                        ["class" => "Persona"],
                        ["class" => "owl:Thing"],
                    ],
                ],
                [
                    "subclass" => [
                        ["class" => "Cellphone"],
                        ["class" => "owl:Thing"],
                    ],
                ],
                [
                    "subclass" => [
                        ["exists" => ["role" => "hasCellphone"]],
                        ["class" => "Person"],
                    ],
                ],
                $subclass1,
                $subclass2,
                $subclass3,
                $subclass4,
            ]
        );

        $builder->insert_footer();
        $actual = $builder->get_product();
        $actual = $actual->to_string();

        // $expected = process_xmlspaces($expected);
        // $actual = process_xmlspaces($actual);
        $this->assertEquals($expected, $actual, true);

    }//end testTranslate()


}//end class
