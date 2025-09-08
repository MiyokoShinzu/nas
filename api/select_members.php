<?php
include '../src/connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = 'Select * from nas_members';
    $result = $mysqli->query($sql);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
$mysqli->close();
