<?php

namespace App\Classes;

use DB;
use App\Classes\Time;
use App\Classes\Program\Details;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Carbon\Carbon;

use App\Actuals2018;
use App\Actuals2019;
use App\Metrics;
use App\Program;

// Salesforce Integration
class Salesforce 
{
    public $programs;
    public $table = 'salesforce_export';

    // Columns to pull from DB table
    public $columns = [
        'program',
        'channel',
        'initiative',
        'date',
        'stage',
        'stage_detail',
        'enquiry',
        'contact',
        'contact_factor',
        'quality_factor',
        'prospect',
        'app_start',
        'date_app_start',
        'app_submit',
        'date_app_submit',
        'partner_offer',
        'date_partner_offer',
        'register',
        'date_register',
    ];

    private $testUri = 'https://eu6.salesforce.com/services/data/';
    private $versionUri = 'https://eu6.salesforce.com/services/data/v43.0/';

    public function __construct()
    {
        $this->allPrograms();
    }

    private function instance()
    {
        return env('SALESFORCE_INSTANCE');
    }
    private function instanceUrl()
    {
        return 'https://' . $this->instance() . '.salesforce.com';
    }
    private function user()
    {
        return env('SALESFORCE_USER');
    }
    private function pass()
    {
        return env('SALESFORCE_PASS');
    }
    private function token()
    {
        return env('SALESFORCE_TOKEN');
    }
    public function clientId()
    {
        return env('SALESFORCE_CLIENT_ID');
    }
    public function clientSecret()
    {
        return env('SALESFORCE_CLIENT_SECRET');
    }
    public function passToken()
    {
        return $this->pass() . $this->token();
    }

    public function accessTokenUrl()
    {
        // $url = $this->instanceUrl();
        // $url .= '/services/oauth2/token';
        $url = 'https://test.salesforce.com/services/oauth2/token';

        return $url;
    }

