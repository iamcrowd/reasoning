#! /bin/bash

 
# Copyright 2016 Giménez, Christian

# Author: Giménez, Christian   

# test-satisf-json.sh

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

curl -d 'json={"classes": [{"attrs":[], "methods":[], "name": "Hi World"}]}' http://localhost/html/wicom/web-src/api/querying/satisfiable.php
