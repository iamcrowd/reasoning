<?php
/*

   Copyright 2018 GILIA

   Author: GILIA

   sparqldlconnector.php

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

namespace Wicom\Reasoner;

load("connector.php");
load("config.php", "../../config/");

use Wicom\Reasoner\Connector;
use DOMDocument;
use SimpleXMLElement;

class SparqldlConnector extends Connector{


    //TODO: Change PROGRAM_CMD and FILES_PATH into configuration variables.

    /**
       The SPARQL-DL command is for executing with all its parameters.
       SPARQL-DL command executes SPARQL-DL queries on an OWL 2 Ontology and
       writes the output into "sparqldl-out-file.json" file.
       This file must be created during crowd installation.

       output format:

       [0]=> array(2) {
              ["head"]=> array(1) {
                    ["vars"]=> array(2) {
                            [0]=> string(3) "op1"
                            [1]=> string(3) "op2"
                            }
                  }
              ["results"]=> array(1) {
                            ["bindings"]=> array(1) {
                                            [0]=>
                                            array(2) {
                                              ["op1"]=> array(2) {
                                                ["type"]=> string(3) "uri"
                                                ["value"]=> string(22) "http://localhost/kb1#R"
                                              }
                                              ["op2"]=> array(2) {
                                                ["type"]=> string(3) "uri"
                                                ["value"]=> string(22) "http://localhost/kb1#R"
                                              }
                                            }
                            }
            }
          }

       File include the following tags:

       class -> Class
       objectproperty -> ObjectProperty
       dataproperty -> DataProperty
       strictsub -> StrictSubClassOf
       directsub -> DirectSubClassOf



     */

    const PROGRAM_CMD = "de-derivo-sparqldlapi-3.0.0.jar";
    const PROGRAM_PARAMS_IN = "de.derivo.sparqldlapi.examples.Sparql_dl_crowd";
    const PROGRAM_LIB = "lib/*";

    /**
       Execute Konclude with the given $document as input.
       Note that this function redefines run in order to convert a DOMDocumentType to DOMElement.
       @return An array of SPARQL-DL answers
     */
    function run($input_string){
        $temporal_path = $GLOBALS['config']['temporal_path'];
        $sparqldl_path = $GLOBALS['config']['sparqldl_path'];
        $lib_path = "'" . $sparqldl_path . SparqldlConnector::PROGRAM_LIB . "'";

        $file_name_onto = uniqid() . "crowd.owl";
        $file_name_res = uniqid() . "crowdsparqldl.json";

        $temporal_path = realpath($temporal_path) . "/";
        $in_file_path = $temporal_path . $file_name_onto;
        $out_file_path = $temporal_path . $file_name_res;
        $sparqldl_path .= SparqldlConnector::PROGRAM_CMD;
        $sparqldl = "java -cp " . $sparqldl_path . ":" . $lib_path . ":. ";
        $commandline = $sparqldl . SparqldlConnector::PROGRAM_PARAMS_IN . " " . $in_file_path . " " . $out_file_path;

        $owl2_file = fopen($in_file_path, "w");

        if (! $owl2_file) {
            throw new \Exception("Temporal file couldn't be opened for writing...
Does there exist '$file_path' file?");
        }

        // Generate OWL file to be sent to SPARLQL-DL-API
        $owl_input = new SimpleXMLElement($input_string);

        $owl = $owl_input->asXML();

        fwrite($owl2_file, $owl);
        fclose($owl2_file);

        // Clear output file json
        $owl2_out_file = fopen($out_file_path, "w");
        fclose($owl2_out_file);

        exec($commandline,$answer);

        $owl2_out_file = fopen($out_file_path, "r");
        $sparqldl_answer = fread($owl2_out_file, filesize($out_file_path));
        fclose($owl2_out_file);

        unlink($in_file_path);
        unlink($out_file_path);

        $res = explode("queryresults:",$sparqldl_answer);

        // split file content into an array and removing first empty element

        foreach ($res as $answer){
          if ($answer != ""){
            $array_ans = json_decode($answer, true);
            array_push($this->col_answers, $array_ans);
          }
        }
    }


    /**
       Check for program and input file existance and proper permissions.

       @return true always
       @exception Exception with proper message if any problem is founded.
    */
    function check_files($temporal_path, $sparqldl_path, $file_path){
        if (! is_dir($temporal_path)){
            throw new \Exception("Temporal path desn't exists!
Are you sure about this path?
temporal_path = \"$temporal_path\"");
        }

        if (!file_exists($file_path)){
            throw new \Exception("Temporal file doesn't exists, please create one at '$file_path'.");
        }

        if (!is_readable($file_path)){
            throw new \Exception("Temporal file cannot be readed.
Please set the write and read permissions for '$file_path'");
        }

        if (file_exists($file_path) and !is_writable($file_path)){
            throw new \Exception("Temporal file is not writable, please change the permissions.
Check the permissions on '${file_path}'.");
        }

        if (!file_exists($sparqldl_path)){
            throw new \Exception("The SPARLQL-DL program has not been founded...
You told me that '$sparqldl_path' is the SPARLQL-DL program, is this right? check your 'web-src/config/config.php' configuration file.");
        }

/*        if (!is_executable($sparqldl_path)){
            throw new \Exception("The SPARLQL-DL program is not executable...
Is the path '$sparqldl_path' right? Is the permissions setted properly?");
        }*/

        return true;
    }
}
?>
