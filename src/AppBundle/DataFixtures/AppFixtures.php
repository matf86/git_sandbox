<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Book;
use AppBundle\Entity\Branch;
use AppBundle\Entity\Employee;
use AppBundle\Entity\Position;
use AppBundle\Entity\Publisher;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $manager;
    /**
     * AppFixtures constructor.
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $publishers = $this->createPublishers();
        $branches = $this->createBranches();
        $positions = $this->createPositions();

        $this->createBooks($branches, $publishers);
        $this->createEmployees($branches, $positions);
        $this->createUsers();

        $this->manager->flush();
    }

    private function createPublishers() {

        $publisher_1 = new Publisher();
        $publisher_1->setName('Wydawnictwo Testowe');

        $publisher_2 = new Publisher();
        $publisher_2->setName('Polskie Wydawnictwo Testowe');

        $this->manager->persist($publisher_1);
        $this->manager->persist($publisher_2);

        return [
          $publisher_1,
          $publisher_2
        ];
    }

    private function createPositions() {
        $manager = new Position();
        $manager->setName('role_manager');

        $librarian = new Position();
        $librarian->setName('role_librarian');

        $this->manager->persist($manager);
        $this->manager->persist($librarian);

        return [
            $manager,
            $librarian
        ];
    }

    private function createBranches() {
        $branch_1 = new Branch();
        $branch_1->setName('Biblioteka Publiczna w Kórniku');
        $branch_1->setPhone('678901000');
        $branch_1->setStreet('Poznańska 23');
        $branch_1->setCity('Kórnik');
        $branch_1->setEmail('test_01@test.pl');
        $branch_1->setZip('62-035');

        $branch_2 = new Branch();
        $branch_2->setName('Biblioteka Publiczna w Bninie');
        $branch_2->setPhone('333222111');
        $branch_2->setStreet('Bnińska 23');
        $branch_2->setCity('Bnin');
        $branch_2->setEmail('test_02@test.pl');
        $branch_2->setZip('62-010');

        $this->manager->persist($branch_1);
        $this->manager->persist($branch_2);

        return [
          $branch_1,
          $branch_2
        ];
    }

    private function createBooks(array $branches, array $publishers) {

        for ($i = 0; $i < 20; $i++) {

            $publisher = ($i % 2 == 0)? $publishers[0] : $publishers[1];
            $branch = ($i % 2 == 0)? $branches[0] : $branches[1];

            $book = new Book();
            $book->setTitle('Book '.$i);
            $book->setAuthor('Author_'.$i);
            $book->setPublisher($publisher);
            $book->setBranch($branch);
            $book->setStatus(random_int(0,1));
            $book->setPublishDate(new \DateTime('- '.$i.' years'));

            $this->manager->persist($book);
        }
    }

    private function createEmployees(array $branches, array $positions) {

        $emp_manager_1 = new Employee();
        $emp_manager_1->setName('Zbigniew');
        $emp_manager_1->setSecondName('Tester');
        $emp_manager_1->setPassword('test1');
        $emp_manager_1->setEmail('test1@test.pl');
        $emp_manager_1->setBranch($branches[0]);
        $emp_manager_1->setPosition($positions[0]);

        $emp_librarian_1 = new Employee();
        $emp_librarian_1->setName('Tadeusz');
        $emp_librarian_1->setSecondName('Tester');
        $emp_librarian_1->setPassword('test2');
        $emp_librarian_1->setEmail('test2@test.pl');
        $emp_librarian_1->setBranch($branches[0]);
        $emp_librarian_1->setPosition($positions[1]);

        $emp_librarian_2 = new Employee();
        $emp_librarian_2->setName('Eryk');
        $emp_librarian_2->setSecondName('Tester');
        $emp_librarian_2->setPassword('test3');
        $emp_librarian_2->setEmail('test3@test.pl');
        $emp_librarian_2->setBranch($branches[0]);
        $emp_librarian_2->setPosition($positions[1]);

        $emp_manager_2 = new Employee();
        $emp_manager_2->setName('Mateusz');
        $emp_manager_2->setSecondName('Tester');
        $emp_manager_2->setPassword('test11');
        $emp_manager_2->setEmail('test11@test.pl');
        $emp_manager_2->setBranch($branches[1]);
        $emp_manager_2->setPosition($positions[0]);

        $emp_librarian_3 = new Employee();
        $emp_librarian_3->setName('Adam');
        $emp_librarian_3->setSecondName('Tester');
        $emp_librarian_3->setPassword('test33');
        $emp_librarian_3->setEmail('test33@test.pl');
        $emp_librarian_3->setBranch($branches[1]);
        $emp_librarian_3->setPosition($positions[1]);

        $this->manager->persist($emp_manager_1);
        $this->manager->persist($emp_manager_2);
        $this->manager->persist($emp_librarian_1);
        $this->manager->persist($emp_librarian_2);
        $this->manager->persist($emp_librarian_3);
    }

    private function createUsers() {

        $user_1 = new User();
        $user_1->setName('cyryl');
        $user_1->setSecondName('User');
        $user_1->setEmail('cyryl@test.pl');
        $user_1->setPhone('678987567');
        $user_1->setGoogleId('id11111');

        $user_2 = new User();
        $user_2->setName('krzysztof');
        $user_2->setSecondName('User');
        $user_2->setEmail('krzysztof@test.pl');
        $user_2->setPhone('234123423');
        $user_2->setGoogleId('id2222');

        $this->manager->persist($user_1);
        $this->manager->persist($user_2);
    }
}
