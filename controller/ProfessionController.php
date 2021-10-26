<?php

namespace controller;

use model\Profession;

class ProfessionController
{
    public function getAll() {
        $professions = (new Profession())->getAll();
        $response = [];
        if (count($professions) > 0) {
            foreach ($professions as $profession) {
                $response[] = [
                    'id' => $profession->id,
                    'name' => $profession->name,
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