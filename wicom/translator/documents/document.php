<?php 
/* 

   Copyright 2016 Giménez, Christian
   
   Author: Giménez, Christian   

   document.php
   
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

namespace Wicom\Translator\Documents;

/**
   I'm a document representation.

   Use like this:

   @code{.php}
   $d = new XDocument();

   $d->insert_something();
   ...
   $d->insert_something_else();

   $d->end_document();
   @endcode
 */
abstract class Document{
    abstract function to_string();
    
    /**
       I finish writing the document, any insert after using this 
       message can make errors.
     */
    abstract function end_document();
}
?>
