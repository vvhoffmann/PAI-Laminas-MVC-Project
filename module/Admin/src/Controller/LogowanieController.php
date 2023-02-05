<?php

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Service\AuthService;
use Laminas\Mvc\Controller\AbstractActionController;

class LogowanieController extends AbstractActionController
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function zalogujAction()
    {
        $request = $this->getRequest();
        $form = new LoginForm();

        if ($request->isPost()) {
            $dane = $request->getPost();

            if ($this->authService->auth($dane->login, $dane->haslo)) {
                $this->flashMessenger()->addSuccessMessage('Użytkownik został pomyślnie zalogowany.');

                return $this->redirect()->toRoute('admin');
            } else {
                $this->flashMessenger()->addErrorMessage('Wprowadzono niepoprawny login lub hasło.');

                return $this->redirect()->toRoute('logowanie');
            }
        }

        $this->layout('layout/logowanie');

        return [
            'form' => $form,
        ];
    }

    public function wylogujAction()
    {
        $this->authService->clear();
        $this->flashMessenger()->addSuccessMessage('Użytkownik został pomyślnie wylogowany.');

        return $this->redirect()->toRoute('logowanie');
    }
}
