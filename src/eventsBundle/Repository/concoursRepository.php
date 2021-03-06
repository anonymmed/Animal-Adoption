<?php

namespace eventsBundle\Repository;

/**
 * concoursRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class concoursRepository extends \Doctrine\ORM\EntityRepository
{
    public function rechercheAjax($description)
    {
        $q=$this->getEntityManager()->createQuery("select v from eventsBundle:concours v where 
            v.description=:description   ")
            ->setParameter('description',$description);
        return $q->getResult();
    }

    public function recherchedate(){
        $q=$this->getEntityManager()
            ->createQuery("select v from eventsBundle:concours v where 
            v.date > CURRENT_DATE() ");
        return $q->getResult();
    }



}
