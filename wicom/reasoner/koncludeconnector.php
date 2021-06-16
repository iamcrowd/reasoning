<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian

   koncludeconnector.php

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

class KoncludeConnector extends Connector{


    //TODO: Change PROGRAM_CMD and FILES_PATH into configuration variables.

    /**
       The Konclude command to execute with all its parameters.
       Konclude writes the reasoning output to a file so that this function configures the commandline
       to write to an "konclude-out-file.owllink" file. This file must be created during crowd installation.
     */
    //    const PROGRAM_CMD = "Racer";
    //    const PROGRAM_PARAMS = "-- -silent -owllink ";

    const PROGRAM_CMD = "Konclude";
    const PROGRAM_PARAMS_IN = " owllinkfile -i ";
    const PROGRAM_PARAMS_OUT = " -o ";

    /**
       Execute Konclude with the given $document as input.
       Note that this function redefines run in order to convert a DOMDocumentType to DOMElement.
     */
    function run($input_string){
        $temporal_path = $GLOBALS['config']['temporal_path'];
        $konclude_path = $GLOBALS['config']['konclude_path'];

        $uuid = uniqid();

        $tmp_realpath = realpath($temporal_path);

        if (($tmp_realpath == FALSE) or (!is_writable($tmp_realpath)) ){
            throw new \Exception(
        	"Temporal path does not exists or is not  writeable. ".
        	"Check if this path exists and is writeable: '$temporal_path'."
            );
        }

        $tmp_realpath .= '/';

        $file_name = $uuid . "input-file-konclude.owllink";
        $file_path = $tmp_realpath . $file_name;

        $file_out_name = $uuid . "konclude-out-file.owllink";
        $out_file_path = $tmp_realpath . $file_out_name;
        $konclude_path .= '/' . KoncludeConnector::PROGRAM_CMD;

        if (!is_executable($konclude_path)){
            throw new \Exception(
        	"The program '$konclude_path' is not executable. " .
        	"Please, use chmod +x to make it executable.");
        }

        $commandline = $konclude_path . " " .
        	       KoncludeConnector::PROGRAM_PARAMS_IN . $file_path .
        	       KoncludeConnector::PROGRAM_PARAMS_OUT . $out_file_path;

        $owllink_file = fopen($file_path, "w");

        if (! $owllink_file) {
            throw new \Exception(
        	"Temporal file couldn't be opened for " .
        	"writing...\n Does there exist '$file_path' file?");
        }

        fwrite($owllink_file, $input_string);
        fclose($owllink_file);

        exec($commandline,$answer);

        $owllink_out_file = fopen($out_file_path, "r");

        if (! $owllink_out_file) {
            throw new \Exception(
        	"Temporal file couldn't be opened for " .
        	" writing... Does there exist '$out_file_path' file?");
        }

        $k_answer = fread($owllink_out_file, filesize($out_file_path));
        $k_answer = explode("\n", $k_answer);

        $k_answer = array_filter($k_answer, function($k) {return $k != '1';}, ARRAY_FILTER_USE_KEY);
        $k_answer_el = array_slice($k_answer, 0);

        fclose($owllink_out_file);

        unlink($file_path);
        unlink($out_file_path);

        array_push($this->col_answers, join($k_answer_el));
    }


    /**
       Check for program and input file existance and proper permissions.

       @return true always
       @exception Exception with proper message if any problem is founded.
     */
    function check_files($temporal_path, $konclude_path, $file_path){
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

      	if (!file_exists($konclude_path)){
      	    throw new \Exception("The Konclude program has not been founded...
      You told me that '$konclude_path' is the Konclude program, is this right? check your 'web-src/config/config.php' configuration file.");
      	}

      	if (!is_executable($konclude_path)){
      	    throw new \Exception("The Konclude program is not executable...
      Is the path '$konclude_path' right? Is the permissions setted properly?");
      	}

      	return true;
          }


      function run_converter($document, $si, $so){}
}
?>
