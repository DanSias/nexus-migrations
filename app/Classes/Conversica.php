<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Conversica as ConversicaModel;

use App\Classes\Time;
use App\Classes\Filter;
use App\Classes\Program\Details;

use App\Program;
use App\Metrics;
use App\Actuals2016;
use App\Actuals2017;
use App\Actuals2018;
use App\Actuals2019;

// Cultivation Marketing Initiative:
class Conversica
{
    public $filter;
    public $programs;
    public $attributes;
    public $group;
    public $keys;

    public $metrics = [
        'leads',
        'hot',
        'engaged',
        'contact',
        'contact15',
        'quality',
        'quality30',
        'app',
        'starts'
    ];

    public $pipeline = [
        'leads',
        'contact',
        'contact15',
        'quality',
        'quality30',
        'app',
        'starts'
    ];

    public $properties = [
        'school',
        'program',
        'year',
        'month'
    ];

    public function __construct($filter = [])
    {
        if ($filter != []) {
            $this->filter = $filter;
            $this->group = strtolower($filter->group);
            $this->programs = $filter->programsList();
        }
        $this->allPrograms();
    }

    public function allPrograms()
    {
        $codes = DB::table('conversica')
            ->distinct()
            ->where('program', '!=', 'Others')
            ->get(['program']);
        $array = [];
        foreach ($codes as $c) {
            array_push($array, str_replace('_', '-', $c->program));
        }
        $programs = Program::all();
        $this->attributes = $programs;
        return json_encode($programs);
    }

    // To Merge with Metrics
    public function query()
    {
        $query = ConversicaModel::query();
        $query->selectRaw($this->queryString());

        $query = $query->groupBy('program');
        $query = $query->groupBy('year');
        $query = $query->groupBy('month');


        return $query;
    }

    // Build string to query 
    public function queryString()
    {
        $selectString = implode(', ', $this->properties);
        $selectString .= ', ';

        foreach ($this->metrics as $m) {
            $selectString .= 'sum(' . $m . ') as ' . $m . ', ';
        }
        $selectString = rtrim($selectString, ', ');

        return $selectString;
    }

    public function groupLookup()
    {
        $array = $this->filter->keys();
        $this->keys = $array;
    }

    public function addKeys($query)
    {
        $this->groupLookup();
        $group = $this->group;
        $keys = $this->keys;

        foreach ($query as $q) {
            $code = $q->program;
            $index = (isset($this->keys[$code])) ? $this->keys[$code] : 'Other';
            $query->$group = $index;
            if ($group == 'channel') {
                $q->key = 'Cultivation';
            } elseif ($group == 'initiative') {
                $q->key = 'Conversica';
            } else {
                $q->key = $keys[$code];
            }
        }

        return $query;
    }

    public function getDate()
    {
        $time = new Time;
        $date = $time->latestConversicaDate();
        return $date;
    }


    public function createElement($key, $type = '')
    {
        $time = new Time;
        $yearMonths = $time->yearMonths($time->getYear());

        $element = new \StdClass();
        $element->name = $key;

        if ($type == 'leads') {
            $props = ['leads'];
        } else {
            $props = $this->metrics;
            array_push($props, 'budget');
        }
        
        // Create monthly array for each property
        foreach ($props as $prop) {
            $element->$prop = [];
            foreach ($yearMonths as $mo) {
                if (substr($mo, 0, 4) != '2016') {
                    $element->$prop[$mo] = 0;
                }
            }
        }

        return $element;
    }

    public function createTargetElement($name)
    {
        $el = new \StdClass();
        $el->name = $name;
        $el->lead_budget = 0;
        $el->media_cut_lead_budget = 0;
        $el->contact15 = 0;
        $el->media_cut_contact15 = 0;
        $el->quality30 = 0;
        $el->media_cut_quality30 = 0;

        return $el;
    }

    public function createDeploymentElement($key)
    {
        $time = new Time;
        $yearMonths = $time->yearMonths($time->getYear());
        // $allMonths = $time->allMonths();

        $element = new \StdClass();
        $element->name = $key;

        $props = [
            'deploy',
            'deployTarget'
        ];

        // Create monthly array for each property
        foreach ($props as $prop) {
            $element->$prop = [];
            foreach ($yearMonths as $mo) {
                if (substr($mo, 0, 4) != '2016') {
                    $element->$prop[$mo] = 0;
                }
            }
        }

        return $element;
    }

    public function timeIndex($item)
    {
        $monthString = ($item->month < 10) ? '0' . $item->month : $item->month;
        $yearMonth = $item->year . $monthString;
        return $yearMonth;
    }

