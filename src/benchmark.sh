#!/bin/bash

output=/var/www/laravel/public/benchmark.csv

echo "url;requests per second" > $output

urls="bench1.php bench2.php bench3 bench4"

for u in $urls; do
	ab -n1000 -c10 "http://localhost/${u}" > /tmp/output

	rps=$(perl -ne '/Requests per second:\s*([0-9.]*)/ && print "$1\n"' < /tmp/output)

	echo "$u;$rps" >> $output
done

