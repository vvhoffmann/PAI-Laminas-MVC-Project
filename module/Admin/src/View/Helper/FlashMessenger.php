<?php

namespace Admin\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class FlashMessenger extends AbstractHelper
{
    public function __invoke()
    {
        $fm = $this->view->flashMessenger();
        $fm->setMessageOpenFormat('<div%s><ul class="my-0"><li>')
            ->setMessageSeparatorString('</li><li>')
            ->setMessageCloseString('</li></ul></div>');

        $wynik = '';
        $wynik .= $fm->render('error', ['alert', 'alert-danger']);
        $wynik .= $fm->render('info', ['alert', 'alert-info']);
        $wynik .= $fm->render('default', ['alert', 'alert-warning']);
        $wynik .= $fm->render('success', ['alert', 'alert-success']);

        return $wynik;
    }
}
