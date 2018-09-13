<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Book;
use AppBundle\Entity\Branch;
use AppBundle\Entity\Employee;
use AppBundle\Entity\Position;
use AppBundle\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;


class BranchTest extends KernelTestCase
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

    private function createPosition() {

        $position = new Position();
        $position->setName('manager');

        return $position;
    }

    public function testBranchHasEmployees()
    {
        $branch = $this->createBranch();
        $position = $this->createPosition();

        $this->em->persist($branch);
        $this->em->persist($position);

        for($i=0; $i < 5; $i++) {

            $emp = new Employee();
            $emp->setName('Test_'.$i);
            $emp->setSecondName('Test_'.$i);
            $emp->setPosition($position);
            $emp->setPassword('test');
            $emp->setBranch($branch);

            $this->em->persist($emp);
        }

        $this->em->flush();
        $this->em->clear();

        $result = $this->em->getRepository(Branch::class)
            ->find($branch->getId());

        $this->assertInstanceOf('AppBundle\Entity\Employee', $result->getEmployees()->first());
        $this->assertEquals(5, $result->getEmployees()->count());
    }

    public function testBranchHasBooks()
    {
        $branch = $this->createBranch();
        $publisher = $this->createPublisher();

        $this->em->persist($branch);
        $this->em->persist($publisher);

        for($i=0; $i < 5; $i++) {

            $book = new Book();
            $book->setTitle('Test_book'.$i);
            $book->setAuthor('Test_author'.$i);
            $book->setStatus(1);
            $book->setPublishDate(new \DateTime());
            $book->setBranch($branch);
            $book->setPublisher($publisher);

            $this->em->persist($book);
        }

        $this->em->flush();
        $this->em->clear();

        $result = $this->em->getRepository(Branch::class)
            ->find($branch->getId());

        $this->assertInstanceOf('AppBundle\Entity\Book', $result->getBooks()->first());
        $this->assertEquals(5, $result->getBooks()->count());
    }
}
