<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Exam;
use AppBundle\Entity\IndividualFaunaOccurrence;
use AppBundle\Entity\Member;
use AppBundle\Utils\Util;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Common\Collections\Collection;
use Zend\Stdlib\DateTime;

class AppExtension extends \Twig_Extension
{
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('instanceof', array($this, 'isInstanceof')),
        ];
    }

    /**
     * @param $var
     * @param $instance
     *
     * @return bool
     */
    public function isInstanceof($var, $instance)
    {
        return  $var instanceof $instance;
    }

    public function getName()
    {
        return 'app_extension';
    }
}
