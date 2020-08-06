<?php
//This file creates the Course Class and sets its methods
 class Course{
    //set the class properties
    public $courseCode;
    public $courseName;
    public $courseYear;
    public $courseSemester;
    public $instructorName;
    public $nrOfCredits;
    public $nrOfStudRegistered;
    public $nrOfStudPassed;
    public $nrOfStudFailed;
    public $avgGrade;

    //Make the constructor
    function __construct($courseCode, $courseYear, $courseSemester,
    $instructorName, $nrOfCredits) {
        $this->courseCode = $courseCode;
        $this->courseYear = $courseYear;
        $this->courseSemester = $courseSemester;
        $this->instructorName = $instructorName;
        $this->nrOfCredits = $nrOfCredits;
    }

    //Below are "getters", functions that simply
    //returns a value for the current object
    function getCourseCode() {
        return $this->courseCode;
    }

    function getCourseYear() {
        return $this->courseYear;
    }

    function getCourseSemester() {
        return $this->courseSemester;
    }

    function getInstructorName() {
        return $this->instructorName;
    }

    function getNrOfCredits() {
        return $this->nrOfCredits;
    }

    function getNrOfStudRegistered() {
        return $this->nrOfStudRegistered;
    }

    function getNrOfStudPassed() {
        return $this->nrOfStudPassed;
    }

    function getNrOfStudFailed() {
        return $this->nrOfStudFailed;
    }

    function getAvgGrade() {
        return $this->avgGrade;
    }

    //Below are "setters", functions that set the property of an object
    function setNrOfStudRegistered ($nrOfStudReg) {
        $this->nrOfStudRegistered = $nrOfStudReg;
    }

    function setNrOfStudPassed ($nrOfStudPass) {
        $this->nrOfStudPassed = $nrOfStudPass;
    }

    function setNrOfStudFailed ($nrOfStudFail) {
        $this->nrOfStudFailed = $nrOfStudFail;
    }

    function setAvgGrade ($ag) {
        $this->avgGrade = $ag;
    }

    //This "getter" decides the object's course name, based on its course code
    function getCourseName() {
        $cmCode = $this->courseCode;
        if ($cmCode == "IMT2641") {
            return "Anvendt Datasikkerhet";
        } elseif ($cmCode == "SMF2293F") {
            return "Driftsregnskap med budsjettering";
        } elseif ($cmCode == "IMT2671") {
            return "Webprosjekt 2";
        } elseif ($cmCode == "IMT1101") {
            return "Typografi 1";
        } elseif ($cmCode == "IMT1292") {
            return "Webkoding";
        } elseif ($cmCode == "IMT3009") {
            return "Informasjonsarkitektur";
        } elseif ($cmCode == "TPD4167") {
            return "Informasjonsvisualisering";
        } else {
            return "Unknown course";
        }
    }
}
        
