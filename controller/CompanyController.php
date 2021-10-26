<?php

namespace controller;

use model\Company;

class CompanyController
{
    public function getAll() {
        $companies = (new Company())->getAll();
        $response = [];
        if (count($companies) > 0) {
            foreach ($companies as $company) {
                $response[] = [
                    'id' => $company->id,
                    'name' => $company->name,
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