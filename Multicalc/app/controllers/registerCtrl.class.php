<?php

namespace app\controllers;
error_reporting(0);
use app\transfer\User;
use core\App;
use core\Utils;
use core\RoleUtils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\LoginForm;

class registerCtrl {

    private $form;
    private $password;

    public function __construct() {
        //stworzenie potrzebnych obiektów
        $this->form = new LoginForm();
    }

    public function validate() {
        $this->form->login = ParamUtils::getFromRequest('login');
        $this->form->email = ParamUtils::getFromRequest('email');
        $this->form->pass = ParamUtils::getFromRequest('pass');
        
        // sprawdzenie, czy potrzebne wartości zostały przekazane
        if (empty($this->form->login)) {
            Utils::addErrorMessage('Nie podano loginu');
        } else if($this->accountList()) {
            if (empty($this->form->email)) {
                Utils::addErrorMessage('Nie podano maila');
            }
            if (empty($this->form->pass)) {
                Utils::addErrorMessage('Nie podano hasła');
            }
        }
        
        return !App::getMessages()->isError();
    }

    public function accountList() {
        try {
            $records = App::getDB()->count("uzytkownicy", [
                "username" => $this->form->login
        ]);
        } catch (\PDOException $e) {
            Utils::addErrorMessage('Wystąpił błąd podczas pobierania rekordów');
            if (App::getConf()->debug)
                Utils::addErrorMessage($e->getMessage());
        }
        if($records > 0){
            Utils::addErrorMessage("Istnieje już taki użytkownik!");
            App::getRouter()->forwardTo('registerShow');
        }
        return !App::getMessages()->isError();
    }

    public function action_register() {
        // 1. Walidacja danych formularza (z pobraniem)
        if ($this->validate()) {
            // 2. Zapis danych w bazie
            try {
                App::getDB()->insert("uzytkownicy", [
                   "username" => $this->form->login, 
                   "password" => password_hash($this->form->pass, PASSWORD_DEFAULT),
                   "email" => $this->form->email
            ]);
            Utils::addInfoMessage('Pomyślnie zarejestrowano');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas rejestracji');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }
        App::getRouter()->forwardTo("loginShow");
    }

    public function action_registerShow() {
        $this->generateView();
    }

    public function generateView() {
        App::getSmarty()->assign('user',SessionUtils::loadObject('user', true));
        App::getSmarty()->assign('form', $this->form); // dane formularza do widoku
        App::getSmarty()->display('register.tpl');
    }

}
