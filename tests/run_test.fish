#! /usr/bin/fish

# Script used by Trun.

cd php
set f (find -name '*test.php')
set out 0
for i in $f
    phpunit --stop-on-error --colors=always --testdox --include ../../ $i
    if test $status -ne 0
	set out 1
    end
end
exit $out
