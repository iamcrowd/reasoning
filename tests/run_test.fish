#! /usr/bin/fish

# Script used by Trun.

cd php
set f (find -name '*test.php')
# phpunit --stop-on-error --colors=always --testdox --include ../../ $f
echo
