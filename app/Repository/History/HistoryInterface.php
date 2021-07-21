<?php


namespace App\Repository\History;


interface HistoryInterface
{
    public function getAll($filters = array());
    public function find($id);
    public function getUserHistory($id);
    public function store($attributes);
}
