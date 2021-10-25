<?php

namespace model;

interface ModelInterface
{
//    public function getAll(): array;

    public function get(): ?ModelInterface;

    public function add(): int;

//    public function update();
//
//    public function delete();
}