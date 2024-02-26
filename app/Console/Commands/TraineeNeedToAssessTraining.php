<?php

namespace App\Console\Commands;

use App\Models\TrainingRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TraineeNeedToAssessTraining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trainee:assessmentNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users they need to assess the training';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $trainings = TrainingRequest::where('assesment_notification_sent','no')->where('status','=','training done')->get();
        foreach($trainings as $training){

            foreach($training->trainees as $trainee){

                $data = [
                    'intro'  => 'Dear '.$trainee->user->name.',',
                    'content'   => 'Congratulations on completing your training. We request you kindly login to the system and rate the training impact.',
                    'name' => $trainee->user->name,
                    'email' => $trainee->user->email,
                    'subject'  => 'Training Assessment Notification'
                ];
                Mail::send('emails.order', $data, function($message) use ($data,$trainee) {
                    $message->to($data['email'], $data['name'])
                            ->cc($trainee->user->personal_email)
                            ->subject($data['subject']);
                });

            }

            $training->update(['assesment_notification_sent'=>'yes']);

        }
    }
}
