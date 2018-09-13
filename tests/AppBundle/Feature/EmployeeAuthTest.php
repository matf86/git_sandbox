<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Book;
use AppBundle\Entity\Branch;
use AppBundle\Entity\Employee;
use AppBundle\Entity\Issue;
use AppBundle\Entity\Position;
use AppBundle\Entity\Publisher;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\DatabasePrimer;


class EmployeeAuthTest extends WebTestCase
{
    private $em;

    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $this->em = self::$kernel->getContainer()->get('doctrine')
                                ->getManager();
    }

    private function createBranch() {

        $branch = new Branch();
        $branch->setName('Biblioteka Testowa');
        $branch->setStreet('Ulica Testowa');
        $branch->setCity('Miasto Testowe');
        $branch->setZip('62-000');
        $branch->setPhone('234123321');
        $branch->setEmail('test@testowo.pl');

        return $branch;
    }

    private function createPublisher() {

        $publisher = new Publisher();
        $publisher->setName('Wydawnictwo Testowe');

        return $publisher;
    }

    private function createPosition($name = 'manager') {

        $position = new Position();
        $position->setName($name);

        return $position;
    }

    public function testEmployeeCanLogin()
    {

        $branch = $this->createBranch();
        $position = $this->createPosition();

        $this->em->persist($branch);
        $this->em->persist($position);


        $emp_manager = new Employee();
        $emp_manager->setName('Test_'.'1');
        $emp_manager->setSecondName('Test_'.'1');
        $emp_manager->setEmail('test@test.pl');
        $emp_manager->setPosition($position);
        $emp_manager->setPassword('test');
        $emp_manager->setBranch($branch);

        $this->em->persist($emp_manager);

        $this->em->flush();

        $client = static::createClient();

        $response = $client->request('POST', '/dashboard/login', [
            '_email' => 'test@test.pl',
            '_password' => 'test'
        ]);

        dump($client->getResponse());die();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
