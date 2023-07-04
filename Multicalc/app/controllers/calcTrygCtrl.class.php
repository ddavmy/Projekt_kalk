<?php

namespace app\controllers;

use core\App;
use core\RoleUtils;
use core\Route;
use core\Router;
use core\SessionUtils;
use core\Utils;
use core\ParamUtils;
use app\forms\calcForm;

class calcTrygCtrl {

    private $form;
    private $sin;
    private $cos;
    private $tg;
    private $ctg;
    private $username;
    private $records;

    public function __construct() {
        $this->form = new calcForm();
    }

    public function validate() {
        $this->form->alfa = ParamUtils::getFromRequest('alfa', true);

        if (App::getMessages()->isError())
            return false;

        if($this->form->alfa < 0) {
            Utils::addErrorMessage('Kąt alfa musi być dodatni!');
        } elseif($this->form->alfa > 90) {
            Utils::addErrorMessage('Kąt alfa nie może przekraczać 90°');
        } elseif(!is_numeric($this->form->alfa)) {
            Utils::addErrorMessage('Podana wartość nie jest liczbą!');
        }

        return !App::getMessages()->isError();
    }

    public function validateEdit() {
        $this->form->id = ParamUtils::getFromCleanURL(1, true, 'Błędne wywołanie aplikacji');
        return !App::getMessages()->isError();
    }

    public function action_calcTrygDelete() {
        if ($this->validateEdit()) {

            try {
                App::getDB()->delete("calc__tryg", [
                    "id" => $this->form->id
                ]);
                Utils::addInfoMessage('Pomyślnie usunięto rekord');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił błąd podczas usuwania rekordu');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }
        $this->wynikList();
    }

    public function wynikSave() {
        if ($this->validate()) {
            $username = SessionUtils::load('login', true);
            try {
                App::getDB()->insert("calc__tryg", [
                    "alfa"=>$this->form->alfa,
                    "sin"=>$this->sin,
                    "cos"=>$this->cos,
                    "tg"=>$this->tg,
                    "ctg"=>$this->ctg,
                    "user_id"=>$username
                ]);
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }

        }
        $this->wynikList();
    }

    public function wynikList() {
        $username = SessionUtils::load('login', true);
        $role = SessionUtils::load('role', true);
        if($role == 1){
            $this->records = App::getDB()->select("calc__tryg", [
                "[><]uzytkownicy"=>["user_id"=>"user_id"]
            ], [
                "calc__tryg.id",
                "calc__tryg.alfa",
                "calc__tryg.sin",
                "calc__tryg.cos",
                "calc__tryg.tg",
                "calc__tryg.ctg",
                "uzytkownicy.username"
            ], [
                "LIMIT"=>10,
                "ORDER"=>[
                    "id"=>"DESC"
                ]
            ]);
        } else {
            $this->records = App::getDB()->select("calc__tryg", [
                "id",
                "alfa",
                "sin",
                "cos",
                "tg",
                "ctg"
            ], [
                "user_id"=>$username,
                "LIMIT"=>5,
                "ORDER"=>[
                    "id"=>"DESC"
                ]
            ]);
        }
        $this->generateView();
    }

    public function action_calcTrygShow(){
        $this->wynikList();
	}

    public function action_calcTrygCompute(){
        if ($this->validate()) {
            
            $this->form->alfa = floatval($this->form->alfa);
            Utils::addInfoMessage('Parametr poprawny, wykonano obliczenia');
            $this->sin = round(sin(deg2rad($this->form->alfa)),7);
            Utils::addWynikMessage('sin = '.$this->sin);
            $this->cos = round(cos(deg2rad($this->form->alfa)),7);
            Utils::addWynikMessage('cos = '.$this->cos);
            $this->tg = round(tan(deg2rad($this->form->alfa)),7);
            Utils::addWynikMessage('tg = '.$this->tg);
            if($this->form->alfa == 0)  {
                $this->ctg = "brak";
            } else {
                $this->ctg = round(1/tan(deg2rad($this->form->alfa)),7);
            }
            Utils::addWynikMessage('ctg = '.$this->ctg);

        }
        $this->wynikSave();
    }

    public function generateView(){
        
        App::getSmarty()->assign('user',SessionUtils::loadObject('user', true));

        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('sin',$this->sin);
        App::getSmarty()->assign('cos',$this->cos);
        App::getSmarty()->assign('tg',$this->tg);
        App::getSmarty()->assign('ctg',$this->ctg);
        App::getSmarty()->assign('records',$this->records);
        
        App::getSmarty()->display('calcTryg.tpl');
    }
    public function log($data) {
        $output = $data;
        if (is_array($output))
        $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
}