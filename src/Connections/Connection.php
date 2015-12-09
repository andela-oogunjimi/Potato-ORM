<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

interface Connection
{
    public function createRecord($table, $record);

    public function deleteRecord($table, $pk);

    public function findRecord($table, $pk);

    public function getAllRecords($table);

    public function getColumns($table);

    public function getPdo();

    public function getPrimaryKey($table);

    public function updateRecord($table, $pk, $record);
}
