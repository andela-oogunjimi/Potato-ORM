<?php

namespace Opeyemiabiodun\PotatoORM\Test;

use PDO;
use stdClass;
use Dotenv\Dotenv;
use Opeyemiabiodun\PotatoORM\Models\User;
use Opeyemiabiodun\PotatoORM\Connections\ConnectionFactory;

class UserTest extends \PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    private $pdo;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->pdo = ConnectionFactory::load()->getPdo();
        User::createTable($this->pdo);
    }

    final public function getConnection()
    {
        if ($this->conn === null) {
            $dotenv = new Dotenv(__DIR__.'/..');
            $dotenv->load();

            $dotenv->required(['DB_DATABASE'])->notEmpty();

            $this->conn = $this->createDefaultDBConnection($this->pdo, getenv('DB_DATABASE'));
        }

        return $this->conn;
    }

    protected function getDataSet()
    {
        return new PotatoORM_DbUnit_ArrayDataSet([
            'user_table' => [
                ['id' => 1, 'name' => 'Shola Bello', 'address' => '256 private road, off excort park, Kimla, Jimsack.', 'phone' => '07093426538'],
                ['id' => 2, 'name' => 'Tomori Shogunre',  'address' => '43, kinkol view, samgara.',  'phone' => '08012345678'],
            ],
        ]);
    }

    public function testUserInstance()
    {
        $user = new User();
        $user->name = 'Tayo';
        $user->address = '54, Kilani street, Akarigbo, Jiyanland.';
        $user->phone = '07834531265';

        $this->assertTrue($user->save());

        $this->assertEquals('Tayo', $user->name);
    }

    public function testDeleteUser()
    {
        $this->assertTrue(User::destroy(1));
    }

    public function testFindandUpdateUser()
    {
        $user = User::find(2);

        $this->assertEquals("Opeyemiabiodun\PotatoORM\Models\User", get_class($user));

        $user->address = 'No. 1 Update grove, off The Past Street, Now Savedland.';

        $this->assertTrue($user->save());
    }

    public function testGetAllUsers()
    {
        $records = User::getAll();

        $count = count(User::getAll());

        for ($i = 0; $i < $count; $i++) {
            $this->assertEquals("Opeyemiabiodun\PotatoORM\Models\User", get_class($records[$i]));
        }
    }

    /**
     * @expectedException Opeyemiabiodun\PotatoORM\Exceptions\PropertyNotFoundException
     */
    public function testSetPropertyNotFoundException()
    {
        $user = new User();

        $user->nonproperty = 'faker';
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
    public function testDeleteUserInvalidArgumentExceptionForRealIntegers()
    {
        User::destroy('3');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDeleteUserInvalidArgumentExceptionForNegativeIntegers()
    {
        User::destroy(-1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindUserInvalidArgumentExceptionForRealIntegers()
    {
        User::find('3');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFindUserInvalidArgumentExceptionForNegativeIntegers()
    {
        User::find(-1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetTableInvalidArgumentException()
    {
        $anotherUser = new User([], null, 1234);
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
