<?php
/**
Common functions used for all tests.

Copyright 2016 Giménez, Christian. Germán Braun

Author: Giménez, Christian

common.php

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

require_once __DIR__ . "/../../common/import_functions.php";


/**
   Remove multiple spaces and newlines replacing it with only one space.

   Replaces tabs, spaces and newlines with only one space.

   @param $str String with or without multiple spaces to process.

   @return The same given string but with one spaces per multiple spaces/newlines.
 */
function multiplespaces_to_one($str)
{
    return preg_replace('/[[:blank:][:space:]]+/im', " ", $str);

}//end multiplespaces_to_one()


/**
   Process XML for testing with PHPUnit.

   @param $xmlstr XML String of a DOMDocument.

   @deprecated assertEqualXMLStructure() is now deprecated in favor of
     assertXMLStringEqualsXMLString()

   @return A DOMElement for using with assertEqualXMLStructure()
 */
function process_xmlspaces($xmlstr)
{
    $out = new \DOMDocument;
    $out->loadXML($xmlstr);
    return $out->firstChild;

}//end process_xmlspaces()


/**
   Process XML for testing with PHPUnit.

   @param $xmlstr XML String of a DOMDocumentType.

   @deprecated assertEqualXMLStructure() is now deprecated in favor of
     assertXMLStringEqualsXMLString()

   @return A DOMElement for using with assertEqualXMLStructure()
 */
function process_xmlspaces_DOMDocType($xmlstr)
{
    $out = new \DOMDocument;
    $out->loadXML($xmlstr);
    return $out->firstChild->nextSibling;

}//end process_xmlspaces_DOMDocType()
