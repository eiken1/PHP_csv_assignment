<!DOCTYPE html>
<?php
    //Files that are needed for the program to run
    require "../Classes/student.php";
    require "../Classes/course.php";
    require "../Classes/studentsInCourse.php";
    require "utils.php";

    //Variable for file path to csv file
    $studCsvPath = "../CsvData/student.csv";
    $fp = fopen($studCsvPath, "r")  or die("Unable to open file!");
    //get arrays/rows from csv
    $studentData = array_map("str_getcsv", file($studCsvPath));
    fclose($fp);

    //loop through studentdata to create students and put into student object array called $students
    $students = createStudents($studentData);
?>
<html>
    <head>
        <title>Viewing student data!</title>
        <link rel="stylesheet" href=""/> 
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    </head>

    <body>
        <div class="mainContainer">
            <h1>Welcome to Henrik Eikelands epic php app</h1>
            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="courses.php">Check out courses</a>
            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="data.php">UPLOAD DATA</a>
            <h3>Unique students: <?php echo count($students); ?></h3>
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
                    <th>Student number</th>
                    <th class="mdl-data-table__cell--non-numeric">Name</th>
                    <th class="mdl-data-table__cell--non-numeric">Surname</th>
                    <th>Birthdate</th>
                    <th>Courses completed</th>
                    <th>Courses failed</th>
                    <th>GPA</th>
                    <th class="mdl-data-table__cell--non-numeric">Status</th>
                </thead>
                <tbody>
                    <?php      
                        //Loop that maps each student object's property to a table cell                  
                        foreach($students as $student){
                            echo "
                            <tr>
                                <td>".$student->getStudentNr()."</td>
                                <td class='mdl-data-table__cell--non-numeric'>".$student->getFirstName()."</td>
                                <td class='mdl-data-table__cell--non-numeric'>".$student->getSurName()."</td>
                                <td>".convertUnixToBirthdate($student->getBirthDate())."</td>
                                <td>".$student->getCoursesCompleted()."</td>
                                <td>".$student->getCoursesFailed()."</td>
                                <td>".$student->getGPA()."</td>
                                <td class='mdl-data-table__cell--non-numeric'>".$student->getStatus()."</td>
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
    //Function that creates the final student objects, using the createStudent function
    function createStudents($studentData){
        $result = array();

        foreach ($studentData as $row) {
            array_push($result, createStudent($row));           
        }
      
        //sort students by gpa descending
        usort($result, function($a, $b) {
            return $b->getGPA() <=> $a->getGPA();
        });
        return $result;
    }

    
    function createStudent($row){
        //Get data from student in course csv file, and convert it to an array
        $studInCoursePath = "../CsvData/studentsInCourse.csv";
        $fp = fopen($studInCoursePath, "r")  or die("Unable to open file!");
        $studentInCourseDataRows = array_map("str_getcsv", file($studInCoursePath));
        fclose($fp);
        
        //Get data from the courses csv file, and convert it to an array
        $coursesCSV = "../CsvData/courses.csv";
        $fpCourse = fopen($coursesCSV, "r")  or die("Unable to open file!");
        $coursesDataRows = array_map("str_getcsv", file($coursesCSV));
        fclose($fpCourse);

        $student = new Student($row[0], $row[1], $row[2], $row[3]);
        $courseGradeMap = array();
        //Loops through all rows in studentsInCourse csv file
        foreach($studentInCourseDataRows as $dataRow) {
            $studInCourse = new studentInCourse($dataRow[0], $dataRow[1], $dataRow[2]);
            //If the studentsInCourse row is the current students row we add it to our courseGradeMap thing
            if($student->getStudentNr() == $studInCourse->getStudentNr()){
                foreach($coursesDataRows as $courseRow){
                    $course = new Course($courseRow[0],$courseRow[1],$courseRow[2],$courseRow[3],$courseRow[4]);
                    if($studInCourse->getCourseCode() == $course->getCourseCode()){
                        array_push($courseGradeMap,[
                            $studInCourse->getGrade(), 
                            $course->getNrOfCredits(), 
                            $studInCourse->getCourseCode(), 
                            $course->getInstructorName(), 
                            $course->getCourseYear(), 
                            $course->getCourseSemester()]);    
                        }
                    }
                }
            }
            
            //makes sure every entry in the array is unique
            $courseGradeMap = array_map("unserialize", array_unique(array_map("serialize", $courseGradeMap)));
            
            //Set rest of values for the student ojects
            $student->setGPA(calculateGPA($courseGradeMap));
            $student->setCoursesCompleted(findNrOfPassed($courseGradeMap));
            $student->setCoursesFailed(findNrOfFailed($courseGradeMap));
            return $student;
        }
        
    //Function that calculates a student's GPA
    function calculateGPA($courseGradeMap){
        $sum = 0;
        $creditsTaken = 0;
        foreach($courseGradeMap as $courseGrade){
            //make variables for readability
            $gradeLetter = $courseGrade[0];
            $credits = $courseGrade[1];
            //convert letter to int
            $grade = getGrade($gradeLetter);

            //add calculations to function variables
            $sum += $grade * $credits;
            $creditsTaken += $credits;
        }
        //calculates gpa
        return number_format($sum / $creditsTaken, 2);
    }

        ?>