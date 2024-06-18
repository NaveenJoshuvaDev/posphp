<?php

session_start();
require 'dbcon.php';

// Input validate($inputData)
function validate($inputData) {
    global $conn;
    return trim(mysqli_real_escape_string($conn, $inputData));
}

// Redirect from one page to another page with a message (status)
function redirect($url, $status) {
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}

// Display messages or status after any process
function alertMessage() {
    if (isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
              <h6>' . $_SESSION['status'] . '</h6>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['status']);
    }
}

// Insert record using this function
function insert($tableName, $data) {
    global $conn;

    $table = validate($tableName);
    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = implode("', '", array_map(function($value) use ($conn) {
        return mysqli_real_escape_string($conn, $value);
    }, $values));

    $query = "INSERT INTO $table ($finalColumn) VALUES ('$finalValues')";
    return mysqli_query($conn, $query);
}

// Update data using this function
function update($tableName, $id, $data) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);
    $updateDataString = "";

    foreach ($data as $column => $value) {
        $value = mysqli_real_escape_string($conn, $value);
        $updateDataString .= "$column='$value',";
    }

    $finalUpdateData = rtrim($updateDataString, ',');
    $query = "UPDATE $table SET $finalUpdateData WHERE id='$id'";

    return mysqli_query($conn, $query);
}

// Get all records from a table
function getAll($tableName, $status = NULL) {
    global $conn;

    $table = validate($tableName);
    if ($status == 'status') {
        $query = "SELECT * FROM $table WHERE status='0'";
    } else {
        $query = "SELECT * FROM $table";
    }

    return mysqli_query($conn, $query);
}

// Get a record by ID
function getById($tableName, $id) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            return [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];
        } else {
            return [
                'status' => 404,
                'message' => 'No Data Found'
            ];
        }
    } else {
        return [
            'status' => 500,
            'message' => 'Something went wrong'
        ];
    }
}

// Delete data from database using ID
function delete($tableName, $id) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
    return mysqli_query($conn, $query);
}

function checkParamId($type){
if(isset($_GET[$type])){
     if($_GET[$type] != ''){
         return $_GET[$type];
     }else{
        return '<h5>No Id Given</h5>';
     }
}
else {
    return '<h5>No Id Given</h5>';
}
}

function logoutSession()
{
    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);

}