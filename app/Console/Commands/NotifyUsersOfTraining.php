<?php

namespace App\Console\Commands;

use App\Models\TrainingRequest;
use Illuminate\Console\Command;
use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;

class NotifyUsersOfTraining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upcoming:training';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Users of a scheduled training';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $trainings = TrainingRequest::where('notified_user','no')->where('start_time','!=',null)->get();
        foreach($trainings as $training){

            $to = Carbon::createFromFormat('Y-m-d H:i:s', $training->start_time);
            $from = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::tomorrow()->toDateTimeString());

            if($to->diffInMinutes($from) < 1440){
                foreach($training->trainees as $user){
                    if ($user->user->country == 'KE') {
                        $username = env('USERNAME_KE');
                        $apiKey   = env('PASS_KE');
                        $from = env('SENDER_KE');
                    } else {
                        $username = env('USERNAME_UG');
                        $apiKey   = env('PASS_UG');
                        $from = env('SENDER_UG');
                    }
    
                    $AT  = new AfricasTalking($username, $apiKey);
                    $sms = $AT->sms();
                    $result = $sms->send([
                        'from' => $from,
                        'to'      => $user->user->phone_number,
                        'message' => 'Dear ' . $user->user->name . ', you have a BGF training scheduled tomorrow ' . $training->start_time ,
                    ]);
                }
            }

            $training->update(['notified_user'=>'yes']);

        }
    }
}