    // Query Table to return relevant rows
    public function actuals()
    {
        // Columns to pull from the DB
        $properties = $this->metrics;
        
        $query = $this->query();
        $query = $query->whereIn('program', $this->programs);
        $query = $query->get();
        
        $query = $this->addKeys($query);

        $returnArray = [];

        foreach ($query as $c) {
            $key = $c->key;

            if (!array_key_exists($key, $returnArray)) {
                $returnArray[$key] = $this->createElement($key);
            }

            $yearMonth = $this->timeIndex($c);

            // Add Monthly Values to all properties
            foreach ($properties as $prop) {
                if (substr($yearMonth, 0, 4) >= 2017) {
                    $returnArray[$key]->$prop[$yearMonth] += $c->$prop;
                }
            }
        }

        return $returnArray;
    }

    // Query Table to return lead rows
    public function leads()
    {
        // Columns to pull from the DB
        $properties = ['leads']; // $this->metrics;

        $query = $this->query();
        $query = $query->whereIn('program', $this->programs);
        $query = $query->get();

        $query = $this->addKeys($query);

        $returnArray = [];

        foreach ($query as $c) {
            $key = $c->key;

            if (!array_key_exists($key, $returnArray)) {
                $returnArray[$key] = $this->createElement($key, 'leads');
            }

            $yearMonth = $this->timeIndex($c);

            // Add Monthly Values to all properties
            foreach ($properties as $prop) {
                if (substr($yearMonth, 0, 4) >= 2017) {
                    $returnArray[$key]->$prop[$yearMonth] += $c->$prop;
                }
            }
        }

        return $returnArray;
    }

    // Monthly Contact15 / Quality30 Targets
    public function targets()
    {
        $group = $this->group;

        $targets = DB::table('conversica_targets')
            ->whereIn('program', $this->programs)
            ->get();

        $targets = $this->addKeys($targets);
        // dd($targets);
        $targetArray = [];
        foreach ($targets as $t) {
            $key = $t->key;

            if (!array_key_exists($key, $targetArray)) {
                $targetArray[$key] = $this->createTargetElement($key);
            }
            $targetArray[$key]->lead_budget += $t->lead_budget;
            $targetArray[$key]->media_cut_lead_budget += $t->media_cut_lead_budget;
            $targetArray[$key]->contact15 += $t->contact15;
            $targetArray[$key]->media_cut_contact15 += $t->media_cut_contact15;
            $targetArray[$key]->quality30 += $t->quality30;
            $targetArray[$key]->media_cut_quality30 += $t->media_cut_quality30;
        }
        
        return $targetArray;
    }

    // Deployments structured year month
    public function deployments()
    {
        // Columns to pull from the DB
        $properties = [
            'deploy',
            'deployTarget'
        ];

        // Actuals
        $deploy = DB::table('conversica_deployment_actuals');
        $deploy = $deploy->whereIn('program', $this->programs);

        $deploy = $deploy->groupBy('program');
        $deploy = $deploy->groupBy('year');
        $deploy = $deploy->groupBy('month');

        $deploy = $deploy->get();

        $returnArray = [];

        $deploy = $this->addKeys($deploy);

        foreach ($deploy as $d) {
            $key = $d->key;

            if (!array_key_exists($key, $returnArray)) {
                $returnArray[$key] = $this->createDeploymentElement($key);
            }

            $yearMonth = $this->timeIndex($d);

            // Add Monthly Values to all properties
            if (substr($yearMonth, 0, 4) >= 2017) {
                $returnArray[$key]->deploy[$yearMonth] += $d->deployments;
            }
        }

        // Targets
        $targets = DB::table('conversica_deployment_targets');
        $targets = $targets->whereIn('program', $this->programs);

        $targets = $targets->groupBy('program');
        $targets = $targets->groupBy('year');
        $targets = $targets->groupBy('month');

        $targets = $targets->get();

        $targets = $this->addKeys($targets);

        foreach ($targets as $t) {
            $key = $t->key;

            if (!array_key_exists($key, $returnArray)) {
                $returnArray[$key] = $this->createDeploymentElement($key);
            }

            $yearMonth = $this->timeIndex($t);

            // Add Monthly Values to all properties
            if (substr($yearMonth, 0, 4) >= 2017) {
                $returnArray[$key]->deployTarget[$yearMonth] += $t->deployments;
            }
        }

        return $returnArray;
    }

    // Select options for Conversica data
    public function selects()
    {
        $distinct = ConversicaModel::distinct()
            ->orderBy('program')
            ->get(['school', 'program', 'year']);
        
        $details = new Details;
        $array = $details->selectData();
        
        $array['year'] = [];
        $array['school'] = [];
        $array['program'] = [];

        foreach ($distinct as $d) {
            if (!in_array($d->program, $array['program'])) {
                array_push($array['program'], $d->program);
            }
            if (!in_array($d->school, $array['school'])) {
                array_push($array['school'], $d->school);
            }
            if (!in_array($d->year, $array['year'])) {
                array_push($array['year'], $d->year);
            }
        }

        $programDetails = $details->arrayDetails($array['program']);

        $array['bu'] = [];
        foreach ($programDetails as $d) {
            if (!in_array($d->business_unit, $array['bu'])) {
                array_push($array['bu'], $d->business_unit);
            }
        }
        sort($array['bu']);

        $time = new Time;
        $array['month'] = $time->allMonths();

        return $array;
    }

