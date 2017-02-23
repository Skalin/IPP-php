#!/bin/bash
#mekel kekel

FILE=$1
if [ -z $1 ]; then
    FILE="project1.php"
fi
COUNT=1

function test {
    echo ""
    echo " ┏[$2]"
    $2 > /dev/null
    local status=$?
    if [ $status -ne $1 ]; then
        echo -e " ┗[$COUNT. test] \e[101m[[FAIL]]\e[49m [Valid: $1 | Actual: $status]"

    else
        echo -e " ┗[$COUNT. test] \e[42m[[OK]]\e[49m [Valid: $1 | Actual: $status]"
    fi
    COUNT=$((COUNT+1))
}


test 0 "php $FILE --format='foo' --input=kek.xml"
test 0 "php $FILE --format='foo' --input=kek"
test 1 "php $FILE --format='foo' --qf=kek"
test 1 "php $FILE --help --input=kek.xml"
test 0 "php $FILE --format='foo' --input=kek.xml"
test 1 "php $FILE --input=foo.xml --input=kek.xml"
test 1 "php $FILE --help='foo'"
test 1 "php $FILE --input='foo' --input=kek.xml --help"
test 1 "php $FILE --input=kekel.xml --output=maslo.xml --qf=kek -n --format=kekelElement"
