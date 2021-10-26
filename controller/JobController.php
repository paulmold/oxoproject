<?php

namespace controller;

use model\Company;
use model\Job;
use model\Profession;

class JobController
{
    public function getAll($query) {
        $jobs = (new Job())->getAll(
            $query['sortBy'] ?? null,
            $query['company'] ?? null,
            $query['profession'] ?? null
        );
        $response = [];
        if (count($jobs) > 0) {
            foreach ($jobs as $job) {
                $company = (new Company())->id($job->company_id)->get();
                $profession = (new Profession())->id($job->profession_id)->get();
                $response[] = [
                    'name' => $job->name,
                    'description' => $job->description,
                    'expiration' => $job->expiration,
                    'openings' => $job->openings,
                    'company' => $company->name ?? null,
                    'profession' => $profession->name ?? null,
                ];
            }
        }

        return json_encode($response);
    }

//    public function get() {
//
//    }
//
//    public function add() {
//
//    }
//
//    public function update() {
//
//    }
//
//    public function delete() {
//
//    }
}