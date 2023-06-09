<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\CalcForm;
use app\forms\ResultForm;

class calcDeltaCtrl {

    private $form;
    private $result;

    public function __construct() {
        $this->form = new CalcForm();
        $this->result = new ResultForm();
    }

    public function getParams() {
        $user = SessionUtils::loadObject('user', true);
        $this->form->user_id = $user->user_id;
        $this->form->login = $user->login;
        $this->form->role = $user->role;
        
        $this->form->calcName = ParamUtils::getFromCleanURL(0, true, null, null);
        $this->form->calcID = App::getDB()->get("calcs", "calc_id", [
            "action" => $this->form->calcName
        ]);
    }

    public function validate() {

        $this->form->dlugoscA = ParamUtils::getFromRequest('dlugoscA', true);
        $this->form->dlugoscB = ParamUtils::getFromRequest('dlugoscB', true);
        $this->form->dlugoscC = ParamUtils::getFromRequest('dlugoscC', true);

        if (App::getMessages()->isError()) {
            return false;
        }
            
        if($this->form->dlugoscA == 0) {
            App::getMessages()->addMessage(new \core\Message("To nie jest funkcja kwadratowa!", \core\Message::ERROR));
        }
        if($this->form->dlugoscA == "") {
            App::getMessages()->addMessage(new \core\Message("Nie podano a", \core\Message::ERROR));
        } elseif(!is_numeric($this->form->dlugoscA)) {
            App::getMessages()->addMessage(new \core\Message("Pierwsza wartość nie jest liczbą!", \core\Message::ERROR));
        }
        if($this->form->dlugoscB == "") {
            App::getMessages()->addMessage(new \core\Message("Nie podano b", \core\Message::ERROR));
        } elseif(!is_numeric($this->form->dlugoscB)) {
            App::getMessages()->addMessage(new \core\Message("Druga wartość nie jest liczbą!", \core\Message::ERROR));
        }
        if($this->form->dlugoscC == "") {
            App::getMessages()->addMessage(new \core\Message("Nie podano c", \core\Message::ERROR));
        } elseif(!is_numeric($this->form->dlugoscC)) {
            App::getMessages()->addMessage(new \core\Message("Trzecia wartość nie jest liczbą!", \core\Message::ERROR));
        }

        $this->getParams();
        return !App::getMessages()->isError();
    }

    public function validateEdit() {
        $this->form->id = ParamUtils::getFromCleanURL(1, true, 'Błędne wywołanie aplikacji');
        return !App::getMessages()->isError();
    }

    public function DeltaDelete() {
        if ($this->validateEdit()) {
            try {
                App::getDB()->delete("calc", [
                    "id" => $this->form->id
                ]);
                Utils::addInfoMessage('Pomyślnie usunięto rekord');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił błąd podczas usuwania rekordu');
                if (App::getConf()->debug){
                    Utils::addErrorMessage($e->getMessage());
                }
            }
        }
        $this->WynikList();
    }

    public function wynikSave() {
        if ($this->validate()) {
            try {
                App::getDB()->insert("calc", [
                    "dlugoscA" => $this->form->dlugoscA,
                    "dlugoscB" => $this->form->dlugoscB,
                    "dlugoscC" => $this->form->dlugoscC,
                    "wynikA" => $this->result->wynikA,
					"wynikB" => $this->result->wynikB,
					"wynikC" => $this->result->wynikC,
                    "calc_id"=>$this->form->calcID,
					"user_id" => $this->form->user_id
                ]);
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
                if (App::getConf()->debug) {
                    Utils::addErrorMessage($e->getMessage());
                }
            }
        }
        $this->WynikList();
    }

    public function wynikList() {
        $this->getParams();
        if($this->form->role == "admin"){
            $this->result->records = App::getDB()->select("calc", [
                "[><]uzytkownicy"=>["user_id"=>"user_id"]
            ], [
                "calc.id",
                "calc.dlugoscA",
                "calc.dlugoscB",
                "calc.dlugoscC",
                "calc.wynikA",
                "calc.wynikB",
                "calc.wynikC",
                "uzytkownicy.username"
            ], [
                "calc.calc_id"=>$this->form->calcID,
                "LIMIT"=>10,
                "ORDER"=>[
                    "calc.id"=>"DESC"
                ]
            ]);
        } 
        else {
            $this->result->records = App::getDB()->select("calc", [
                "id",
                "dlugoscA",
                "dlugoscB",
                "dlugoscC",
                "wynikA",
                "wynikB",
                "wynikC"
            ], [
                "calc_id"=>$this->form->calcID,
                "user_id"=>$this->form->user_id,
                "LIMIT"=>5,
                "ORDER"=>[
                    "id"=>"DESC"
                ]
            ]);
        }
        $this->generateView();
    }

    public function action_Delta(){
        $this->getParams();
        $submit = ParamUtils::getFromRequest('submit', true);
        App::getMessages()->clear();
        if($submit == "Oblicz") {
            $this->DeltaCompute();
        }else if($submit == "Usuń" && $this->form->role == "admin") {
            $this->DeltaDelete();
        }else{
            $this->WynikList();
        }
	}

    public function DeltaCompute(){
        
        if ($this->validate()) {

            $this->form->dlugoscA = floatval($this->form->dlugoscA);
            $this->form->dlugoscB = floatval($this->form->dlugoscB);
            $this->form->dlugoscC = floatval($this->form->dlugoscC);
            $this->result->wynikB = 'brak';
            $this->result->wynikC = 'brak';
            App::getMessages()->addMessage(new \core\Message("Parametry poprawne,wykonano obliczenia", \core\Message::INFO));
            
            $this->result->wynikA = round(pow($this->form->dlugoscB, 2) - 4 * $this->form->dlugoscA * $this->form->dlugoscC, 3);
            App::getMessages()->addMessage(new \core\Message("Δ = ".$this->result->wynikA, \core\Message::INFO));

                if($this->result->wynikA == 0) {
                    $this->result->wynikB = -($this->form->dlugoscB) / (2 * $this->form->dlugoscA);
                    if($this->result->wynikB == -0) {$this->result->wynikB = abs($this->result->wynikB);}
                    App::getMessages()->addMessage(new \core\Message("x<sub>0</sub> = ".$this->result->wynikB, \core\Message::INFO));
                }
                else if($this->result->wynikA < 0) {
                    App::getMessages()->addMessage(new \core\Message("Delta ujemna, brak pierwiastków.", \core\Message::INFO));
                }else {
                    $this->result->wynikB = round((-$this->form->dlugoscB + sqrt($this->result->wynikA)) / (2 * $this->form->dlugoscA), 3);
                    if($this->result->wynikB == -0) {$this->result->wynikB = abs($this->result->wynikB);}
                    App::getMessages()->addMessage(new \core\Message("x<sub>1</sub> = ".$this->result->wynikB, \core\Message::INFO));

                    $this->result->wynikC = round((-$this->form->dlugoscB - sqrt($this->result->wynikA)) / (2 * $this->form->dlugoscA), 3);
                    if($this->result->wynikC == -0) {$this->result->wynikC = abs($this->result->wynikC);}
                    App::getMessages()->addMessage(new \core\Message("x<sub>2</sub> = ".$this->result->wynikC, \core\Message::INFO));
                }
        }
        $this->wynikSave();
    }

    public function generateView(){

        App::getSmarty()->assign('user',SessionUtils::loadObject('user', true));
        
        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('records',$this->result->records);
        
        App::getSmarty()->display('calcDelta.tpl');
    }
    public function log($data) {
        $output = $data;
        if (is_array($output))
        $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
}
