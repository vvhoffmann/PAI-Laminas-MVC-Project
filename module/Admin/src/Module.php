<?php

namespace Admin;

use Admin\Controller\LogowanieController;
use Admin\Service\AuthService;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function (MvcEvent $e) {
            $authSrv = $e->getApplication()->getServiceManager()->get(AuthService::class);

            // sprawdzenie, czy użytkownik jest zalogowany
            if (
                $e->getRouteMatch()->getParam('controller') != LogowanieController::class
                && !$authSrv->loggedIn()
            ) {
                $urlLogin = $e->getRouter()->assemble([], ['name' => 'logowanie']);
                $response = $e->getResponse();

                $response->getHeaders()->addHeaderLine('Location', $urlLogin);
                $response->setStatusCode(302);
                $response->sendHeaders();
            } else {
                // zmiana domyślnego layoutu dla panelu adminustracyjnego
                $controller = $e->getTarget();
                $controller->layout('layout/administracja');
            }
        }, 1000);
    }
}
