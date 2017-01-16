<?php

namespace WebModule;

use App\Model;
use Nette\Application\UI;
class PagePresenter extends BasePresenter
{

    /************************************************************************************************************z*v***/
    /********** COMPONENTS **********/

    protected function createComponentRegistrationForm()
    {
        $form = new UI\Form();
        $form->addText('name', 'Jméno:')
            ->setRequired('Zadejte jméno');
        $form->addText('email', 'E-mail:')
            ->addRule(UI\Form::EMAIL, 'E-mail není ve správném tvaru');
        $form->addText('phone', 'Telefon:')
            ->setRequired('Zadejte telefon');;
        $form->addSubmit('submit', 'Pokracovat');
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }

    // volá se po úspěšném odeslání formuláře
    public function registrationFormSucceeded(UI\Form $form)
    {
        $this->flashMessage('Byl jste úspěšně registrován.');
//        $this->redirect('Homepage:');
    }
}