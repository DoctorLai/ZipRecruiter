# ZipRecruiter
Job Searching using Zip-Recruiter API. Demo at: https://helloacm.com/software-engineering-jobs/ and Chrome Extension (Find a Job): https://chrome.google.com/webstore/detail/job-tools/ghclpimmbjepihhlnklkiemncamklkii

# Introduction
The ZipRecruiter Search API enables our search Publisher partners to write software that works with ZipRecruiter’s elevated search product and display the best job search matches to job seekers on their site.

This PHP library wraps the ZipRecruiter Search API and allows you to do some advanced job searches.

# Job Searching API Keys
You can call this API to get the API keys e.g. https://str.justyy.workers.dev/ziprecruiter:
```
function ziprecruiter(x) {
    return {
        "result": 2,
        "us": {
            "api": "https://api.ziprecruiter.com/jobs/v1",
            "key": "e3ataxfnpynn4zhrtjinwkxi2s4sweg7"
        },
        "uk": {
            "api": "https://api.ziprecruiter.com/jobs/v1",
            "key": "p7ark7v2nzpzat6r38zhwuftm5p22x2m"
        }            
    }
}
```

- Alert API Key for (USA): **vfdh6hytmuwf4wtuquzkbkbbn92n26ip**
- Alert API Key for (UK ): **mjx3weq6sz2s5davpjntaqq3a4e8cr9p**

# Sample API Calls for Job Searching
https://api.ziprecruiter.com/jobs/v1?search=Software%20Engineer&location=New+York&radius_miles=5000&days_ago=3000&jobs_per_page=200&page=1&api_key=p7ark7v2nzpzat6r38zhwuftm5p22x2m&refine_by_salary=0 

# How to Use?
First, require the unit `class.ziprecruiter.php` and you can create the ZipRecruiter object by passing the APP_KEY.

```
$key = "APP_KEY";
$zip = new ZipRecruiter($key);
```

Then you can search the jobs by giving the paramter `job name`, `location` and `job age` to the `Search` method:

```
$zip->Search("Software Engineer", "London, UK", 25);
```

You then need to check if results are valid by method `CheckResult`. After that, you can filter the jobs by salary range `FilterJobsBySalaryRange` or industry `FilterJobsByIndustry`

# Demo
```
<?php
$key = "APP_KEY";
$zip = new ZipRecruiter($key);
$zip->Search("Software Engineer", "London, UK", 25);

if ($zip->CheckResult()) {
  echo "Total Jobs: " . $zip->GetTotalJobs(). "\n";
  $jobs = $zip->FilterJobsBySalaryRange(55000, 75000);
  foreach ($jobs as $job) {
    echo "Job ID: " . $job->id . "\n";
    echo "Company: " . $job->hiring_company->name . "\n";
    echo "Salary Min Annual: " . $job->salary_min_annual . "\n";
    echo "Salary Max Annual: " . $job->salary_max_annual . "\n";
    echo "Job URL: " . $job->url . "\n";
    echo "Published: " . $job->job_age . " days ago.\n";
  }  
  echo count($jobs);
  $jobs = $zip->FilterJobsByIndustry("Technology", $jobs);
  echo "Total Technology jobs: " . count($jobs);
}
```

# Live Example
The above sample has been integrated live: https://helloacm.com/software-engineering-jobs/

# Chrome Extension
- [Find a Job Chrome Extension: https://chrome.google.com/webstore/detail/job-tools/ghclpimmbjepihhlnklkiemncamklkii](https://chrome.google.com/webstore/detail/job-tools/ghclpimmbjepihhlnklkiemncamklkii)
- Source Code: https://github.com/DoctorLai/JobTools
