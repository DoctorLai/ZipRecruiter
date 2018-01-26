<?php
// Author: @justyy
// License: MIT

class ZipRecruiter {
    /*
        define base API access end point
    */
    private $API_BASE_URL = "https://api.ziprecruiter.com/jobs/v1";
    /*
        app key
    */
    private $KEY = "";
    
    /*
        error string
    */
    private $error = "";
    
    /*
      internal data holder;
    */
    private $jobs = null;
        
    /*
        get current error
    */
    public function GetError() {
      return $this->error;
    } 
    
    /* 
      get raw data
    */
    public function GetData() {
      return $this->jobs;
    }    
    
    /*
        reset status
    */
    public function Reset() {
      $this->error = '';
      $this->jobs = null;
    }
    
    /*
       constructor
    */
    public function ZipRecruiter($key) {    
      $this->KEY = $key;
      if (!$this->KEY) {
        throw "Error: Required APP KEY";
      }
    }
    
    /*
      get API access URL
    */
    private function _getAPI($keyword, $location, $radius, $age, $page = 1, $per = 10) {
      $radius = (float)$radius;
      $age = (integer)$age;
      $page = (integer)$page;
      $per = (integer)$per;
      $keyword = urlencode($keyword);
      $location = urlencode($location);
      return $this->API_BASE_URL . "?search=$keyword&location=$location&radius_miles=$radius&days_ago=$age&jobs_per_page=$per&page=$page&api_key=" . $this->KEY;
    }
        
    /*
        call API and set status
    */
    private function CallAPI($data = null, $headers = null) {
      $keyword = $data['keyword'] ??  '';
      $location = $data['location'] ?? '';
      $radius = $data['radius'] ?? 25;
      $age = $data['age'] ?? 7;
      $page = $data['page'] ?? 1;
      $per = $data['per'] ?? 10;
      $url = $this->_getAPI($keyword, $location, $radius, $age, $page, $per);
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      }
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
      $response = curl_exec($curl);
      $data = json_decode($response);
    
      /* Check for 404 (file not found). */
      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      // Check the HTTP Status code
      switch ($httpCode) {
        case 200:
            $this->error = "200: OK";
            break;
        case 404:
            $this->error = "404: API Not found";
            break;
        case 500:
            $this->error = "500: Servers replied with an error.";
            break;
        case 502:
            $this->error = "502: Servers may be down or being upgraded. Hopefully they'll be OK soon!";
            break;
        case 503:
            $this->error = "503: Service unavailable. Hopefully they'll be OK soon!";
            break;
        default:
            $this->error = "Undocumented error: " . $httpCode . " : " . curl_error($curl);
            break;
      }
      curl_close($curl);
      return ($data);  
    }    
    
    /*
       call API to search jobs
    */
    public function Search($keywords, $location, $radius, $page = 1, $per = 10) {
      $data = array(
        "keyword" => $keywords,
        "location" => $location,
        "radius" => $radius,
        "page" => $page,
        "per" => $per
      );
      $this->jobs = $this->CallAPI($data);
      return $this->jobs;  
    }    
    
    /*
      get total jobs
    */
    public function GetTotalJobs() {
      return $this->jobs->total_jobs;
    }
    
    /* 
      check result
    */
    public function CheckResult() {
      return $this->jobs->success === true;  
    } 
    
    /*
      return all jobs
    */
    public function GetJobs() {
      return $this->jobs->jobs;
    }
    
    /*
      filter jobs by salary range
    */
    public function FilterJobsBySalaryRange($salary_min, $salary_max, $interval = 'yearly', $jobs = null) {
      $salary_min = (float)$salary_min;
      $salary_max = (float)$salary_max;
      $base = 1;
      switch ($interval) {
        case "monthly": $base = 12; break;
        case "quarterly": $base = 4; break;
        case "semi-yearly": $base = 2; break; 
      } 
      $salary_min *= $base;
      $salary_max *= $base;
      $r = array();
      $jobs = $jobs ?? $this->GetJobs();  
      foreach ($jobs as $job) {
        $max = $job->salary_max_annual;
        $min = $job->salary_min_annual;
        if (($salary_min <= $min) && ($salary_max <= $max)) {
          $r[] = $job;
        }
      }
      return $r;
    }    
    
    /*
      filter jobs by salary range
    */
    public function FilterJobsByIndustry($industry, $jobs = null) {
      $r = array();       
      $industry = strtolower($industry);
      $jobs = $jobs ?? $this->GetJobs();  
      foreach ($jobs as $job) {
        $industry_name = strtolower($job->industry_name);
        if ($industry_name == $industry) {
          $r[] = $job;
        }
      }
      return $r;
    }        
}
