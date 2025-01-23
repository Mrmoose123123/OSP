<?php

function generationNextSevenDays(){

    $dates = [];
    $currentDate = new DateTime();
    for ($i = 0; $i < 7; $i++) {
        $date = clone $currentDate;
        $date->modify("+$i day");
        $dates[] = [
            'date' => $date->format('Y-m-d'),
            'dayOfWeek' => $date->format('1')
        ];
    }
    return $dates;   
}






?>
