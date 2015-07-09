#!/usr/bin/env bash

sed -i "s/^zend\.assertions.*/zend.assertions = 1/" $HOME/php/lib/php.ini
echo ">>> assertions enabled"
