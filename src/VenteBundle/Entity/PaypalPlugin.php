<?php
/**
 * Created by PhpStorm.
 * User: bn-sk
 * Date: 21/02/2018
 * Time: 20:53
 */

namespace VenteBundle\Entity;


use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;

class PaypalPlugin extends AbstractPlugin
{
    public function processes($name)
    {
        return 'paypal' === $name;
    }
}