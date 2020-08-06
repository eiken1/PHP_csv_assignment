<?php
    //This file is for creating the Student in Course class and setting its properties
    class studentInCourse {
        //Set class properties
        public $studentNr;
        public $courseCode;
        public $grade;

        //Make the constructor function
        public function __construct($studentNr, $courseCode, $grade) {
            $this->studentNr = $studentNr;
            $this->courseCode = $courseCode;
            $this->grade = $grade;
        }

        //Getters for student in course class, see course class for explanation
        function getStudentNr() {
            return $this->studentNr;
        }

        function getCourseCode() {
            return $this->courseCode;
        }

        function getGrade() {
            return $this->grade;
        }
    }
?>