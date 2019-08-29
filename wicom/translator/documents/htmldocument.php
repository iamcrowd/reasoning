<?php 
/* 

   Copyright 2016 Giménez, Christian
   
   Author: Giménez, Christian   

   htmldocument.php
   
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

use function \load;
load('document.php');

class HTMLDocument extends Document{
    protected $content = null;

    function __construct(){
        $this->content = "";
        $this->operand = "";
    }

    function begin_line(){
        $this->content .= "<p>";
    }
    function end_line(){
        $this->content .= "</p>\n";
    }
    
    public function insert_class($name){
        if ($name == "owl:Thing"){
            $this->content .= "&#8868; ";
        }else{
            if ($name == "owl:Nothing") {
                $this ->content .= "&#8869; ";
            }else{
                $this->content .= "$name ";
            }
        }
    }

    public function insert_subclassof($child_class, $father_class){
        // replace spaces
        $child_class = str_replace(" ", "_", $child_class);
        $father_class = str_replace(" ", "_", $father_class);
        $this->content .= "<p>$child_class &#8849; $father_class</p>";
    }

    public function insert_objectproperty($name){
        $this->content .= "$name " . $this->operand;
    }

    /**
       @param $only_elt A boolean. Is true if the inverse applies only to a single element.
     */
    public function begin_inverseof(){
        $this->content .= "(";
    }
    public function end_inverseof(){
        $this->content .= ")<sup>-</sup> ";
    }

    public function begin_subclassof(){
        $this->content .= "<b>&#8849;</b>(";
    }
    public function end_subclassof(){
        $this->content .= ")";
    }

    public function begin_intersectionof(){
        $this->content .= "<b>&sqcap;</b>(";
    }
    public function end_intersectionof(){
        $this->content .= ")";
    }

    public function begin_unionof(){
        $this->content .= "<b>&sqcup;</b>(";
    }
    public function end_unionof(){
        $this->content .= ")";
    }

    public function begin_complementof(){
        $this->content .= "<b>&not;</b>(";
    }
    public function end_complementof(){
        $this->content .= ")";
    }

    public function begin_somevaluesfrom(){
        $this->content .= "&exist;(";
    }
    public function end_somevaluesfrom(){
        $this->content .= ")";
    }

    public function begin_allvaluesfrom(){
        $this->content .= "&forall;(";
    }
    public function end_allvaluesfrom(){
        $this->content .= ")";        
    }

    public function begin_mincardinality($cardinality){
        $this->content .= "(&ge; $cardinality.";
    }
    public function end_mincardinality(){
        $this->content .= ")";
    }

    public function begin_maxcardinality($cardinality){
        $this->content .= "(&le; $cardinality.";
    }
    public function end_maxcardinality(){
        $this->content .= ")";
    }
    
    public function end_document(){
        $this->content = str_replace("  ", " ", $this->content);
        $this->content = str_replace("( ", "(", $this->content);
        $this->content = str_replace(" ( ", "(", $this->content);
        $this->content = str_replace(" )", ")", $this->content);
        $this->content = str_replace(") ", ")", $this->content);
        $this->content = trim($this->content);
    }
    
    public function to_string(){
        return $this->content;
    }

    /**
       I remove the last operand inserted from the string.

       I just remove the last nth characters, don't check if it is the operand
       really.
     */
    protected function remove_operand(){
        $length = strlen($this->operand);
        $this->content = substr_replace($this->content, "", -$length);
    }
}
?>
