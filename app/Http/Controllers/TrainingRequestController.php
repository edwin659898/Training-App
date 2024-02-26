<?php

namespace App\Http\Controllers;

use App\Models\TrainingRequest;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TrainingRequestController extends Controller
{
    public function submitForReview(TrainingRequest $record)
    {

        $record->update(['status' => request()->data]);

        Notification::make()
            ->title('Request status updated')
            ->success()
            ->send();

        if (request()->data == 'in review') {
            $data = [
                'intro'  => 'Dear HOD,',
                'content'   => 'Training request from '.auth()->user()->name.'for approval',
                'name' => 'HOD '.auth()->user()->department->name,
                'email' => auth()->user()->department->HOD_email,
                'subject'  => 'Training Request Approval'
            ];
            Mail::send('emails.order', $data, function($message) use ($data) {
                $message->to($data['email'], $data['name'])
                        // ->cc($trainee->user->personal_email)
                        ->cc('lydia@betterglobeforestry.com')
                        ->subject($data['subject']);
            });

            return redirect('/admin/training-requests');

        } elseif (request()->data == 'training done') {

            return redirect('/admin/approved-training-requests');

        } else {

            return redirect('/admin/trainings-review-and-approval');

        }
    }
}
