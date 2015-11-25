<?php

namespace Opeyemiabiodun\PotatoORM\Model;

interface Model
{
	
	public static function getAll();

	public function __get($property);

	public function __set($property, $value);

	public static function find($number); 

	public function save();

	public static function destroy($number);
}