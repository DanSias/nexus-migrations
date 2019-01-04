<?php

namespace App\Classes;

use App\Program;

// neXus Filter:
class Filter
{
    // Define Filter
    public $selected;
    public $selectedGroup;
    public $group;
    public $excludeGroup;
    public $exclude;
    public $excludeChannels;
    public $location;
    public $bu;
    public $partner;
    public $program;
    public $level;
    public $type;
    public $vertical;
    public $subvertical;
    public $active;
    public $channel;
    public $initiative;
    public $query;
    public $sort;
    public $order;
    public $vintage;
    public $list;
    public $useMine;
    public $termYear;
    public $semester;
    public $term;
    public $budgetType;
    public $strategy;
    public $starbucks;

    /**
     * Build object from Laravel Request Object
     */
    public function __construct($request = [])
    {
        if ($request == []) {
            return $this;
        }

        $filter = $request->filter;
        $filter = json_decode($filter);

        if (is_array($filter) && count($filter) > 0) {
            foreach ($filter as $key => $value) {
                if ($value == 'School') {
                    $value = 'partner';
                }
                $this->$key = $value;
            }
        }
        return $this;
    }

    /**
     * Create Filter Class from Request Object
     * @param request object
     * @return object instance of Filter class (this)
     */

    public function programs()
    {
        $query = Program::query();

        $attributes = [
            'active',
            'location', 
            'bu', 
            'vertical', 
            'subvertical'
        ];

        foreach ($attributes as $attribute) {
            if ($this->$attribute) {
                $array = $this->checkArray($this->$attribute);
                $query = $query->whereIn($attribute, $array);
            }
        }

        if ($this->selected) {
            $subGroup = $this->selectedGroup;
            $selection = $this->selected;
            if ($subGroup == 'School') {
                $subGroup = 'partner';
            }
            if (strtolower($subGroup) == 'program') {
                $query = $query->where('code', $selection);
            } elseif ($subGroup != 'channel') {
                $query = $query->where($subGroup, $selection);
            }
        }

        $query = $query->orderBy('bu', 'asc')
            ->orderBy('code', 'asc');

        $programs = $query->get();

        return $programs;
    }

    // Array of Program Codes
    public function programsList()
    {
        $programs = $this->programs();
        $list = [];
        foreach ($programs as $program) {
            if (!in_array($program->code, $list)) {
                array_push($list, $program->code);
            }
        }
        return $list;
    }

    public function checkArray($payload)
    {
        $array = [];
        if (is_string($payload)) {
            $array = [$payload];
        } else {
            $array = $payload;
        }
        return $array;
    }

    // The key for each item in the group
    public function groupKeyColumns()
    {
        $group = strtolower($this->group);
        
        $columns = ['code'];

        // Translate group to attribute column
        switch ($group) {
            case 'channel':
            case 'initiative':
                return [];
                break;
            case 'program':
                $group = 'code';
                break;
            case 'business unit':
                $group = 'bu';
                break;
            case 'school':
                $group = 'partner';
                break;
            case 'degree type':
                $group = 'type';
                break;
            case 'degree level':
                $group = 'level';
                break;
            default:
                $group = 'code';
                break;
        }

        array_push($columns, $group);
        
        return $columns;
    }

    public function keys()
    {
        $group = strtolower($this->group);
        if ($group == 'channel' || $group == 'initiative') {
            return [];
        } elseif ($group == 'program') {
            $group = 'code';
        }
        
        $columns = $this->groupKeyColumns();

        $programs = $this->programsList();
        
        $query = Program::whereIn('code', $programs)
            ->get($columns);

        $array = [];
        
        foreach ($query as $q) {
            $array[$q->code] = ($q->$group != null) ? $q->$group : 'other';
        }

        return $array;
    }

}
