<?php

namespace AdoptionBundle\Repository;

/**
 * AnimalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnimalRepository extends \Doctrine\ORM\EntityRepository
{
    public function rechercheAnimal($nom)
    {
        $q=$this->getEntityManager()->createQuery("select v from AdoptionBundle:Animal v where 
            v.nom=:nom ")
            ->setParameter('nom',$nom);
        return $q->getResult();
    }

    public function rechercheById($id)
    {
        $q=$this->getEntityManager()->createQuery("select a from AdoptionBundle:Animal a where a.id=:id")
        ->setParameter('id',$id);
        return $q->getResult();
    }
    public function updateEtat($id)
    {
        $q=$this->getEntityManager()->createQuery("UPDATE AdoptionBundle:Animal u SET u.etat = '1' WHERE u.id =:id ")
            ->setParameter('id',$id);
    }
    public function rechercheNonAdopte()
    {
        $q=$this->getEntityManager()->createQuery("select v from AdoptionBundle:Animal v where 
            v.etat='nonadopte' ");
        return $q->getResult();
    }
    public function rechercheAjax($mot)
    {
        $q=$this->getEntityManager()->createQuery("select c from AdoptionBundle:Animal c where 
                c.nom LIKE :mot OR c.race LIKE :mot OR c.sexe LIKE :mot OR c.age LIKE :mot")
            ->setParameter('mot',"%$mot%");
        return $q->getResult();

    }

    public function findAnimal($espece, $age_animal, $sexe_animal, $region, $taille){
        $query = $this->createQueryBuilder('f')
            ->where('f.espece like :espece')
            ->setParameter('espece', $espece.'%')
            ->andWhere('f.etat like :nonadopte')
            ->setParameter('nonadopte', "nonadopte")
            ->andWhere('f.region like :region')
            ->setParameter('region', $region.'%')
            ->andWhere('f.taille like :taille')
            ->setParameter('taille', $taille.'%')
            ->andWhere('f.age like :age')
            ->setParameter('age', $age_animal.'%')
            ->andWhere('f.sexe like :sexe_animal')
            ->setParameter('sexe_animal', $sexe_animal.'%')
            ->orderBy('f.race', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

}
