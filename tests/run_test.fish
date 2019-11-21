#! /usr/bin/fish

# Copyright 2016 Giménez, Christian

# Author: Giménez, Christian   

# run_test.fish

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


# Script used by Trun.

cd php
set f (find -name '*test.php')
set out 0
for i in $f
    if test -z (echo "$i" | grep '.*connector.*')
        phpunit --stop-on-error --colors=always --testdox --include ../../ $i
        if test $status -ne 0
	    set out 1
        end
    end 
end
exit $out

