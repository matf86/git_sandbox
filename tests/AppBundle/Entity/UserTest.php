<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Book;
use AppBundle\Entity\Branch;
use AppBundle\Entity\Issue;
use AppBundle\Entity\Publisher;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;


class UserTest extends KernelTestCase
{
    private $em;

    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $this->em = self::$kernel->getContainer()->get('doctrine')
                                ->getManager();
    }

    private function createPublisher() {

        $publisher = new Publisher();
        $publisher->setName('Wydawnictwo Testowe');

        return $publisher;
    }

    private function createBook($publisher, $branch = null) {

        $book = new Book();
        $book->setAuthor('Autor Testowy');
        $book->setTitle('Tytuł testowy');
        $book->setPublishDate(new \DateTime());
        $book->setStatus(1);
        $book->setPublisher($publisher);
        $book->setBranch($branch);

        return $book;
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

    private function createUser() {

        $user = new User();
        $user->setName('Mateusz');
        $user->setSecondName('Fludra');
        $user->setPhone('784552322');
        $user->setEmail('mateusz@testy.pl');
        $user->setGoogleId('id-11111');

        return $user;
    }

    public function testUserKnowsHisIssuesData()
    {
        $branch = $this->createBranch();
        $publisher = $this->createPublisher();
        $book = $this->createBook($publisher, $branch);
        $user = $this->createUser();

        $this->em->persist($publisher);
        $this->em->persist($book);
        $this->em->persist($user);
        $this->em->persist($branch);
        $this->em->flush();

        $issue = new Issue();
        $issue->setUser($user);
        $issue->setBook($book);
        $issue->setStatus('issued');
        $issue->setStartDate(new \DateTime());
        $issue->setEndDate(new \DateTime('+ 7 days'));

        $this->em->persist($issue);
        $this->em->flush();
        $this->em->clear();

        $result = $this->em->getRepository(User::class)
                    ->find($book->getId());

        $this->assertInstanceOf('AppBundle\Entity\Issue', $result->getIssues()->first());
        $this->assertEquals(1, $result->getIssues()->count());
    }
}
