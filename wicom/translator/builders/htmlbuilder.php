<?php 
/* 

   Copyright 2016 Giménez, Christian
   
   Author: Giménez, Christian   

   htmlbuilder.php
   
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

namespace Wicom\Translator\Builders;

use function \load;
load("documentbuilder.php");
load("htmldocument.php", "../documents/");

use Wicom\Translator\Documents\HTMLDocument;

class HTMLBuilder extends DocumentBuilder{
    function __construct(){
        $this->product = new HTMLDocument();
    }

    public function insert_class($name, $col_attrs = []){
        $this->product->insert_class($name);
    }

    public function insert_subclassof($child, $father){
        $this->product->insert_subclassof($child, $father);
    }

    public function insert_header(){
    }
    public function insert_footer(){
        $this->product->end_document();
    }

    /**
       @name Queries
    */
    ///@{
    public function insert_satisfiable(){
    }
    public function insert_satisfiable_class($classname){
    }
    ///@}

    /**
       @name DL List 
    */
    ///@{

    function translate_DL($DL_list){
        foreach ($DL_list as $elt){
            $this->product->begin_line();
            $this->DL_element($elt);
            $this->product->end_line();
        }
        
    }

    protected function translate_DL_internal($DL_list){
        foreach ($DL_list as $elt){
            $this->DL_element($elt);
        }
    }
    
    protected function DL_element($elt){
        $key = array_keys($elt)[0];

        switch ($key){
        case "class" :
            $this->product->insert_class($elt["class"]);
            break;
        case "role" :
            $this->product->insert_objectproperty($elt["role"]);
            break;
        case "subclass" :
            $this->product->begin_subclassof();
            // We expect various consecutives DL cexpressions 
            // (two classes for example)
            $this->translate_DL_internal($elt["subclass"]);
            $this->product->end_subclassof();
            break;
        case "intersection" :
            $this->product->begin_intersectionof();
            $this->translate_DL_internal($elt["intersection"]);
            $this->product->end_intersectionof();
            break;
        case "union" :
            $this->product->begin_unionof();
            $this->translate_DL_internal($elt["union"]);
            $this->product->end_unionof();
            break;
        case "complement" :
            $this->product->begin_complementof();
            $this->DL_element($elt["complement"]);
            $this->product->end_complementof();
            break;
        case "inverse" :
            $this->product->begin_inverseof();
            // We expect one DL expression
            // (the inverse of the inverse of the role for example,
            // but not one role, and one inverse of another role).
            $this->DL_element($elt["inverse"]);
            $this->product->end_inverseof();
            break;
        case "exists" :
            $this->product->begin_somevaluesfrom();
            $this->DL_element($elt["exists"]);
            $this->product->insert_class("owl:Thing");
            $this->product->end_somevaluesfrom();
            break;
        case "forall" :
            $this->product->begin_allvaluesfrom();
            $this->DL_element($elt["forall"][0]);
            $this->DL_element($elt["forall"][1]);
            $this->product->end_allvaluesfrom();
            break;
        case "mincard" :
            $this->product->begin_mincardinality($elt["mincard"][0]);
            $this->DL_element($elt["mincard"][1]);
            $this->product->end_mincardinality();
            break;
        case "maxcard" :
            $this->product->begin_maxcardinality($elt["maxcard"][0]);
            $this->DL_element($elt["maxcard"][1]);
            $this->product->end_maxcardinality();
            break;
        default:
            throw new \Exception("I don't know $key DL operand");
        }
    }
    //@}
   
}

?>
