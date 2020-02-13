<?php 
/* 

   Copyright 2020 GILIA
   
   Author: GILIA 

   v1-validate.php
   
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

namespace JSONValidators;

use function \load;
use Opis\JsonSchema\{
    Validator, ValidatorResult, ValidationError, Schema
};
use function \json_decode;
use function \jsone_encode;

/**
   Validate a V1 JSON.
 */
class V1Validator {

    /**
       An Opis::Schema instance with the loaded V1 schema.
     */
    protected $schema = null;

    /**
       An Opis::Validator instance.
     */
    protected $validator = null;

    /**
       The Opis::ValidationResult instance. This is the last validation result.
     */
    protected $results = null;
    
    /**
       @param $input [string] A JSON string.
     */
    function __construct($input=''){
        $this->schema = Schema::fromJsonString(
            file_get_contents(__DIR__ . './v1-schema.json'));
        $this->input = json_decode($input);
        $this->validator = new Validator();
    }

    /**
       @return [boolean] True if the input is successful, False otherwise.
     */
    function validate() {
        $this->results = $this->validator->schemaValidation(
            $this->input, $this->validator);

        return $this->results->isValid();       
    }

    /**
       Generate the error reports in string.

       @return An array of strings with the error descriptions.
     */
    function get_errors(){
        if ($this->results == null){
            return [];
        }
        
        $arr = [];
        $lst_errors = $this->results->getErrors();

        foreach ($lst_errors as $error){
            $arr[] = "Error: " . $error->keyword .
                     json_encode($error->keywordArgs(), JSON_PRETTY_PRINT);
        }
        
        return $arr;
    }
}

?>
