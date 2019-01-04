<?php

namespace App\Classes;

use DB;
use App\Classes\Time;
use App\Classes\Filter;

use App\Revenue as RevenueModel;
use App\Program;
use App\ProgramMap;

// Performance Metrics
class Revenue
{
    public $filter;
    public $query;
    public $group;
    // Laurus Codes
    public $codes;
    // Based on Group
    public $keys;
    // Laurus -> neXus
    public $map;
    public $metrics = [
        'starts',
        'students',
        'revenue',
        'plan_starts',
        'plan_students',
        'plan_revenue'
    ];

    public function __construct($request)
    {
        $filter = new Filter($request);
        $this->filter = $filter;
        $this->group = strtolower($filter->group);
        $this->allRevenueCodes();
        $this->getMap();
        // $this->modelPrograms();
    }

    // Query a DB table
    public function query()
    {
        $this->selectTable()
            ->getResults();

        $this->addMap();

        return $this->query;
    }

    // Table, select years and range
    public function selectTable()
    {
        $thisYear = (int) $this->filter->termYear;
        $firstYear = $thisYear - 2;

        $query  = RevenueModel::query()
            ->where('year', '>=', $firstYear);
        $this->query = $query;
        return $this;
    }

    public function getResults()
    {
        $columns = [
            'program_rolled',
            'year',
            'term',
            'starts',
            'plan_starts',
            'students',
            'plan_students',
            'revenue',
            'plan_revenue'
        ];

        $query = $this->query;
        $query = $query->get($columns);
        $this->query = $query;

        return $this;
    }

    public function modelPrograms()
    {
        $programs = RevenueModel::distinct(['program_rolled'])->get(['program_rolled']);

        $codes = [];
        foreach ($programs as $p) {
            $code = $p->program_rolled;
            array_push($codes, $code);
        }

        $group = $this->group;
        if ($this->group != 'program') {
            $keys = $this->modelProgramKeys($codes);
        }

        $this->keys = $keys;
        return $this;
    }

    public function modelProgramKeys()
    {
        $codes = ProgramMap::whereIn('laurus', $this->codes)->distinct('code')->pluck('code')->toArray();

        $programs = Program::whereIn('code', $codes);
        $programs = $programs->get(['code', $this->group]);

        $this->map = $programs;

        return $this;
    }

    public function getMap()
    {
        $map = ProgramMap::get();
        // $this->map = $map;
        $array = [];
        foreach ($map as $m) {
            $array[$m->laurus] = $m->code;
        }
        $this->map = $array;
    }

    public function allCodesMap()
    {

        foreach ($query as $q) {
            $pro = $q->program_rolled;
            
        }
        $this->query = $query;
    }

    public function allRevenueCodes()
    {
        $codes = RevenueModel::query()->distinct('program_rolled')->pluck('program_rolled')->toArray();
        $this->codes = $codes;
    }

    public function allKeys()
    {
        $codes = $this->codes;
    }

    public function getCode($laurus)
    {
        if (isset($this->map[$laurus])) {
            return $this->map[$laurus];
        } else {
            return 'Other';
        }
    }

    public function getKey($laurus)
    {
        $code = $this->getCode($laurus);

    }

    public function semesterTerms($semester)
    {
        switch ($semester) {
            case 'Spring':
                return ['A1', 'A2', 'A3'];
                break;
            case 'Summer':
                return ['B1', 'B2', 'B3'];
                break;
            case 'Fall':
                return ['C1', 'C2', 'C3'];
                break;
            default:
                return [];
                break;
        }
    }
    

    public function programSemester($request)
    {
        $filter = new Filter($request);

        $columns = ['program', 'year', 'term', 'starts', 'plan_starts', 'students', 'plan_students', 'revenue', 'plan_revenue'];
        $metrics = ['starts', 'plan_starts', 'students', 'plan_students', 'revenue', 'plan_revenue'];

        $yr = $filter->termYear;
        $sem = $filter->semester;
        $terms = $this->semesterTerms($sem);

        $list = $filter->programsList();
        $rev = RevenueModel::whereIn('program', $list)
            ->where('year', $yr)
            ->whereIn('term', $terms)
            ->get($columns);

        $returnArray = [];
        foreach ($rev as $r) {
            $program = $r->program;
            $term = $r->term;

            $sum = 0;
            foreach ($metrics as $m) {
                $sum += $r->$m;
            }
            if ($sum > 0) {
                // Array of programs
                if (!array_key_exists($program, $returnArray)) {
                    $returnArray[$program] = new \StdClass();
                }
                // Object of each term with semester total

                if (! property_exists($returnArray[$program], $sem)) {
                    $returnArray[$program]->$sem = new \StdClass();
                    $returnArray[$program]->$sem->starts = 0;
                    $returnArray[$program]->$sem->plan_starts = 0;
                    $returnArray[$program]->$sem->students = 0;
                    $returnArray[$program]->$sem->plan_students = 0;
                    $returnArray[$program]->$sem->revenue = 0;
                    $returnArray[$program]->$sem->plan_revenue = 0;
                }

                if (! property_exists($returnArray[$program], 'term')) {
                    $returnArray[$program]->$term = new \StdClass();
                    $returnArray[$program]->$term->starts = 0;
                    $returnArray[$program]->$term->plan_starts = 0;
                    $returnArray[$program]->$term->students = 0;
                    $returnArray[$program]->$term->plan_students = 0;
                    $returnArray[$program]->$term->revenue = 0;
                    $returnArray[$program]->$term->plan_revenue = 0;
                }
                // Add to appropriate semester
                $returnArray[$program]->$sem->starts += $r->starts;
                $returnArray[$program]->$sem->students += $r->students;
                $returnArray[$program]->$sem->revenue += $r->revenue;
                $returnArray[$program]->$sem->plan_starts += $r->plan_starts;
                $returnArray[$program]->$sem->plan_students += $r->plan_students;
                $returnArray[$program]->$sem->plan_revenue += $r->plan_revenue;

                // Add to appropriate term
                $returnArray[$program]->$term->starts += $r->starts;
                $returnArray[$program]->$term->students += $r->students;
                $returnArray[$program]->$term->revenue += $r->revenue;
                $returnArray[$program]->$term->plan_starts += $r->plan_starts;
                $returnArray[$program]->$term->plan_students += $r->plan_students;
                $returnArray[$program]->$term->plan_revenue += $r->plan_revenue;
            }
        }

        return $returnArray;
    }
}
