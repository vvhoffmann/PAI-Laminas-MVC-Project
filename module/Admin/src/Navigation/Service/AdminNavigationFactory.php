<?php

namespace Admin\Navigation\Service;

use Laminas\Navigation\Service\DefaultNavigationFactory;

class AdminNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'admin';
    }
}
