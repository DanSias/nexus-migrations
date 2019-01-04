<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use App\Classes\Metrics;

class MetricController extends Controller
{
    // Check for Authorized Users
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('pearson');
        setlocale(LC_MONETARY, "en_US.UTF-8");
    }

    public function actuals(Request $request)
    {
        $metrics = new Metrics($request);
        $metrics->setType('actuals');
        $data = (json_encode($metrics->threeYears()));

        return $data;
    }

    public function budget(Request $request)
    {
        $metrics = new Metrics($request);
        $metrics->setType('budget');
        $data = (json_encode($metrics->threeYears()));

        return $data;
    }

    public function forecast(Request $request)
    {
        $metrics = new Metrics($request);
        $metrics->setType('forecast');
        $data = (json_encode($metrics->threeYears()));

        return $data;
    }

    public function pipeline(Request $request)
    {
        $metrics = new Metrics($request);
        $metrics->setType('pipeline');
        $data = (json_encode($metrics->threeYears()));

        return $data;
    }

    // Combine Actuals, Budget, and Forecast into a single request
    public function yearMonth(Request $request)
    {
        $metrics = new Metrics($request);
        $data = $metrics->actualsBudgetForecast();
        
        return json_encode($data);
    }

    // Combine metrics with Conversica
    public function data(Request $request)
    {
        $data = new \StdClass();
        $data->actuals = json_decode($this->actuals($request));
        $data->budget = json_decode($this->budget($request));
        $data->forecast = json_decode($this->forecast($request));
        $data->pipeline = json_decode($this->pipeline($request));

        return json_encode($data);
    }

    public function json(Request $request)
    {
        $m = new Metrics($request);
        return $m->data();
    }
}
