<?php
$host="localhost";
$username="root";
$password="";
$dbname="project";

$conn=mysqli_connect("$host","$username","$password","$dbname");
if(!$conn)
{
    die("not connect");
}
else{
    // echo"connect successfully";
}
// mysqli_colse($conn);


?>