    // Fetch Access Token
    public function accessToken()
    {
        $client = new Client();

        $token = $client->request(
            'POST',
            $this->accessTokenUrl(),
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $this->user(),
                    'password' => $this->passToken(),
                    'client_id' => $this->clientId(),
                    'client_secret' => $this->clientSecret(),
                ]
            ]
        );

        return $token;
    }

    public function services()
    {
        $uri = $this->testUri;

        $client = new Client();
        $result = $client->get($uri);

        return $result;
    }


    // Get data from table (ignore Pep Launch Leads)
    public function data()
    {
        $data = DB::table($this->table)
            ->where('program', '!=', 'Others')
            ->where('initiative', '!=', 'PEP Launch Lead File')
            ->get($this->columns);
        return $data;
    }

    // Create arrays to update in monthly_actuals_20XX 
    public function actualsData()
    {
        $data = $this->data();
        
        $time = new Time;
        $months = $time->allMonths;

        $details = new Details;
        $blank = $details->metricTableArray();

        $array = [];

        $metricsData = [];
        $insertData = collect([]);

        // dd($collection);
        foreach ($data as $d) {
            $date = $d->date;
            $year = $time->excelToYear($date);
            $month = $time->excelToName($date);
            $iso = $time->excelToIso($date);
            
            $metrics = $this->getMetrics($d);
            $program = $this->getProgram($d->program);
            $channel = $this->getChannel($d->channel);
            $initiative = $d->initiative;

            $obj = new \StdClass();
            $obj->year = $year;
            $obj->month = $month;
            $obj->program = $program;
            $obj->channel = $channel;
            $obj->initiative = $initiative;
            $obj->metrics = $metrics;
            array_push($metricsData, $obj);
            
            $attributes = $this->programs->where('code', $program);
            $attributes = $attributes->first();

            if (!array_key_exists($year, $array)) {
                $array[$year] = [];
            }
            // For each metric
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

                    $array[$year][$metric][$program][$channel][$initiative]['location'] = $attributes->location;
                    $array[$year][$metric][$program][$channel][$initiative]['business_unit'] = $attributes->bu;
                    $array[$year][$metric][$program][$channel][$initiative]['school'] = $attributes->partner;
                    $array[$year][$metric][$program][$channel][$initiative]['program'] = $program;
                    $array[$year][$metric][$program][$channel][$initiative]['channel'] = $channel;
                    $array[$year][$metric][$program][$channel][$initiative]['initiative'] = $initiative;
                    $array[$year][$metric][$program][$channel][$initiative]['year'] = $year;

                    foreach ($months as $mo) {
                        $array[$year][$metric][$program][$channel][$initiative][$mo] = 0;
                    }
                }

            }
        }

        foreach($metricsData as $d) {
            foreach ($d->metrics as $metric) {
                $array[$d->year][$metric][$d->program][$d->channel][$d->initiative][$d->month]++;
            }
        }
        
        // return $array;


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

        $programs = $this->programs->pluck('code')->all();
        
        // Clear all records for current programs
        $clearActuals = Actuals2018::whereIn('program', $programs)
            ->where('metric', 'Leads')
            ->delete();
        $clearMetrics = Metrics::whereIn('program', $programs)
            ->delete();

        // Insert each row to respective table
        foreach ($rows as $row) {
            if ($row['metric'] == 'Leads' && $row['year'] == 2018) {
                $insert = Actuals2018::insert($row);
            } else {
                $insert = Metrics::insert($row);
            }
        }
        return $rows;
    }
    
    // Return array of metrics for a given row
    public function getMetrics($d)
    {
        $time = new Time;
        $array = [];
        // Leads
        if ($d->enquiry > 0) {
            array_push($array, 'Leads');
        }
        // InSales
        if ($d->prospect > 0) {
            array_push($array, 'InSales');
        }
        // Contact
        if ($d->contact > 0) {
            array_push($array, 'contact');
        }
        // Contact 15
        if ($d->contact_factor == 'Contact 15') {
            array_push($array, 'contact15');
        }
        // Quality
        if ($d->quality_factor != '') {
            array_push($array, 'quality');
        }
        // Quality 30
        if ($d->quality_factor == 'Quality 15' || $d->quality_factor == 'Quality 30') {
            array_push($array, 'quality30');
        }
        // AIP && APP
        if ($d->app_start > 0) {
            array_push($array, 'AIP');
            array_push($array, 'APP');
        }
        // App30
        if ($d->date_app_start) {
            $date = $time->excelToIso($d->date);
            $dateApp = $time->excelToIso($d->date_app_start);
            $carbonDate = new Carbon($date);
            $carbonApp = new Carbon($dateApp);
            $diff = $carbonDate->diffInDays($carbonApp);
            if ($diff < 30) {
                array_push($array, 'App30');
            }
        }
        // Comfile
        if ($d->app_submit > 0) {
            array_push($array, 'Comfile');
        }
        // Comfile60
        if ($d->date_app_submit) {
            $date = $time->excelToIso($d->date);
            $dateSubmit = $time->excelToIso($d->date_app_submit);
            $carbonDate = new Carbon($date);
            $carbonSubmit = new Carbon($dateSubmit);
            $diff = $carbonDate->diffInDays($carbonSubmit);
            if ($diff < 60) {
                array_push($array, 'Comfile60');
            }
        }
        // Accepted
        if ($d->partner_offer > 0) {
            array_push($array, 'Acc');
        }
        // Acc90
        if ($d->date_partner_offer) {
            $date = $time->excelToIso($d->date);
            $dateAcc = $time->excelToIso($d->date_partner_offer);
            $carbonDate = new Carbon($date);
            $carbonAcc = new Carbon($dateAcc);
            $diff = $carbonDate->diffInDays($carbonAcc);
            if ($diff < 90) {
                array_push($array, 'Acc90');
            }
        }
        // Accepted Confirmed
        if ($d->register > 0) {
            array_push($array, 'AccConf');
        }
        // Accepted Confirmed 120
        if ($d->date_register) {
            $date = $time->excelToIso($d->date);
            $dateRegister = $time->excelToIso($d->date_register);
            $carbonDate = new Carbon($date);
            $carbonRegister = new Carbon($dateRegister);
            $diff = $carbonDate->diffInDays($carbonRegister);
            if ($diff < 120) {
                array_push($array, 'AccConf120');
            }
        }
        if ($d->stage == 'Registered / Deposit Paid') {
            array_push($array, 'StartsLeadmonth');
        }


        return $array;
    }

    // Get Program from string
    public function getProgram($string)
    {
        $program = str_replace('_', '-', $string);
        return $program;
    }
    // Get Channel from string
    public function getChannel($string)
    {
        $channel = $string;
        if (str_contains($channel, 'Other')) {
            $channel = 'Other';
        } elseif (str_contains($channel, 'Referral')) {
            $channel = 'Referral';
        } elseif ($channel == 'Search') {
            $channel = 'SEO';
        }
        return $channel;
    }

    public function allPrograms()
    {
        $codes = DB::table($this->table)
            ->distinct()
            ->where('program', '!=', 'Others')
            ->get(['program']);
        $array = [];
        foreach ($codes as $c) {
            array_push($array, str_replace('_', '-', $c->program));
        }
        $programs = Program::whereIn('code', $array)->get();
        $this->programs = $programs;
        return json_encode($programs);
    }

    public function checkArrayForElement($array, $el, $value = '')
    {
        if (!array_key_exists($el, $array)) {
            $array[$el] = $value;
        }
        return $array;
    }


    public function soqlQuery()
    {
        $props = [
            'Name',
            'ProgramofInterest__c',
            'Program_access_code__c',
            'POLS_Channel__c',
            'POLS_Source__c',
            'Contact_Factor__c',
            'POLS_Quality_Factor__c',
            'App_Started__c',
            'App_Submitted__c',
            'Analytics_Utm_Campaign__c',
            'Analytics_Utm_Medium__c',
            'Analytics_Utm_Source__c',
            'Analytics_Keyword__c',
            'EnrollmentrxRx__Admissions_Status__c',
            'Date_Inquiry__c',
            'Date_Contact_Complete__c',
            'Date_Prospect__c',
            'Date_App_Started__c',
            'Date_App_Submitted__c',
            'Term_Orig_Registered__c'
        ];

        $text = 'SELECT
            Name,
            ProgramofInterest__c,
            Program_access_code__c,
            POLS_Channel__c,
            POLS_Source__c,
            Contact_Factor__c,
            POLS_Quality_Factor__c,
            App_Started__c,
            App_Submitted__c,
            Analytics_Utm_Campaign__c,
            Analytics_Utm_Medium__c,
            Analytics_Utm_Source__c,
            Analytics_Keyword__c,
            EnrollmentrxRx__Admissions_Status__c,
            Date_Inquiry__c,
            Date_Contact_Complete__c,
            Date_Prospect__c,
            Date_App_Started__c,
            Date_App_Submitted__c,
            Term_Orig_Registered__c

            FROM EnrollmentrxRx__Enrollment_Opportunity__c
            ORDER BY Date_Inquiry__c ASC NULLS LAST';

        return str_replace(' ', '+', $text);
    }
}