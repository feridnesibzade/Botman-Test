<?php

namespace App\Http\Controllers\CRUD\Botman;

use App\Http\BotConversation\AgeConversation;
use App\Http\BotConversation\BaseConversation;
use App\Http\BotConversation\GameChooseConversation;
use App\Http\BotConversation\PriceConversation;
use App\Http\BotConversation\ReservationConversation;
use App\Http\Controllers\Controller;
use App\Models\BotMessages\BotMessages;
use App\Services\Model\BotmanMethods;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Cache\LaravelCache;

class BotManController extends Controller
{

    public $bot;

    public $model;
    public $service;

    public function __construct()
    {
        $this->model = new BotMessages();


        $config = [
            'user_cache_time' => 720,

            'config' => [
                'conversation_cache_time' => 720 ,
            ],
            // Your driver-specific configuration
        ];

        $this->bot = \BotMan\BotMan\BotManFactory::create($config, new LaravelCache());
//        dd(\BotMan\BotMan\BotManFactory::create($config, new LaravelCache()));
//        $this->bot = app('botman');
        $this->service = new BotmanMethods();
    }



    /**
     * Place your BotMan logic here.
     */

    public function handle()
    {
        $that = $this;
        $this->bot->startConversation(new BaseConversation());
//        $this->service->hears();

//        $this->firstQuestions();

//        $this->bot->hears('{message}', function($botman, $message) use ($that){
//            /*if(strpos($message)){
//
//            }*/
//
//            if ($message == 'hi') {
//                $that->bot->reply('hello');
//            }else{
////                $this->welcomeMessage();
//                $this->firstQuestions();
//            }
//        });
        $this->bot->listen();

    }

    /**
     * Place your BotMan logic here.
     */
    public function askName($botman)
    {
        $botman->ask('Hello! What is your Name?', function(Answer $answer) {
            $name = $answer->getText();
            $this->say('Nice to meet you '.$name);
        });
    }

    public function welcomeMessage()
    {
        $this->bot->reply("Salam Portal Games Botu xidmətinizdədir. Sizi maraqlandıran suallarınıza ən qısa zamanda cavab tapacaqsınız.");
    }

    public function fallback(){
        $this->firstQuestions();
    }


    public function firstQuestions()
    {
        $row = $this->model->where('type', 1)->first();

        $this->service->question($row);

        /*$question = Question::create('Aşağıda qeyd olunan başlıqlardan sizə maraqlı olanı seçin')->addButtons([
            Button::create('Yaş həddi var?')->value('age'),
            Button::create('Qiymətlər nədir?')->value('price'),
            Button::create('Rezerv necə etməli?')->value('reserve'),
            Button::create('Ən yaxşı oyun hansıdır?')->value('game-choose'),
        ]);

        $this->bot->ask($question, function ($answer){
            if($answer == 'age'){
                $this->bot->startConversation(new AgeConversation());
            }elseif ($answer == 'price'){
                $this->bot->startConversation(new PriceConversation());
            }elseif ($answer == 'reserve'){
                $this->bot->startConversation(new ReservationConversation());
            }elseif ($answer == 'game-choose'){
                $this->bot->startConversation(new GameChooseConversation());
            }
        });*/
    }

}
