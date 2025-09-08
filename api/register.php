<?php
date_default_timezone_set('Asia/Manila');
$d = date('Y-m-d H:i:s');
include '../src/connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $firstname = $_GET['firstname'];
    $lastname = $_GET['lastname'];
    $middlename = $_GET['middlename'];
    $year = $_GET['year'];
    $section = $_GET['section'];
    $email = $_GET['email'];
    $contact = $_GET['contact'];
    $suffix = $_GET['suffix'];
    $suggestion = $_GET['suggestion'];

    $sql = "Insert into nas_members(firstname, lastname, middlename, year, section, email, contact, suffix, suggestion, datetime_registered, status, access, is_member) 
    values('$firstname', '$lastname', '$middlename', '$year', '$section', '$email', '$contact', '$suffix', '$suggestion', '$d', 0, 0, 0)";
    $result = $mysqli->query($sql);
    if ($result) {
        echo json_encode(['success' => '1']);
    } else {
        echo json_encode(['success' => '0']);
    }
}
$mysqli->close();
?>
