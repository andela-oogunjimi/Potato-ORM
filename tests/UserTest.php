<?php

namespace Opeyemiabiodun\PotatoORM\Test;

use Opeyemiabiodun\PotatoORM\Models\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
	public function testCreateUser()
	{
		/*
		CREATE TABLE "User-table"
		(
		  id integer NOT NULL DEFAULT nextval('users_id_seq'::regclass),
		  name character varying(255) NOT NULL,
		  address character varying(255) NOT NULL,
		  phone character varying(255) NOT NULL,
		  CONSTRAINT "User-table_pkey" PRIMARY KEY (id)
		)
		WITH (
		  OIDS=FALSE
		);
		ALTER TABLE "User-table"
		  OWNER TO potatouser;
		*/
	
		$user = new User();
		$user->name = "Tayo";
		$user->address = "54, Kilani street, Akarigbo, Jiyanland.";
		$user->phone = "07834531265";
		$this->assertTrue($user->save());
	}
	
	public function testDeleteUser()
	{
		$this->assertTrue(User::destroy(1));
	}

	public function testFindUser()
	{
		$this->assertEquals("Opeyemiabiodun\PotatoORM\Models\User", get_class(User::find(3)));
	}
}