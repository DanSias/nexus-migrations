<?php

namespace App\Classes;

use DB;
use App\Classes\Time;
use App\Classes\Filter;

use App\Program;

// Performance Metrics
class Metrics
{
    public $group;
    public $filter;
    public $programs;
    public $channel;
    public $initiative;
    public $table;
    public $type;
    public $query;
    public $array = [];
    public $index;
    public $metric;
    public $year;
    public $monthDigits;
    public $keys;

    // Attributes in programs table
    public $attributes = [
        'partner',
        'location',
        'bu',
        'vertical',
        'subvertical',
        'strategy',
        'type',
        'level',
        'strategy'
    ];

    public $pipelineMetrics = [
        'contact',
        'contact15',
        'quality',
        'quality30',
        'app',
        'insales',
        'aip',
        'app30',
        'comfile',
        'comfile60',
        'acc',
        'acc90',
        'accconf',
        'accconf120',
        'start',
        'startsleadmonth'
    ];

    public function __construct($request)
    {
        $filter = new Filter($request);
        $this->filter = $filter;

        $this->setPrograms($filter->programsList());
        $this->setGroup(strtolower($filter->group));
        $this->setChannels($filter->channel);

        $time = new Time;
        $year = $time->getYear();
        $this->setYear($year);
        $this->setTable('monthly_actuals_' . $year);
        $this->monthDigits = $time->monthDigits;
        $this->groupLookup();
    }
    public function setPrograms($array)
    {
        $this->programs = $array;
    }
    public function setChannels($array)
    {
        $this->channel = $array;
    }
    public function setGroup($group)
    {
        $this->group = $group;
    }
    public function setArray($array)
    {
        $this->array = $array;
    }
    public function setIndex($index)
    {        
        $this->index = $index;
    }
    public function setMetric($metric)
    {
        $this->metric = $metric;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
    public function setTable($table)
    {
        $this->table = $table;
    }
    public function setQuery($query)
    {
        $this->query = $query;
    }
    public function setYear($year)
    {
        $this->year = $year;
    }

    // Current year and previous 2
    public function years()
    {
        $time = new Time;
        $year = $time->getYear();
        $years = [$year - 2, $year - 1, $year];
        return $years;
    }

    // Tables for a given metric type
    // actuals, budget, forecast
    public function tables($type = 'actuals')
    {
        $years = $this->years();
        $array = [];

        foreach ($years as $year) {
            $table = 'monthly_' . $type . '_' . $year;
            array_push($array, $table);
        }
        return $array;
    }

    // Latest 3 years for a given type
    // actuals, budget, forecast, pipeline
    public function threeYears()
    {
        $time_start = microtime(true);

        $type = $this->type;
        $years = $this->years();

        // Pipeline table has all years, no need to loop
        if ($type == 'pipeline') {
            $query = $this->query();
            $time_query = microtime(true);
            foreach ($query as $q) {
                $this->addQueryRowToArray($q);
            }
        } else {
            foreach ($years as $year) {
                $this->setYear($year);
                $query = $this->query();
                $time_query[$year] = microtime(true);
                foreach ($query as $q) {
                    $this->addQueryRowToArray($q);
                }
            }
        } 
        $time_complete = microtime(true);

        $object = new \StdClass();
        $object->$type = $this->array;
        $object->filter = $this->filter;
        $object->time = new \StdClass();
        $object->time->complete = $time_complete - $time_start;
        if (is_array($time_query)) {
            foreach ($time_query as $key => $value) {
                $object->time->query[$key] = $value - $time_start;
            }
        } else {
            $object->time->query = $time_query - $time_start;
        }
        return $object;
    }

    public function actualsBudgetForecast()
    {
        $returnData = new \StdClass();

        $this->setType('actuals');
        $returnData->actuals = $this->threeYears()->actuals;

        $this->setType('budget');
        $returnData->budget = $this->threeYears()->budget;

        $this->setType('forecast');
        $returnData->forecast = $this->threeYears()->forecast;

        $this->setType('pipeline');
        // $returnData->pipeline = $this->threeYears()->pipeline;
        $pipeline = $this->threeYears()->pipeline;

        $array = [];

        // Add pipeline data to actuals
        foreach ($pipeline as $index => $data) {
            foreach ($data as $metric => $value) {
                if (in_array($metric, $this->pipelineMetrics)) {
                    array_push($array, $metric);
                    if (isset($returnData->actuals[$index])) {
                        $returnData->actuals[$index]->$metric = $value;
                    } else {
                        
                    }
                }
            }
        }

        return $returnData;
    }
    
    public function addQueryRowToArray($q)
    {
        $metric = strtolower($q->metric);
        if ($metric == 'ï»¿quality') {
            $metric = 'quality';
        }
        $this->metric = $metric;
        $index = $this->checkIndex($q);
        $this->checkArray();

        $year = (isset($q->year)) ? $q->year : $this->year;

        foreach ($this->monthDigits as $digits) {
            $yearMonth = $year . $digits;
            $this->array[$index]->$metric->$yearMonth += $q->$digits;
        }
    }

    // Organize the data of the query
    public function data()
    {
        $query = $this->query();
        $group = $this->group;
        $monthDigits = $this->monthDigits;

        $array = [];
        
        foreach ($query as $q) {

            $metric = $q->metric;

            $year = (isset($q->year)) ? $q->year : $this->year;
            
            // Channel Grouping also includes has initiative
            if ($group == 'channel') {
                $channel = $q->channel;
                $initiative = $q->initiative;
                
                if (strpos($initiative, 'SEO2') !== false) {
                    $channel = 'SEO2';
                }

                $index = $channel; 
                $array = $this->checkArrayForGroup($array, $index);
                $array = $this->checkGroupForMetric($array, $index, $metric);
                
                // 12 Months
                foreach ($monthDigits as $digits) {
                    $yearMonth = (string)$year . $digits;
                    $value = $q->$digits;

                    $array[$index]->$metric->$yearMonth += $value;
                }
            } else {
                $index = (isset($this->keys[$q->program])) ? $this->keys[$q->program] : 'Other';

                $array = $this->checkArrayForGroup($array, $index);
                $array = $this->checkGroupForMetric($array, $index, $metric);
                foreach ($monthDigits as $digits) {
                    $yearMonth = $year . $digits;

                    $array[$index]->$metric->$yearMonth += $q->$digits;
                }
            }
        }
        $object = new \StdClass();
        $object->data = $array;
        $object->filter = $this->filter;
        // return $query;
        return $object;
    }

    public function checkArray()
    {
        $array = $this->array;

        $index = $this->index;
        if (!array_key_exists($index, $array)) {
            $array[(string)$index] = new \StdClass();
        }

        $metric = $this->metric;
        // Metric with yearmonth for latest 3 years
        if (!isset($array[$index]->$metric) && ! property_exists($array[$index], $metric)) {
            $array[$index]->$metric = $this->emptyLongObject();
        }
        $this->setArray($array);
        // return $this;
    }

    // Return the index for each query item
    public function checkIndex($q)
    {
        $group = $this->group;
        // $index = (!isset($q->$group) || $q->$group == '') ? 'Other' : $q->$group;
        $index = (isset($this->keys[$q->program])) ? $this->keys[$q->program] : 'Other';

        // Channels have different index
        if ($group == 'channel') {
            $channel = ($q->channel == 'OutReach') ? 'Outreach' : $q->channel;
            $channel = ($channel == 'Unknown') ? 'Other' : $channel;
            if ($this->hasInitiative()) {
                $initiative = $q->initiative;
            } else {
                $initiative = '';
            }
                    // Split SEO2 if in initiative
            if (strpos($initiative, 'SEO2') !== false) {
                $channel = 'SEO2';
            }
            $index = $channel;
        } elseif ($group == 'initiative') {
            if ($this->hasInitiative()) {
                $index = $q->initiative;
            } else {
                $index = 'Other';
            }
        }

        $this->setIndex($index);
        return $index;
    }

    public function checkArrayForGroup($array, $index)
    {
        if (!array_key_exists($index, $array)) {
            $array[$index] = new \StdClass();
        }
        return $array;
    }

    public function checkArrayForYear($array)
    {
        $year = $this->year;
        if (!isset($array[$year])) {
            $array[$year] = new \StdClass();
        }
        return $array;
    }
    public function checkYearArrayForGroup($array, $group)
    {
        $year = $this->year;
        if (!isset($array[$year]->$group)) {
            $array[$year]->$group = new \StdClass();
        }
        return $array;
    }

    public function checkGroupForMetric($array, $group, $metric)
    {
        $year = $this->year;
        $metric = strtolower($metric);

        if (!isset($array[$year]->$group->$metric)) {
            $array[$year]->$group->$metric = $this->emptyLongObject();
        }
        return $array;
    }
    public function checkMetric()
    {
        
    }

    // Query a table
    public function query()
    {
        $this->selectTable()
            ->selectString()
            ->selectMetrics()
            ->selectPrograms()
            ->selectChannels()
            ->selectGroups()
            ->selectStarbucks()
            ->getResults();

        return $this->query;
    }

    public function selectTable()
    {
        $type = $this->type;
        $year = $this->year;

        switch ($type) {
            case 'actuals':
                $table = 'monthly_actuals_';
                $table .= $year;
                break;

            case 'budget':
                $table = 'monthly_budget_';
                $table .= $year;
                break;

            case 'forecast':
                $table = 'monthly_forecast_';
                $table .= $year;
                break;

            case 'metrics':
                $table = 'monthly_metrics';
                break;
            default:
                $table = 'monthly_metrics';
                break;
        }

        // Oritinal budget for 2018
        if ($type == 'budget' && $this->filter->budgetType == 'original') {
            $table = 'monthly_budget_' . $year . '_original';
        }

        $this->setTable($table);
        $query = DB::table($table);
        $this->setQuery($query);
        return $this;
    }

    // Query string based on the table
    public function queryString()
    {
        $table = $this->table;
        $group = $this->group;
        
        $string = $table . '.metric, ';
        // Year if table NOT budget
        if (strpos($table, 'budget') === false) {
            $string .= $table . '.year, ';
        }

        $string .= $table . '.program, ';
        
        // Channel & Initiative
        if ($this->channel || $this->group == 'channel') {
            $string .= $table . '.channel, ';
            if ($this->hasInitiative($table)) {
                $string .= $table . '.initiative, ';
            }

        } elseif ($this->initiative || $this->group == 'initiative') {
            $string .= $table . '.channel, ';
            if ($this->hasInitiative($table)) {
                $string .= $table . '.initiative, ';
            }
        } 

        $sumString = $this->monthSumDigits();

        if ($sumString == '') {
            $string = rtrim($string, ', ');
        } else {
            $string .= $sumString;
        }
        return $string;
    }

    public function selectString()
    {
        $queryString = $this->queryString();
        $query = $this->query->select(DB::raw($queryString));
        $this->setQuery($query);
        return $this;
    }

    // Exclude unnecessary metrics
    public function selectMetrics()
    {
        $ignore = ['CPL', 'CompFileDenied', 'convleads', 'convstarts', 'AppRec'];
        $query = $this->query->whereNotIn('metric', $ignore);
        $this->setQuery($query);
        return $this;
    }

    public function selectPrograms()
    {
        $program = $this->table . '.program';
        $query = $this->query->whereIn($program, $this->programs);
        $this->setQuery($query);
        return $this;
    }

    public function selectChannels()
    {
        $query = $this->query->where('channel', '!=', 'Test');
        $group = $this->group;
        $channel = $this->channel;
        $selected = $this->filter->selected;
        $exclude = $this->filter->excludeChannels;

        // No channel -> return
        if ($channel == '' || $channel == [] || $channel == null) {
            return $this;
        }
        if ($channel) {
            if (is_string($channel)) {
                $channel = [$channel];
            }

            if (! $this->hasInitiative()) {
                $query = $query->whereIn('channel', $channel);
            } elseif ($channel == ['SEO2'] || $selected == 'SEO2') {
                $query = $query->where('initiative', 'like', '%SEO2%');
            } elseif ($channel == ['SEO'] || $selected == 'SEO') {
                $query = $query->where('channel', 'SEO');
                $query = $query->where('initiative', 'NOT LIKE', '%SEO2%');
            } elseif (in_array('SEO2', $channel) && in_array('SEO', $channel)  && count($channel) == 2) {
                $query = $query->where('channel', 'SEO');
            } elseif (in_array('SEO', $channel) && count($channel) > 1) {
                $query = $query->whereIn('channel', $channel);
                $query = $query->where('initiative', 'NOT LIKE', '%SEO2%');
            } else {
                $query = $query->whereIn('channel', $channel);
            }
        } 

        if (count($exclude) > 0) {
            $query = $query->whereNotIn('channel', $exclude);
        }

        if (count($this->filter->initiative) > 0 && $this->hasInitiative()) {
            $query = $query->whereIn('initiative', $initiative);
        }

        $this->setQuery($query);
        return $this;
    }

    // SEO & SEO2

    public function selectGroups()
    {
        $table = $this->table;
        // Always group by Metric
        $query = $this->query->groupBy('metric');
        
        $group = $this->group;
        // If we are grouping by a known attributes, include
        if ($group == 'program' || $group == 'code') {
            $query = $query->groupBy($table . '.program');
        } elseif ($group == 'channel') {
            $query = $query->groupBy($table . '.channel');
            if ($this->hasInitiative($table)) {
                $query = $query->groupBy($table . '.initiative');
            }
        } elseif ($group == 'initiative' && $this->hasInitiative($table)) {
            $query = $query->groupBy($table . '.initiative');
        } elseif (in_array($group, $this->attributes)) {
            // $query = $query->leftJoin('programs', $table . '.program', '=', 'programs.code');
            // $query = $query->groupBy('programs.' . $group);
            $query = $query->groupBy('program');
        } 

        // Metrics table has a different structure
        // All years are in a single table
        if ($this->table == 'monthly_metrics') {
            $years = $this->years();
            $query = $query->whereIn('year', $years);
            $query = $query->groupBy('year');
        }

        $this->setQuery($query);
        return $this;
    }

    public function selectStarbucks()
    {
        $query = $this->query;
        $table = $this->table;
        if ($this->filter->starbucks == false) {
            if ($this->hasInitiative($table)) {
                $query = $query->where('initiative', 'not like', '%SBUX%');
            }
        }
        $this->setQuery($query);
        return $this;
    }

    public function getResults()
    {
        $query = $this->query;
        $query = $query->get();
        $this->setQuery($query);

        return $this;
    }


    public function monthSumDigits()
    {
        $time = new Time;        
        $months = $time->allMonths;
        $string = '';
        $i = 1;
        foreach ($months as $mo) {
            if ($i < 10) {
                $digits = '0' . (string)$i;
            } else {
                $digits = (string)$i;
            }
            $string .= 'SUM(' . $mo . ') as \'' . $digits . '\', ';
            $i++;
        }
        $string = rtrim($string, ', ');
        return $string;
    }

    public function emptyObject()
    {
        $year = (isset($this->year)) ? $this->year : date('Y');
        $digits = $this->monthDigits;
        $object = new \StdClass();
        foreach ($digits as $digit) {
            $yrmo = $year . $digit;
            $object->$yrmo = 0;
        }
        return $object;
    }
    public function emptyLongObject()
    {
        // $year = (isset($this->year)) ? $this->year : date('Y');
        $years = $this->years();
        $digits = $this->monthDigits;
        $object = new \StdClass();
        foreach ($years as $year) {
            foreach ($digits as $digit) {
                $yrmo = $year . $digit;
                $object->$yrmo = 0;
            }
        }
        return $object;
    }

    // Table with no Initiative Column
    public function hasInitiative($table = '')
    {
        if ($table == '') {
            $table = $this->table;
        }
        if ($table == 'monthly_budget_2016') {
            return false;
        } else {
            return true;
        }
    }

    public function groupLookup()
    {
        $array = $this->filter->keys();
        $this->keys = $array;
    }
}
