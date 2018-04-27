<?php

namespace ServiceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ServiceBundle extends Bundle
{
    public  function getParent(){
        return 'FOSMessageBundle';
    }
}
