<?php

include("./db/connection.php");

$id = $_GET["id"]; 


$select = "delete  FROM student WHERE id = $id";
$A = mysqli_query($conn, $select);


if ($A!=null) {
    header("location:table.php");
} else {
    echo "can not delete";
}
?>