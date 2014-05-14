<?php

$link = mysqli_init();
if ( !mysqli_real_connect($link, "localhost", "tester", "bubble", "tester") ) {
    throw new Exception("Could not connect: ". mysqli_error());
}

$sql = "insert into bubbles set name='bob'";

if ( !mysqli_real_query($link,$sql) ) {
    throw new Exception("MySQL error occurred: " . mysql_error());
}

echo "All good, inserted a row\n";
