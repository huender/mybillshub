<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Security;

class SearchService
{
    private $em;
    private $security;

    public function __construct(EntityManager $em, AuthorizationChecker $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function findByTerm($term){
        $results = [];
        $sql = "SELECT * FROM category WHERE LOWER(name) like LOWER('%$term%') or lower(observation) like LOWER('%$term%');";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $results['categories'] = $stmt->fetchAll();

        $sql = "SELECT * FROM service WHERE LOWER(name) like LOWER('%$term%') or lower(url) like LOWER('%$term%');";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $results['services'] = $stmt->fetchAll();

        if($this->security->isGranted("ROLE_SUPER_ADMIN")){
            $sql = "SELECT * FROM app_users WHERE LOWER(username) like LOWER('%$term%') or lower(email) like LOWER('%$term%');";
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
            $results['users'] = $stmt->fetchAll();
        }

        return $results;
    }
}
