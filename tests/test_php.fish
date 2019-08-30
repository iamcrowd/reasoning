#! /usr/bin/fish


# Copyright 2016 Giménez, Christian

# Author: Giménez, Christian   

# test_php.fish

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

cd php

##
# Files that are not unit tests.
#
# Add here (separate with spaces, not "," nor ":") files that you
# don't want to execute as test.
set exceptions "./common.php"

function execute_test --description "execute_test [TESTNAME]

Execute all the test if no parameter is given.
If TESTNAME is present, then execute the first test founded at the subdirectories called TESTNAMEtest.php.
"
	echo "----------------------------------------------------------------------------------------------------"
	set_color -o  ;	echo $argv[1]
	set_color normal
	if test -f ../run/input-file.owllink
		echo "found ../temp/input-file.owllink, changing its name..."
		mv ../run/input-file.owllink ../temp/input-file.(date +%F-%T).owllink
	end
	phpunit --colors=always --include ../../web-src $argv[1]
end

if test -z "$argv[1]" 
	for testfile in (find -name '*.php')
		if not contains $testfile $exceptions
			execute_test $testfile
		end
	end
else
	set testfile (find -name  "$argv[1]test.php")
	if test ! -z "$testfile"
		execute_test $testfile
	end
end
