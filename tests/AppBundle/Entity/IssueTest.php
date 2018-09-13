<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Book;
use AppBundle\Entity\Branch;
use AppBundle\Entity\Employee;
use AppBundle\Entity\Issue;
use AppBundle\Entity\Position;
use AppBundle\Entity\Publisher;
use AppBundle\Entity\User;
use Faker\Provider\cs_CZ\DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\AppBundle\DatabasePrimer;


class IssueTest extends KernelTestCase
{
    private $em;

    public function setUp()
    {
        self::bootKernel();

        DatabasePrimer::prime(self::$kernel);

        $this->em = self::$kernel->getContainer()->get('doctrine')
            ->getManager();
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

    private function createPublisher() {

        $publisher = new Publisher();
        $publisher->setName('Wydawnictwo Testowe');

        return $publisher;
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

    public function testIssueRelationToUser()
    {
        $publisher = $this->createPublisher();
        $branch = $this->createBranch();
        $book = $this->createBook($publisher, $branch);

        $user = new User();
        $user->setName('Mateusz');
        $user->setSecondName('Fludra');
        $user->setPhone('784552322');
        $user->setEmail('mateusz@testy.pl');
        $user->setGoogleId('id-11111');

        $this->em->persist($publisher);
        $this->em->persist($branch);
        $this->em->persist($book);
        $this->em->persist($user);

        $issue = new Issue();
        $issue->setUser($user);
        $issue->setBook($book);
        $issue->setStatus('issued');
        $issue->setStartDate(new \DateTime());
        $issue->setEndDate(new \DateTime('+ 7 days'));

        $this->em->persist($issue);
        $this->em->flush();

        $result = $this->em->getRepository(Issue::class)
                            ->find($issue->getId());

        $this->assertInstanceOf('AppBundle\Entity\User', $result->getUser());
        $this->assertInstanceOf('AppBundle\Entity\Book', $result->getBook());
    }

}
