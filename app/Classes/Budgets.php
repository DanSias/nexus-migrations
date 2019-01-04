<?php

namespace App\Classes;

use App\Classes\Filter;

use App\Program;
use App\Setting;
use App\BudgetScenario;

// Monthly Budgets
class Budgets
{
    public $filter;
    public $programs;

    public $year;
    public $scenario;
    public $status;
    public $description;

    public $scenarios = [
        'version2',
        'version1',
        'initial'
    ];

    // public $channel;
    // public $initiative;

    public function __construct($request)
    {
        $this->settings();
        $filter = new Filter($request);
        $this->filter = $filter;

    }
    public function settings()
    {
        $settings = Setting::where('type', 'budget')->get();
        foreach ($settings as $setting) {
            $key = $setting->key;
            $value = $setting->value;
            $this->$key = $value;
        }
    }

    public function programScenario()
    {
        $channel = $this->filter->channel;
        $programs = $this->filter->programsList();

        $loop = $this->scenarios;
        $budgets = [];


        foreach ($loop as $item) {
            $check = BudgetScenario::whereIn('program', $programs)
                ->where('year', $this->year)
                ->where('scenario', $item);
            
            if (count($channel) > 0) {
                $check = $check->whereIn('channel', $channel);
            }

            $check = $check->get();

            
            $budgets[$item] = $check;
        }

        return $budgets;
    }
}
