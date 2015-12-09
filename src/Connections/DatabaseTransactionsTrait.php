<?php

namespace Opeyemiabiodun\PotatoORM\Connections;

use InvalidArgumentException;

trait DatabaseTransactionsTrait 
{
    /**
     * Creates a record in the database.
     *
     * @param string $table  The table where the a new record is made.
     * @param array  $record The record to be made in the database.
     *
     * @return bool
     */
    public function createRecord($table, $record)
    {
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not a string. A string is required instead.");
        }

        if (gettype($record) !== 'array') {
            throw new InvalidArgumentException("The parameter {$record} is not an array. An array is required instead.");
        }

        $count = count($record);

        $sql = "INSERT INTO {$table} (";
        foreach ($record as $key => $value) {
            
            $count--;

            if ($key === $this->getPrimaryKey($table)) {
                continue;
            }

            if ($count > 0) {
                $sql = $sql."{$key}, ";
            } else {
                $sql = $sql."{$key}) ";
            }
        }

        $count = count($record);

        $sql .= 'VALUES (';
        foreach ($record as $key => $value) {

            $count--;

            if ($key === $this->getPrimaryKey($table)) {
                continue;
            }

            if ($count > 0) {
                $sql = (empty($value)) ? $sql."NULL, " : $sql."'{$value}', ";
            } else {
                $sql = (empty($value)) ? $sql."NULL " : $sql."'{$value}') ";
            }
        }

        return $this->getPdo()->prepare($sql)->execute();
    }

    /**
     * Remove a record in the database.
     *
     * @param string $table The table where the record is removed in the database.
     * @param string $pk    The primary key value of the record.
     *
     * @return bool Returns boolean true if the record was successfully deleted or else it returns false.
     */
    public function deleteRecord($table, $pk)
    {
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not a string. A string is required instead.");
        }

        return $this->getPdo()->prepare("DELETE FROM {$table}
                                            WHERE {$this->getPrimaryKey($table)}={$pk}")->execute();
    }

    /**
     * Returns a particular record in a table.
     *
     * @param string $table The table of the record. 
     * @param string $pk    The primary key value of the record.
     *
     * @return array An array containing the particular record.
     */
    public function findRecord($table, $pk)
    {
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not a string. A string is required instead.");
        }

        return $this->getPdo()->query("SELECT * FROM {$table}
                                        WHERE {$this->getPrimaryKey($table)}={$pk}")->fetchAll();
    }

    /**
     * Returns all the records in a table.
     *
     * @param string $table The table inspected for all its records.
     *
     * @return array All the records in the table.        
     */
    public function getAllRecords($table)
    {
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not a string. A string is required instead.");
        }

        return $this->getPdo()->query("SELECT * FROM {$table}")->fetchAll();
    }

    /**
     * Update a record in the database.
     *
     * @param string $table  The table where the record update is being made.
     * @param string $pk     The primary key value of the record to be updated.
     * @param array  $record The updates to be made to the record in the database.
     *
     * @return bool Returns boolean true if the record was successfully updated or else it returns false.
     */
    public function updateRecord($table, $pk, $record)
    {
        if (gettype($table) !== 'string') {
            throw new InvalidArgumentException("The parameter {$table} is not a string. A string is required instead.");
        }

        if (gettype($pk) !== 'string') {
            throw new InvalidArgumentException("The parameter {$pk} is not a string. A string is required instead.");
        }

        if (gettype($record) !== 'array') {
            throw new InvalidArgumentException("The parameter {$record} is not an array. An array is required instead.");
        }

        $count = count($record);

        $sql = "UPDATE {$table} SET ";
        foreach ($record as $key => $value) {
            if ($count > 1) {
                $sql = $sql."{$key}={$value}, ";
            } else {
                $sql = $sql."{$key}={$value} ";
            }
            $count--;
        }
        $sql .= "WHERE {$this->getPrimaryKey($table)}='{$pk}'";

        return $this->getPdo()->prepare($sql)->execute();
    }
}