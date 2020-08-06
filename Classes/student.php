<?php
//This file is used for creating the Student class and setting its properties
 class Student{
    //set the class properties
    public $studentNr;
    public $firstName;
    public $surName;
    public $birthdate;
    public $coursesCompleted;
    public $coursesFailed;
    public $GPA;
    public $status;

    //Make the constructor
    public function __construct($studentNr, $firstName, $surName,
    $birthdate) {
        $this->studentNr = $studentNr;
        $this->firstName = $firstName;
        $this->surName = $surName;
        $this->birthdate = $birthdate;
    }

    //"Getters" for Student Class, see course class for explanation
    function getStudentNr(){
        return $this->studentNr;
    }

    function getFirstName(){
        return $this->firstName;
    }

    function getSurName() {
        return $this->surName;
    }

    function getBirthDate() {
        return $this->birthdate;
    }

    function getCoursesCompleted() {
        return $this->coursesCompleted;
    }

    function getCoursesFailed() {
        return $this->coursesFailed;
    }

    function getGPA () {
        return $this->GPA;
    }
    //"Setters" for Student Class, see course class for explanation
    function setGpa($GPA) { 
        $this->GPA = $GPA;  
    }

    function setCoursesCompleted($cc) { 
        $this->coursesCompleted = $cc;  
    }

    function setCoursesFailed($cf) { 
        $this->coursesFailed = $cf;  
    }
    
    //This getter determines a student's status, based off of its GPA
    function getStatus(){
        $GPA = $this->GPA;
        if($GPA >= 0 && $GPA <= 1.99) {
                return "Unatisfactory";
        } elseif ($GPA >= 2 && $GPA <= 2.99) {
                return "Satisfactory";
        } elseif ($GPA >= 3 && $GPA <= 3.99) {
                return "Honour";
        } elseif ($GPA >= 4 && $GPA <= 5) {    
                return "High honour";
        } else {
            return "";
        }
    }
}

?>