    public function merge()
    {
        $this->clearConversica();

        $rows = $this->mergeRows();

        foreach ($rows as $row) {
            if ($row['metric'] == 'leads') {
                $row['metric'] = 'Leads';
                if ($row['year'] == 2018) {
                    Actuals2018::insert($row);
                } elseif ($row['year'] == 2017) {
                    Actuals2017::insert($row);
                } elseif ($row['year'] == 2016) {
                    Actuals2016::insert($row);
                }
            } elseif ($row['metric'] == 'starts') {
                $row['metric'] = 'StartsLeadmonth';
                Metrics::insert($row);
            } elseif ($row['metric'] == 'app') {
                $row['metric'] = 'AIP';
                Metrics::insert($row);
            } else {
                Metrics::insert($row);
            }
        }
        return $rows;
    }

    // Clear conversica data from all tables
    public function clearConversica()
    {
        Metrics::where('channel', 'Cultivation')
            ->where('initiative', 'CONVERSICA')
            ->delete();
        Actuals2018::where('metric', 'Leads')
            ->where('channel', 'Cultivation')
            ->where('initiative', 'CONVERSICA')
            ->delete();
        Actuals2017::where('metric', 'Leads')
            ->where('channel', 'Cultivation')
            ->where('initiative', 'CONVERSICA')
            ->delete();
        Actuals2016::where('metric', 'Leads')
            ->where('channel', 'Cultivation')
            ->where('initiative', 'CONVERSICA')
            ->delete();
    }

    public function mergeRows()
    {
        $data = $this->query()->get();

        $details = new Details;
        $blank = $details->metricTableArray();

        $time = new Time;
        $months = $time->allMonths;

        $array = [];
        $metricsData = [];

        $metrics = $this->pipeline;

        foreach ($data as $d) {
            $year = $d->year;
            if ($year == 0) {
                continue;
            }
            $monthNumber = $d->month;
            $monthIndex = $monthNumber - 1;
            $month = $months[$monthIndex];

            $program = $d->program;
            if ($program == 'ASU-ASU') {
                $program = 'ASU-BRAND';
            }
            $channel = 'Cultivation';
            $initiative = 'Conversica';

            $obj = new \StdClass();
            $obj->year = $year;
            $obj->month = $month;
            $obj->program = $program;
            $obj->channel = $channel;
            $obj->initiative = $initiative;
            // $obj->metrics = $metrics;

            // $attributes = $this->attributes->where('code', $program);
            // $attributes = $attributes->first();

            if (!array_key_exists($year, $array)) {
                $array[$year] = [];
            }
            foreach ($metrics as $metric) {
                if (!array_key_exists($metric, $array[$year])) {
                    $array[$year][$metric] = [];
                }

                if (!array_key_exists($program, $array[$year][$metric])) {
                    $array[$year][$metric][$program] = [];
                }

                // Channel
                if (!array_key_exists($channel, $array[$year][$metric][$program])) {
                    $array[$year][$metric][$program][$channel] = [];
                }

                // Initiative
                if (!array_key_exists($initiative, $array[$year][$metric][$program][$channel])) {
                    $array[$year][$metric][$program][$channel][$initiative] = [];

                    $array[$year][$metric][$program][$channel][$initiative]['metric'] = $metric;

                    // $array[$year][$metric][$program][$channel][$initiative]['location'] = $attributes->location;
                    // $array[$year][$metric][$program][$channel][$initiative]['business_unit'] = $attributes->bu;
                    // $array[$year][$metric][$program][$channel][$initiative]['school'] = $attributes->partner;
                    $array[$year][$metric][$program][$channel][$initiative]['program'] = $program;
                    $array[$year][$metric][$program][$channel][$initiative]['channel'] = $channel;
                    $array[$year][$metric][$program][$channel][$initiative]['initiative'] = $initiative;
                    $array[$year][$metric][$program][$channel][$initiative]['year'] = $year;

                    foreach ($months as $mo) {
                        $array[$year][$metric][$program][$channel][$initiative][$mo] = 0;
                    }
                }
                $array[$year][$metric][$program][$channel][$initiative][$month] += $d->$metric;
            }
        }

        $rows = [];
        foreach ($array as $year => $value) {
            foreach ($value as $metric => $pro) {
                foreach ($pro as $program => $ch) {
                    foreach ($ch as $channel => $init) {
                        foreach ($init as $initiative => $row) {
                            array_push($rows, $row);
                        }
                    }
                }
            }
        }
        return $rows;
    }
}
