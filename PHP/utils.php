<?php
    //This file is for various function, that may have use across several files
    
    //Function that converts a gradeletter, into a numeric grade
    function getGrade($letter){
        if($letter == "A"){
            return 5;
        } elseif($letter == "B"){
            return 4;
        } elseif($letter == "C"){
            return 3;
        } elseif($letter == "D"){
            return 2;
        } elseif($letter == "E"){
            return 1;
        } else {
            return 0;
        }
    }

    //Find the nr of either passed students or courses passed
    function findNrOfPassed ($arrayMap) {
        $nrOfPassed = 0;
        foreach ($arrayMap as $array){
            $grade = $array[0];
            if ($grade != "F") {
                $nrOfPassed ++;
            }
        }
        return $nrOfPassed;
    }
    //Find the nr of either failed students or courses
    function findNrOfFailed ($arrayMap) {
        $nrOfFailed = 0;
        foreach ($arrayMap as $array){
            $grade = $array[0];
            if ($grade == "F") {
                $nrOfFailed ++;
            }
        }
        return $nrOfFailed;
    }

    //Functions that converts unix timestamps to readable dates
    function convertUnixToBirthdate($date) {
        return date("d-m-Y", $date);
    }

    function convertUnixToYear ($date){
        return date("Y", $date);
    }

?>