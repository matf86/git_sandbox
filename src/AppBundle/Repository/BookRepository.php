<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class BookRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllDistinct()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT p FROM AppBundle:Book p ORDER BY p.title DESC'
            )
            ->getResult();
    }
}
