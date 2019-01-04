<?php

namespace App\Classes;

use DB;

use App\GaCode;
use App\LandingPage;
use App\PartnerLink;

// Google Analytics Class:
class Analytics
{
    public $program;
    public $channel = 'all';
    public $url;

    public function setProgram($program)
    {
        $this->program = $program;
    }
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }
    public function setUrl($url, $scheme = 'http://')
    {  
        $fullUrl = parse_url($url, PHP_URL_SCHEME) === null ?
            $scheme . $url : $url;

        $this->url = $fullUrl;
    }

    

    /**
     * JSON file client_secrets.json in storage/google
     * @return string $secret
     */
    public function getSecret()
    {
        $secret = storage_path('google/client_secrets.json');
        return $secret;
    }

    /**
     * Finds the Google Analytics View ID for a given program 
     * @param string $program Program Code
     * @param string $channel Marketing Channel (default 'all')
     * @return integer $view or null
     */
    public function getView($program, $channel = 'all')
    {
        $query = GaCode::query()
            ->where('program', $program)
            ->where('channel', $channel)
            ->value('analytics_view');

        return ($query !== null) ? (int) $query : null;
    }

    /**
     * Finds the Asset Domain for a given program 
     * @param string $program Program Code
     * @return string $domain or null
     */
    public function getDomain()
    {
        $program = $this->program;
        $query = GaCode::query()
            ->where('program', $program)
            ->value('domain');

        return $query;
    }

    /**
     * Finds the Partner Domain for a given program 
     * @param string $program Program Code
     * @return string $domain or null
     */
    public function getPartnerDomain($program = '')
    {
        $program = ($program === '') ? $this->program : $program;        
        $query = GaCode::query()
            ->where('program', $program)
            ->value('partner_url');

        return $query;
    }

    /**
     * Finds the Slugs (array of objects) for a given program 
     * @param string $program Program Code
     * @return array key = program, value = slug
     */
    public function getSlugs($program)
    {
        $query = LandingPage::query()
            ->where('program', $program)
            ->get();
        $array = [];
        foreach ($query as $q) {
            $array[$q->slug] = $q;
        }
        return $array;
    }

    public function getAllDomains()
    {
        $query = GaCode::query()
            ->distinct()
            ->pluck('domain')
            ->toArray();
        return $query;
    }

    /**
     * Finds Array of programs and current LP Count for a given channel
     * @param string $channel Current Marketing Channel
     * @return array key = program code, value = count
     */
    public function getPageCount($channel = '')
    {
        $query = LandingPage::select('program', DB::raw('count(*) as count'))
            ->groupBy('program');
        
        if (!empty($channel)) {
            $query = $query->where('channel', $channel);
        }
        $query = $query->get();

        $array = [];
        foreach ($query as $q) {
            $array[$q->program] = $q->count;
        }

        return $array;
    }

    /**
     * Finds Landing Page details for a specified ID
     * @param int $id
     * @return LandingPage with Domain details
     */
    public function getPage($id)
    {
        $query = LandingPage::where('landing_pages.id', $id)
            ->leftJoin('program_details', 'landing_pages.program', '=', 'program_details.program')
            ->leftJoin('google_analytics', 'landing_pages.program', '=', 'google_analytics.program')
            ->first();

        return $query;
    }

    public function getPageTitle()
    {
        $page = $this->url;
        if (empty($page)) {
            return '';
        }

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $code = file_get_contents($page, false, stream_context_create($arrContextOptions));


        // $code = file_get_contents($page);
        if (strlen($code)>0) {
            $code = trim(preg_replace('/\s+/', ' ', $code)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i", $code, $title); // ignore case
            return $title[1];
        }
    }

    public function getLinks()
    {
        return $this->getProgramLinks();
    }

    /**
     * Get the current database of links for the current program
     * @return array link details for the current program
     */
    public function getProgramLinks()
    {
        $program = $this->program;
        $query = PartnerLink::where('program', $program)
            ->get();
        return $query;
    }

    /**
     * Check a page url for links to a program asset
     * @param page url to parse for links
     * @return array link details for the given page
     */
    public function getAllOnPageLinks()
    {
        $page = $this->url;
        $array = [];
        if (empty($page)) {
            return [];
        }


        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $input = file_get_contents($page, false, stream_context_create($arrContextOptions));


        // $input = @file_get_contents($page); //or die("404 Cannot access file: <a target=\"_blank\" href=\"$page\">$page</a>");
        
        // Match all links on the page
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if (preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $link = new \StdClass();
                $link->anchor = $match[3];
                $link->href = str_replace('\'', '', $match[2]);
                $link->domain = $this->getHost($match[2]);
                // $link->link = $match[0];
                array_push($array, $link);
            }
        }
        return $array;
    }

    /**
     * Match all links on a page a page url for links to a program asset
     * @param page url to parse for links
     * @return array link details for the given page TO the marketing asset
     */
    public function matchingPageLinks()
    {
        $array = [];
        $links = $this->getAllOnPageLinks();
        $domain = $this->getDomain();

        // If the href contains the domain, add to match array
        foreach ($links as $link) {
            if (strpos($link->href, $domain) !== false) {
                array_push($array, $link);
            }
        }
        return $array;
    }


    /**
     * Check a page for links to a program asset
     * @param url of the partner page to scan
     * @return object report for that page / program
     */
    public function referralLinksReport()
    {
        $data = new \StdClass();
        $data->program = $this->program;
        $data->domain = $this->getDomain();
        $data->partnerUrl = $this->url;

        $title = $this->getPageTitle();
        $data->title = trim($title);

        $data->matchingLinks = $this->matchingPageLinks();
        $data->programLinks = $this->getLinks();

        return $data;
    }

    /**
     * Get the host of a url
     * @param url of page
     * @return string host
     */
    function getHost($page)
    {
        $url = parse_url(trim($page));
        if (!empty($url) && !empty($url['host'])) {
            # code...
            return trim($url['host'] ? $url['host'] : array_shift(explode('/', $url['path'], 2)));
        }
    }


    /**
     * Check a single URL for links to ANY program asset
     * @param url of the partner page to scan
     * @return object report of links on the given page
     */
    public function pageLinkReport()
    {
        $data = new \StdClass();
        $data->url = $this->url;

        $title = $this->getPageTitle();
        $data->title = trim($title);  
        
        $data->links = $this->getAllOnPageLinks();
        

        return json_encode($data);
    }

    public function partnerReferralLinkObject()
    {
        $program = $this->program;
        $reports = $this->partnerReferralLinkReport($program);

        $returnArray = [];

        for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
			
				$report = $reports[ $reportIndex ];
				$header = $report->getColumnHeader();
				$dimensionHeaders = $header->getDimensions();
				$metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
				$rows = $report->getData()->getRows();
			

			for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++ ) {
                $obj = new \StdClass();
                $row = $rows[ $rowIndex ];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
				
                $obj->url = $dimensions[0];

                for  ($j = 0; $j < count($metrics); $j++) {
                    $values = $metrics[$j]->getValues();
                    for  ($k = 0; $k < count($values); $k++) {
                        $entry = $metricHeaders[$k];
                        $name = $entry->name;
                        $obj->$name = (int)$values[$k];
                    }
                }
                array_push($returnArray, $obj);
            }
        }
        
        return $returnArray;
    }


    ///////////////////////
    // Analytics Reports //
    ///////////////////////

    /**
     * Initializes an Analytics Reporting API V4 service object.
     * @return An authorized Analytics Reporting API V4 service object.
     */
    public function initializeAnalytics()
    {
        $KEY_FILE_LOCATION = $this->getSecret();

        // Create and configure a new client object.
        $client = new \Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new \Google_Service_AnalyticsReporting($client);

        return $analytics;
    }


    /**
     * Queries Links referred from Partner Domain to Pearson Marketing Asset
     *
     * @param program Program Code
     * @return The Analytics Reporting API V4 response.
     */
    public function partnerReferralLinkReport($program = 'EKU-BSOS')
    {
        $analytics = $this->initializeAnalytics();

        // Domain and GA View for Program
        $domain = $this->getPartnerDomain($program);
        $view = $this->getView($program);

        $VIEW_ID = (string) $view;

        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("91daysAgo");
        $dateRange->setEndDate("yesterday");

        // Create the Metrics object.
        $sessions = new \Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");

        $goals = new \Google_Service_AnalyticsReporting_Metric();
        $goals->setExpression("ga:goalCompletionsAll");
        $goals->setAlias("goals");

        //Create the Dimensions object.
        $date = new \Google_Service_AnalyticsReporting_Dimension();
        $date->setName("ga:fullReferrer");
        
        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // Create Dimension Filter.
        $dimensionFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionFilter->setDimensionName("ga:source");
        $dimensionFilter->setOperator("PARTIAL");
        $dimensionFilter->setExpressions(array($domain));

        // Create Segment Filter Clause.
        $segmentFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $segmentFilterClause->setDimensionFilter($dimensionFilter);

        // Create the Or Filters for Segment.
        $orFiltersForSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));

        // Create the Simple Segment.
        $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName("SEO2");

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($sessions, $goals));
        $request->setDimensions(array($date, $segmentDimensions));
        $request->setSegments(array($segment));

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array( $request));
        return $analytics->reports->batchGet($body);
    }



    /**
     * Queries Links referred from Partner Domain to Pearson Marketing Asset
     *
     * @param program Program Code
     * @return The Analytics Reporting API V4 response.
     */
    public function landingPageReport($program = 'EKU-BSOS')
    {
        $analytics = $this->initializeAnalytics();
        // Domain and GA View for Program
        // $domain = $this->programDomain($program);
        $view = $this->getView($program);

        $VIEW_ID = (string) $view;

        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("91daysAgo");
        $dateRange->setEndDate("yesterday");

        // Create the Metrics object.
        $sessions = new \Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");

        $goals = new \Google_Service_AnalyticsReporting_Metric();
        $goals->setExpression("ga:goalCompletionsAll");
        $goals->setAlias("goals");

        //Create the Dimensions object.
        $path = new \Google_Service_AnalyticsReporting_Dimension();
        $path->setName("ga:landingPagePath");
        
        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($sessions, $goals));
        $request->setDimensions($path);
        //$request->setSegments(array($segment));

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));
        return $analytics->reports->batchGet($body);
    }



    /**
     * Queries Monthly traffic by segment
     *
     * @param program Program Code
     * @return Analytics Reporting API V4 response.
     */
    public function monthlyTrafficReport($program = 'EKU-BSOS')
    {
        $analytics = $this->initializeAnalytics();
        //  GA View for Program
        $view = $this->getView($program);

        // Date should be through previous month
        $now = new \DateTime();
        $now->modify("last day of previous month");
        $endDate = $now->format("Y-m-d");

        $VIEW_ID = (string) $view;

        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("2016-01-01");
        $dateRange->setEndDate($endDate);

        // Create the Metrics object.
        $sessions = new \Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");

        $goals = new \Google_Service_AnalyticsReporting_Metric();
        $goals->setExpression("ga:goalCompletionsAll");
        $goals->setAlias("goals");

        //Create the Dimensions object.
        $date = new \Google_Service_AnalyticsReporting_Dimension();
        $date->setName("ga:yearMonth");
        
        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // Create Dimension Filter.
        $dimensionFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionFilter->setDimensionName("ga:landingPagePath");
        $dimensionFilter->setOperator("BEGINS_WITH");
        $dimensionFilter->setExpressions(array('/lpap/'));

        // Create Segment Filter Clause.
        $segmentFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $segmentFilterClause->setDimensionFilter($dimensionFilter);

        // Create the Or Filters for Segment.
        $orFiltersForSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));

        // Create the Simple Segment.
        $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName("Organic");

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics(array($sessions, $goals));
        $request->setDimensions(array($date, $segmentDimensions));
        $request->setSegments(array($segment));

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array( $request));
        return $analytics->reports->batchGet($body);
    }

    //////////////
    // Segments //
    //////////////


    // Landing Page Reporting (Totals) for a given page or domain
    public function lpReportingSegments($page = '', $type = 'page')
    {
        $analytics = $this->initializeAnalytics();

        $gaView =  (string) $page->analytics_view;
        $slug = $page->slug;
        $partnerDomain = $page->partner_url;
        
        $now = new \DateTime();
        $lastDate = $now->modify('last day of previous month');
        $endDate = $lastDate->format('Y-m-d');

        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("2016-01-01");
        $dateRange->setEndDate($endDate);

        // Create the Metrics object.
        $sessions = new \Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");

        $goals = new \Google_Service_AnalyticsReporting_Metric();
        $goals->setExpression("ga:goalCompletionsAll");
        $goals->setAlias("goals");

        //Create the device dimension.
        $device = new \Google_Service_AnalyticsReporting_Dimension();
        $device->setName("ga:deviceCategory");

        $yearMonth = new \Google_Service_AnalyticsReporting_Dimension();
        $yearMonth->setName("ga:yearMonth");

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($gaView);
        $request->setDateRanges(array($dateRange));
        $request->setDimensions(array($yearMonth, $segmentDimensions));
        $request->setMetrics(array($sessions, $goals));

        // $analytics = new Analytics;
        // Specifying multiple segments
        $desktopSegment = $this->desktopSegment($page, $type);
        $mobileSegment = $this->mobileSegment($page, $type);
        $tabletSegment = $this->tabletSegment($page, $type);

        $request->setSegments(array($desktopSegment, $mobileSegment, $tabletSegment));

        // Create the GetReportsRequest object.
        $getReport = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $getReport->setReportRequests(array($request));

        // Call the batchGet method.
        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array( $request));
        $response = $analytics->reports->batchGet($body);

        return $response->getReports();
        //$this->printResults($response->getReports());
    }


    // SEO Reporting (organic, referral, direct, seo2) for a given page or domain
    public function seoReportingSegments($page = '', $type = 'page')
    {
        $analytics = $this->initializeAnalytics();
        
        $gaView =  (string) $page->analytics_view;
        $slug = $page->slug;
        $partnerDomain = $page->partner_url;

        $now = new \DateTime();
        $lastDate = $now->modify('last day of previous month');
        $endDate = $lastDate->format('Y-m-d');

        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("2016-01-01");
        $dateRange->setEndDate($endDate);

        // Create the Metrics object.
        $sessions = new \Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");

        $goals = new \Google_Service_AnalyticsReporting_Metric();
        $goals->setExpression("ga:goalCompletionsAll");
        $goals->setAlias("goals");

        //Create the device dimension.
        $device = new \Google_Service_AnalyticsReporting_Dimension();
        $device->setName("ga:deviceCategory");

        $yearMonth = new \Google_Service_AnalyticsReporting_Dimension();
        $yearMonth->setName("ga:yearMonth");


        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");


        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($gaView);
        $request->setDateRanges(array($dateRange));
        $request->setDimensions(array($yearMonth, $segmentDimensions));
        $request->setMetrics(array($sessions, $goals));

        // Specifying multiple segments
        $organicSegment = $this->organicSegment($page, $type);
        $directSegment = $this->directSegment($page, $type);
        $referralSegment = $this->referralSegment($page, $type);
        $seo2Segment = $this->seo2Segment($page, $type);

        $request->setSegments(array($organicSegment, $directSegment, $referralSegment, $seo2Segment));

        // Create the GetReportsRequest object.
        $getReport = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $getReport->setReportRequests(array($request));

        // Call the batchGet method.
        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array( $request));
        $response = $analytics->reports->batchGet($body);

        return $response->getReports();
        //$this->printResults($response->getReports());
    }


    // Create a Desktop segment - input is $page object
    public function desktopSegment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Mobile visits
        // Create Dimension Filter device == mobile
        $desktopFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $desktopFilter->setDimensionName('ga:deviceCategory');
        $desktopFilter->setOperator("EXACT");
        $desktopFilter->setExpressions(array('desktop'));

        // Create Segment Filter Clause.
        $desktopFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $desktopFilterClause->setDimensionFilter($desktopFilter);

        // Create the Or Filters for Segment.
        $orFiltersForDesktopSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForDesktopSegment->setSegmentFilterClauses(array($desktopFilterClause));

        // Filter - Traffic to page
        // Create Dimension Filter.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate filters
        // Create the Simple Segment.
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForDesktopSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForDesktopSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForDesktopSegment));
        }

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('Desktop');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // Create a Referral segment - input is $page object
    public function mobileSegment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Mobile visits
        // Create Dimension Filter device == mobile
        $mobileFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $mobileFilter->setDimensionName('ga:deviceCategory');
        $mobileFilter->setOperator("EXACT");
        $mobileFilter->setExpressions(array('mobile'));

        // Create Segment Filter Clause.
        $mobileFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $mobileFilterClause->setDimensionFilter($mobileFilter);

        // Create the Or Filters for Segment.
        $orFiltersForMobileSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForMobileSegment->setSegmentFilterClauses(array($mobileFilterClause));

        // Filter - Traffic to page
        // Create Dimension Filter.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate filters
        // Create the Simple Segment.
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForMobileSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForMobileSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForMobileSegment));
        }

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('Mobile');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // Create a Tablet segment - input is $page object
    public function tabletSegment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Mobile visits
        // Create Dimension Filter device == mobile
        $tabletFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $tabletFilter->setDimensionName('ga:deviceCategory');
        $tabletFilter->setOperator("EXACT");
        $tabletFilter->setExpressions(array('tablet'));

        // Create Segment Filter Clause.
        $tabletFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $tabletFilterClause->setDimensionFilter($tabletFilter);

        // Create the Or Filters for Segment.
        $orFiltersForTabletSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForTabletSegment->setSegmentFilterClauses(array($tabletFilterClause));

        // Filter - Traffic to page
        // Create Dimension Filter.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate filters
        // Create the Simple Segment.
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForTabletSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForTabletSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForTabletSegment));
        }

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('Tablet');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // SEO Reporting Segments (SEO2, Organic, Referral, Direct)

    // Create an SEO2 segment - input is $page object
    public function seo2Segment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $partnerFilter .= '|seo2';

        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$|^\/\?Access_Code.*SEO2';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Medium == Referral
        // Create Dimension Filter.
        $referralFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $referralFilter->setDimensionName('ga:medium');
        $referralFilter->setOperator("EXACT");
        $referralFilter->setExpressions(array('referral'));

        // Create Segment Filter Clause.
        $referralFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $referralFilterClause->setDimensionFilter($referralFilter);

        // Create the Or Filters for Segment.
        $orFiltersForReferralSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForReferralSegment->setSegmentFilterClauses(array($referralFilterClause));

        // Filter - Source from Partner Domain
        // Create Filter with a source containing the partner domain or the string 'seo2'
        $seo2Filter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $seo2Filter->setDimensionName('ga:source');
        $seo2Filter->setOperator("REGEXP");
        $seo2Filter->setExpressions(array($partnerFilter));

        // Create Segment Filter Clause.
        $seo2FilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $seo2FilterClause->setDimensionFilter($seo2Filter);

        // Create the Or Filters for Segment.
        $orFiltersForPartnerSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPartnerSegment->setSegmentFilterClauses(array($seo2FilterClause));

        // Filter - Traffic to single pageslug
        // Create Dimension Filter.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate filters
        // Create the Simple Segment.
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForReferralSegment, $orFiltersForPartnerSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForReferralSegment, $orFiltersForPartnerSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForReferralSegment, $orFiltersForPartnerSegment));
        }
        
        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('SEO2');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // Create an Organic segment - input is $page object - type can be single page (default) or whole domain;
    public function organicSegment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Organic
        // Create Dimension Filter for medium == organic.
        $organicFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $organicFilter->setDimensionName('ga:medium');
        $organicFilter->setOperator("EXACT");
        $organicFilter->setExpressions(array('organic'));

        // Create Segment Filter Clause.
        $organicFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $organicFilterClause->setDimensionFilter($organicFilter);

        // Create the Or Filters for Segment.
        $orFiltersForOrganicSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForOrganicSegment->setSegmentFilterClauses(array($organicFilterClause));

        // Filter - Traffic to single slug
        // Create Dimension Filter.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate all filters
        // Create the Simple Segment.
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForOrganicSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForOrganicSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForOrganicSegment));
        }

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('Organic');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // Create a Direct segment - input is $page object
    public function directSegment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Direct
        // Create Dimension Filter where source == (direct).
        $directFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $directFilter->setDimensionName('ga:source');
        $directFilter->setOperator("EXACT");
        $directFilter->setExpressions(array('(direct)'));

        // Create Segment Filter Clause.
        $directFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $directFilterClause->setDimensionFilter($directFilter);

        // Create the Or Filters for Segment.
        $orFiltersForDirectSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForDirectSegment->setSegmentFilterClauses(array($directFilterClause));

        // Filter - Traffic to single slug
        // Create Dimension Filter to single page.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate filters based on request type
        // Create the Simple Segment based on requested view (page or domain).
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForDirectSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForDirectSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForDirectSegment));
        }
        

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);



        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('Direct');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // Create a Referral segment - input is $page object
    public function referralSegment($page, $type = 'page')
    {
        $partnerDomain = $page->partner_url;
        $pageSlug = $page->slug;

        $partnerFilter = str_replace('.', '\.', $partnerDomain);
        $pageFilter = str_replace('/', '\/', $pageSlug);
        if ($pageSlug == '/') {
            $pageFilter = '^\/$';
        }

        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // FILTER: Referral visits
        // Create Dimension Filter medium == referral
        $referralFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $referralFilter->setDimensionName('ga:medium');
        $referralFilter->setOperator("EXACT");
        $referralFilter->setExpressions(array('referral'));

        // Create Segment Filter Clause.
        $referralFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $referralFilterClause->setDimensionFilter($referralFilter);

        // Create the Or Filters for Segment.
        $orFiltersForReferralSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForReferralSegment->setSegmentFilterClauses(array($referralFilterClause));

        // Filter - Traffic to page
        // Create Dimension Filter.
        $lpFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $lpFilter->setDimensionName('ga:landingPagePath');
        $lpFilter->setOperator("REGEXP");
        $lpFilter->setExpressions(array($pageFilter));

        // Create Segment Filter Clause.
        $lpFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $lpFilterClause->setDimensionFilter($lpFilter);

        // Create the Or Filters for Segment.
        $orFiltersForPageSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForPageSegment->setSegmentFilterClauses(array($lpFilterClause));

        // Consolidate filters
        // Create the Simple Segment.
        if ($type == 'page') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForReferralSegment, $orFiltersForPageSegment));
        } elseif ($type == 'domain') {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForReferralSegment));
        } else {
            $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
            $simpleSegment->setOrFiltersForSegment(array($orFiltersForReferralSegment));
        }

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName('Referral');

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }

    // Create a new segment
    public function buildSimpleSegment($segmentName, $dimension, $dimensionFilterExpression) {
        // Create the segment dimension.
        $segmentDimensions = new \Google_Service_AnalyticsReporting_Dimension();
        $segmentDimensions->setName("ga:segment");

        // Create Dimension Filter.
        $dimensionFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionFilter->setDimensionName($dimension);
        $dimensionFilter->setOperator("EXACT");
        $dimensionFilter->setExpressions(array($dimensionFilterExpression));

        // Create Segment Filter Clause.
        $segmentFilterClause = new \Google_Service_AnalyticsReporting_SegmentFilterClause();
        $segmentFilterClause->setDimensionFilter($dimensionFilter);

        // Create the Or Filters for Segment.
        $orFiltersForSegment = new \Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));

        // Create the Simple Segment.
        $simpleSegment = new \Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

        // Create the Segment Filters.
        $segmentFilter = new \Google_Service_AnalyticsReporting_SegmentFilter();
        $segmentFilter->setSimpleSegment($simpleSegment);

        // Create the Segment Definition.
        $segmentDefinition = new \Google_Service_AnalyticsReporting_SegmentDefinition();
        $segmentDefinition->setSegmentFilters(array($segmentFilter));

        // Create the Dynamic Segment.
        $dynamicSegment = new \Google_Service_AnalyticsReporting_DynamicSegment();
        $dynamicSegment->setSessionSegment($segmentDefinition);
        $dynamicSegment->setName($segmentName);

        // Create the Segments object.
        $segment = new \Google_Service_AnalyticsReporting_Segment();
        $segment->setDynamicSegment($dynamicSegment);
        return $segment;
    }
}
