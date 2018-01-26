<?php
require('class.ziprecruiter.php');
$key = '';
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
