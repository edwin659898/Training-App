<?php

namespace App\Console\Commands;

use App\Models\Trainer;
use App\Models\Training;
use App\Models\TrainingRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;

class generateCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:cert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Individual Certificates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $concluded_trainings = TrainingRequest::where('status','completed')->where('sent_pdf','no')->get();
        foreach($concluded_trainings as $training){
            foreach($training->trainees as $trainee){

                $file = public_path("storage/sample.pdf");
                $outputFilePath = public_path("storage/".$trainee->user->name."-".$training->id."-certificate.pdf");

                $pdf = new FPDI;

                $pdf->addPage('L');
                $pagecount = $pdf->setSourceFile($file);;
                $tplIdx = $pdf->importPage(1); 
                $pdf->useTemplate($tplIdx); 
                $pdf->SetFont('Times','I',20);
                $pdf->SetTextColor(0,0,0); 
                $counted_names = Str::of($trainee->user->name)->wordCount();  
                if($counted_names < 3){
                    $pdf->SetXY(120, 83);
                }else{
                    $pdf->SetXY(105, 83); 
                }
                $pdf->Write(0, $trainee->user->name); 

                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(90, 104);
                foreach ($training->trainings as $recordID){
                $pdf->Write(0, Training::find($recordID)->name); 
                }
        
                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(122, 114.4); 
                $pdf->Write(0, substr($training->end_time, 0, -9)); 
        
                $pdf->SetFont('Times','I',20);
                $pdf->SetXY(77, 134.5); 
                $pdf->Write(0, $training->venue); 
        
                $firstNonNullTrainer = $training->trainerss->first(function ($trainer) {
                    return $trainer->signature != null;
                });

                if($firstNonNullTrainer == null){
                    $firstNonNullTrainer = Trainer::findOrFail(6);
                }

                $pdf->SetFont('Times','I',20);
                $pdf->SetXY(167, 134.5); 
                $pdf->Write(0, $firstNonNullTrainer->organization); 
        
                $pdf->SetFont('Times','I',15);
                $pdf->SetXY(33, 170);
                $pdf->Write(0, $firstNonNullTrainer->name); 
                //$pdf->Write(0, "Michael Njogu - ".$user->job_title); 
        
                $image = public_path("storage/".$firstNonNullTrainer->signature);
                $pdf->SetFont('Times','I',15);
                $pdf->Image($image, 165, 170, 20, 15,'PNG');

                $pdf->SetFont('Times','I',10);
                $pdf->SetXY(125, 185); 
                $pdf->Write(0, '0' . $training->id . $trainee->user->id);
                $pdf->Output();

                $data = [
                    'intro'  => 'Dear '.$trainee->user->name.',',
                    'content'   => 'Congratulations on completing your training. Kindly find attached your certificate.',
                    'name' => $trainee->user->name,
                    'email' => $trainee->user->personal_email,
                    'subject'  => $trainee->user->name.' Certificate of Participation'
                ];
                Mail::send('emails.order', $data, function($message) use ($data, $pdf,$outputFilePath,$trainee) {
                    $message->to($data['email'], $data['name'])
                            ->cc('lydia@betterglobeforestry.com')
                            ->subject($data['subject'])
                            ->attachData($pdf->Output($outputFilePath, 'S'), $trainee->user->name." Certificate of participation.pdf");
                });

            }

            $training->update(['sent_pdf'=>'yes']);
            
        }
    }
}
