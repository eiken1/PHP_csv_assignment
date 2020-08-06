<!DOCTYPE html>
<html>
    <head>
        <title>Upload CSV files!</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    </head>

    <body>
        <h1>Welcome to the CSV upload page!</h1>
        <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="students.php">Check out students</a>
        <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="courses.php">Check out courses</a>
        <table width="600px"> 
            <form action="data.php" method="post" enctype="multipart/form-data">

                <tr>
                    <td width="20%">Select file</td>
                    <td width="80%"><input type="file" name="csvFile" id="csvFile" /></td>
                </tr>

                <tr>
                    <td>Submit</td>
                    <td><input type="submit" name="submit" /></td>
                </tr>

            </form>
        </table>
    </body>
</html>

<?php
include ("../Classes/student.php");

//Function that uses getCsvFromFile to collect file contents, 
//then convert the content into CSVV data
function getCsvData($path){
    $result = array();
    $rows = getCsvFromFile($path);
    foreach ($rows as $row) {
        $result[] = str_getcsv($row);
    }
    return $result;
}

//Collects csv data from the location specified in the functions parameters
function getCsvFromFile($path){
    $csvData = file_get_contents($path);
    return explode(PHP_EOL, $csvData);
}

//Function that first, maps the data given in $path, 
//then it pushes the array given in the functions parameter
//to the end of the existing data array
//Then we put the result into $result, at the same time
//We make all the data in the result array unique, before
//converting the result array to csv and writing it into the file
//specified in $path
function writeToCsv($path, $arrays){
    //using w because we want to get rid of old data.
    $fp = fopen($path, "w+")  or die("Unable to open file!");
    $existingData = array_map("str_getcsv", file($path));
    foreach ($arrays as $newRow){
        array_push($existingData, $newRow);
    }
    //set all rows in the array to be unique
    $result = array_map("unserialize", array_unique(array_map("serialize", $existingData)));
    foreach($result as $row){
        fputcsv($fp, trimValues($row));
    }
    fclose($fp);
} 

//Function that removes whitespace in arrays/csv file
function trimValues($row){
    $result = array();
    foreach($row as $value){
        array_push($result, ltrim($value, " "));
    }
    return $result;
}

//Array consisting of csv file types
$csvFileTypes = array(
    'text/csv',
    'text/plain',
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    'text/anytext',
    'application/octet-stream',
    'application/txt',
);

//Function that validates csv files and ensures no data is duplicated
function validateArrays ($array) {
    $array = array_map("unserialize", array_unique(array_map("serialize", $array)));

    return $array;
}

//If a file has been submitted, proceed
if ( isset($_POST["submit"]) ) {

    //If that file is an acceptable csv file type
    if (in_array($_FILES["csvFile"]["type"], $csvFileTypes)) {
        echo "<br>";
        echo "File is an acceptable CSV!";
        //if there was an error uploading the file
        if ($_FILES["csvFile"]["error"] > 0) { 
            echo "Return Code: " . $_FILES["csvFile"]["error"] . "<br />";
        } else if ($_FILES["csvFile"]["size"] > 0) {
            echo "<br>";
            echo "No errors opening file!";
            echo "<br>";
            echo "File is opened!";
            
            $CsvFile = $_FILES["csvFile"]["tmp_name"];

            //Get CSV data from input file and map it to an array
            $csvArray = getCsvData($CsvFile);
            //Create arrays
            $studArray = array();
            $courseArray = array();
            $studInCourseArray = array();

            $studCsvPath = "../CsvData/student.csv";
            $coursesCsvPath = "../CsvData/courses.csv";
            $studInCoursePath = "../CsvData/studentsInCourse.csv";

            foreach ($csvArray as $arr) {
                //converts dates to unix
                $arr[3] = strtotime($arr[3]); // birthdate
                $arr[5] = strtotime($arr[5]); // courseyear

                //divides data in to respective arrays
                $studArray[] = array_slice($arr, 0, 4);
                $courseArray[] = array_slice($arr, 4, 5);
                $studInCourseArray[] = array($arr[0], $arr[4], $arr[9]);
            }

            //Validate each of the csv arrays
            validateArrays($studArray);
            validateArrays($courseArray);
            validateArrays($studInCourseArray);

            //Use the sliced arrays to write CSV into several files
            writeToCsv($studCsvPath, $studArray);
            writeToCsv($coursesCsvPath, $courseArray);
            writeToCsv($studInCoursePath, $studInCourseArray);
        }
    } else {
         echo "The uploaded file is not an acceptable CSV file type!";
    }
}

?>