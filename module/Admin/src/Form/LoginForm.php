<?php

namespace Admin\Form;

use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct('logowanie');

        $this->setAttribute('method', 'post');
        $this->add([
            'name' => 'login',
            'type' => 'Text',
            'options' => [
                'label' => 'Login',
            ],
        ]);
        $this->add([
            'name' => 'haslo',
            'type' => 'Password',
            'options' => [
                'label' => 'Hasło',
            ],
        ]);
        $this->add([
            'name' => 'zaloguj',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Zaloguj',
            ],
        ]);
    }
}
