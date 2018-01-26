# ZipRecruiter
Job Searching using Zip-Recruiter API:  Demo at:  https://helloacm.com/software-engineering-jobs/

# Introduction
The ZipRecruiter Search API enables our search Publisher partners to write software that works with ZipRecruiter’s elevated search product and display the best job search matches to job seekers on their site.

This PHP library wraps the ZipRecruiter Search API and allows you to do some advanced job searches.

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

# Unit Tests
Unit tests are coming on the way...

# Live Example
The above sample has been integrated live:  https://helloacm.com/software-engineering-jobs/
