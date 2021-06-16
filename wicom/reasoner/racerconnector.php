<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   racerconnector.php

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

class RacerConnector extends Connector{


    //TODO: Change PROGRAM_CMD and FILES_PATH into configuration variables.

    /**
       The Racer command to execute with all its parameters.
     */
    const PROGRAM_CMD = "Racer";
    const PROGRAM_PARAMS = "-- -silent -owllink ";

    /**
       Execute Racer with the given $document as input.
     */
    function run($input_string){
        $temporal_path = $GLOBALS['config']['temporal_path'];
        $racer_path = $GLOBALS['config']['racer_path'] . '/';

        $uuid = uniqid();

        $tmp_realpath = realpath($temporal_path);

      	if (($tmp_realpath == FALSE) or (!is_writable($tmp_realpath)) ) {
      	    throw new \Exception(
      		"Temporal path does not exists or is not  writeable. ".
      		"Check if this path exists and is writeable: '$temporal_path'."
      	    );
      	}
      	$tmp_realpath .= '/';

      	$file_name = $uuid . "input-file-racer.owllink";
              $file_path = $tmp_realpath . $file_name;
              $racer_path .= RacerConnector::PROGRAM_CMD;

      	if (!is_executable($racer_path)){
      	    throw new \Exception(
      		"The program is not executable. " .
      		"Please, use chmod +x to make it executable.");
      	}

              $commandline = $racer_path . " " . RacerConnector::PROGRAM_PARAMS .
      		       $file_path;


              $owllink_file = fopen($file_path, "w");

              if (! $owllink_file){
                  throw new \Exception(
      		"Temporal file couldn't be opened for " .
      		"writing... \n Is the path '$file_path' correct?");
              }

              fwrite($owllink_file, $input_string);
              fclose($owllink_file);

              exec($commandline, $answer);

              unlink($file_path);

              array_push($this->col_answers, join($answer));
    }

    /**
       Check for program and input file existance and proper permissions.

       @return true always
       @exception Exception with proper message if any problem is founded.
    */
    function check_files($temporal_path, $racer_path, $file_path){
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

        if (!file_exists($racer_path)){
            throw new \Exception("The Racer program has not been founded...
You told me that '$racer_path' is the Racer program, is this right? check your 'web-src/config/config.php' configuration file.");
        }

        if (!is_executable($racer_path)){
            throw new \Exception("The Racer program is not executable...
Is the path '$racer_path' right? Is the permissions setted properly?");
        }

        return true;
    }

    function run_converter($document, $si, $so){}
}

?>
