<?php

namespace AppBundle\Service;

use AppBundle\Entity\Service;
use Doctrine\ORM\EntityManager;

class RecurrencePaymentService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function changeStatusOfRecurrencePayments()
    {
        //Check last day of month
        if(date("t") == date("d")){
            $em = $this->em;

            $recurrenceServices = $em->getRepository("AppBundle:Service")->findBy(['isRecurrence' => true]);

            /** @var Service $service */
            foreach ($recurrenceServices as $service) {
                $service->setIsActive(false);
                $em->persist($service);
            }

            $em->flush();
        }
    }
}
