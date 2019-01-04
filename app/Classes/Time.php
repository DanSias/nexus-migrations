<?php

namespace App\Classes;

class Time
{
    public $allMonths = [
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december'
    ];

    public $monthDigits = [
        '01', 
        '02', 
        '03', 
        '04', 
        '05', 
        '06', 
        '07', 
        '08', 
        '09', 
        '10', 
        '11', 
        '12'
    ];

    // Current Calendar Year
    public function getYear()
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        return (int) $year;
    }
}
