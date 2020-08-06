<!DOCTYPE html>
<?php
//Files that need to be included for the program to work
require "../Classes/course.php";
require "../Classes/studentsInCourse.php";
require "utils.php";
//Variable for file path to csv file
$courseCsvPath = "../CsvData/courses.csv";
$fp = fopen($courseCsvPath, "r")  or die("Unable to open file!");
//get arrays/rows from csv
$courseData = array_map("str_getcsv", file($courseCsvPath));
fclose($fp);
//loop through them to create students and put into student object array called $students
$courses = createCourseObjects($courseData);
?>
<html>
    <head>
        <title>Viewing courses data!</title>
        <link rel="stylesheet" href=""/> 
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    </head>

    <body>
        <div class="mainContainer">
            <h1>Welcome to the Courses page!</h1>
            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="students.php">Check out students</a>
            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="data.php">UPLOAD DATA</a>
            <h3>Unique courses: <?php echo count($courses); ?></h3>
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
                    <th class="mdl-data-table__cell--non-numeric">Course code</th>
                    <th class="mdl-data-table__cell--non-numeric">Course name</th>
                    <th>Course year</th>
                    <th class="mdl-data-table__cell--non-numeric">Course semester</th>
                    <th class="mdl-data-table__cell--non-numeric">Instructor name</th>
                    <th>Credits</th>
                    <th>Nr. of students registered</th>
                    <th>Nr. of students passed</th>
                    <th>Nr. of students failed</th>
                    <th>Avg. grade</th>
                </thead>
                <tbody>
                    <?php
                        //Loop that maps each course object's property to a table cell                        
                        foreach($courses as $course){
                            echo "
                            <tr>
                                <td class='mdl-data-table__cell--non-numeric'>".$course->getCourseCode()."</td>
                                <td class='mdl-data-table__cell--non-numeric'>".$course->getCourseName()."</td>
                                <td>".convertUnixToYear($course->getCourseYear())."</td>
                                <td class='mdl-data-table__cell--non-numeric'>".$course->getCourseSemester()."</td>
                                <td class='mdl-data-table__cell--non-numeric'>".$course->getInstructorName()."</td>
                                <td>".$course->getNrOfCredits()."</td>
                                <td>".$course->getNrOfStudRegistered()."</td>
                                <td>".$course->getNrOfStudPassed()."</td>
                                <td>".$course->getNrOfStudFailed()."</td>
                                <td>".$course->getAvgGrade()."</td>
                            </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>

<?php
    //Function used to create final course objects
    function createCourseObjects($courseData) {
        $studInCoursePath = "../CsvData/studentsInCourse.csv";
        $fp = fopen($studInCoursePath, "r") or die("Unable to open file");
        $studentInCourseDataRows = array_map("str_getcsv", file($studInCoursePath));
        fclose($fp);
        
        $result = array();
        // FOR EACH COURSE IN THE CSV WE CREATE A COURSE
        foreach ($courseData as $row) {
            array_push($result, createCourse($row, $studentInCourseDataRows));
        }

        //sort courses by number of students in descending order
        usort($result, function($a, $b) {
            return $a->getNrOfStudRegistered() <=> $b->getNrOfStudRegistered();
        });
        return $result;
    }
    //Function that creates course objects and maps all of its properties
    function createCourse($row, $studentInCourseDataRows){
        $course = new Course($row[0], $row[1], $row[2], $row[3], $row[4]);
        $courseStudentMap = array();
        foreach ($studentInCourseDataRows as $dataRow) {
            $studInCourse = new studentInCourse($dataRow[0], $dataRow[1], $dataRow[2]);
            //if coursecode from course object and studincourse object match, push it to the map array
            if ($course->getCourseCode() == $studInCourse->getCourseCode()) {
                array_push($courseStudentMap,[
                    $studInCourse->getGrade(), 
                    $course->getCourseCode(), 
                    $studInCourse->getStudentNr(), 
                    $course->getCourseYear(), 
                    $course->getCourseSemester(),
                    $course->getNrOfCredits()]);
            }
        }
        //Coursestudentmap = all STUDENTS who have this COURSE
        //The following expression also makes all entries in the array unique
        $courseStudentMap = array_map("unserialize", array_unique(array_map("serialize", $courseStudentMap)));

        //Set missing course properties, using the coursestudentmap
        $course->setNrOfStudRegistered(count($courseStudentMap));
        $course->setNrOfStudPassed(findNrOfPassed($courseStudentMap));
        $course->setNrOfStudFailed(findNrOfFailed($courseStudentMap));
        $course->setAvgGrade(findAvgGrade($courseStudentMap, $course->getCourseCode()));

        //Return the finished course objects
        return $course;
    }
    //Function that finds average grade, using the coursemap and the coursecode of the course objects
    function findAvgGrade ($courseMap, $courseCode) {
        $nrOfGrades = 0;
        $totalGrade = 0;
    
        foreach ($courseMap as $course){
            $cmCode = $course[1];
            $gradeLetter = $course[0];

            if ($courseCode == $cmCode) {
                $nrOfGrades++;
                $totalGrade += getGrade($gradeLetter);
            }
        }
        return number_format($totalGrade / $nrOfGrades, 2);
    }
?>