<?php
namespace App\Http\BotConversation;

use App\Http\Controllers\BotManController;
use App\Models\BotMessages\BotMessages;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Session;

class BaseConversation extends Conversation{

    public $botMessages;
    public $question;
//
    public function __construct()
    {
        $this->botMessages = new BotMessages();
        $this->question = $this->botMessages->where('type', 1)->where('value', 'first_question')->first();
    }

    public function question($row)
    {
        foreach ($row->buttons as $button) {
            $buttons[] = Button::create($button['name'])->value($button['id']);
        }

        $question = Question::create($row->text)->addButtons($buttons);

        $that = $this;
        $this->question = false;
        $this->ask($question, function (Answer $answer) use ($that){
            $nextAnswer = $that->botMessages->where('value', $answer)->where('deleted_at', null)->first();
            if($nextAnswer['type'] == 1){
//                Session::put('nextAnswer', $nextAnswer);
                $that->question = $nextAnswer;
//                dd($nextAnswer);
//                $that->question($nextAnswer);
            }elseif($nextAnswer['type'] == 2){
                $this->say($nextAnswer->text);
            }
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->question($this->question);
    }
}
