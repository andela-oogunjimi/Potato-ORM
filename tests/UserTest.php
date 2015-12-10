<?php

namespace Opeyemiabiodun\PotatoORM\Test;

use stdClass;
use Opeyemiabiodun\PotatoORM\Models\User;
use Opeyemiabiodun\PotatoORM\Connections\PgSqlConnection;

class UserTest extends \PHPUnit_Framework_TestCase
{
	public function testUserInstance()
	{	
		$user = new User();
		$user->name = "Tayo";
		$user->address = "54, Kilani street, Akarigbo, Jiyanland.";
		$user->phone = "07834531265";

		$this->assertTrue($user->save());

		$this->assertEquals("Tayo", $user->name);
	}
	
	public function testDeleteUser()
	{
		$this->assertTrue(User::destroy(1));
	}

	public function testFindandUpdateUser()
	{
		$user = User::find(7);

		$this->assertEquals("Opeyemiabiodun\PotatoORM\Models\User", get_class($user));

		$user->address = "No. 1 Update grove, off The Past Street, Now Savedland.";

		$this->assertTrue($user->save());
	}

	public function testGetAllUsers()
	{
		$records = User::getAll();

		$count = count(User::getAll());

		for ($i=0; $i < $count; $i++) { 
			$this->assertEquals("Opeyemiabiodun\PotatoORM\Models\User", get_class($records[$i]));
		}
	}

	/**
     * @expectedException Opeyemiabiodun\PotatoORM\Exceptions\PropertyNotFoundException
     */
	public function testSetPropertyNotFoundException()
	{
		$user = new User();

		$user->nonproperty = "faker";
	}

	/**
     * @expectedException Opeyemiabiodun\PotatoORM\Exceptions\AssignmentException
     */
	public function testAssignmentException()
	{
		$user = new User();

		$user->nonproperty = new stdClass();
	}

	/**
     * @expectedException Opeyemiabiodun\PotatoORM\Exceptions\PropertyNotFoundException
     */
	public function testGetPropertyNotFoundException()
	{
		$user = new User();

		$user->nonproperty;
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testDeleteUserInvalidArgumentExceptionI()
	{
		User::destroy("3");
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testDeleteUserInvalidArgumentExceptionII()
	{
		User::destroy(-1);
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testFindUserInvalidArgumentExceptionI()
	{
		User::find("3");
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testFindUserInvalidArgumentExceptionII()
	{
		User::find(-1);
	}

	/**
     * @expectedException InvalidArgumentException
     */
	public function testSetTableInvalidArgumentException()
	{
		$anotheruser = new User([], null, 1234);
	}

	/**
     * @expectedException RuntimeException
     */
	public function testSaveHasRuntimeException()
	{
		$user = new User();

		$user->save();
	}
}











