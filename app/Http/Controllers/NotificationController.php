<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Models\Student;
use App\Models\Curriculum\ClassSubjectProgress;
use DB;
use Carbon;
use App\Utils\Util;
use App\Utils\NotificationUtil;
use GuzzleHttp\Client;
use App\Jobs\WhatsAppGroup;
use App\Utils\StudentUtil;

class NotificationController extends Controller
{
    public function __construct(Util $util, NotificationUtil $notificationUtil, StudentUtil $studentUtil)
    {
        $this->util= $util;
        $this->notificationUtil= $notificationUtil;
        $this->studentUtil = $studentUtil;
    }
    public function lessonProgressSendCreate()
    {
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, true);
        return view('whatsapp.lesson_progress_create')->with(compact('campuses', 'sessions'));
    }
    public function lessonProgressSendPost(Request $request)
    {
        try {
            $input=$request->only(['campus_id', 'date','send_through']);
            //dd($input);
            $class_sections =ClassSection::with(['classes'])->where('campus_id', $input['campus_id'])->get();
            foreach ($class_sections as $section) {
                $date=$this->notificationUtil->uf_date($input['date']);
                $lesson_progress=ClassSubjectProgress::with(['subject','lesson','chapter'])->where('campus_id', $input['campus_id'])
                ->where('class_id', $section->class_id)
                ->where('class_section_id', $section->id)
                ->WhereDate('complete_date', $date)->get();

                if ($lesson_progress->count()>0) {
                    $sms_body="Today's Lesson Read For Class 55" .$section->classes->title . PHP_EOL;
                    foreach ($lesson_progress as $key => $value) {
                        $sms_body .= 'Subject Name:'.$value->subject->name . PHP_EOL.'Chapter:'. $value->chapter->chapter_name . PHP_EOL.'Lesson:'.$value->lesson->name. PHP_EOL;
                        if (!empty($value->lesson->description)) {
                            $sms_body .= 'Lesson Description:'.$value->lesson->description . PHP_EOL;
                        }
                    }
                    if ($input['send_through']=='whatsapp_group') {
                        if (!empty($section->whatsapp_group_name)) {
                            WhatsAppGroup::dispatch($section->whatsapp_group_name, $sms_body);
                        }
                    } elseif ($input['send_through']=='single_wise_sms') {
                        $this->student_send($section, $sms_body);
                    }
                }
            }
            $output = ['success' => true,
            'msg' => __("english.added_success")
    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return redirect()->back()->with('status', $output);
    }
    public function student_send($section, $sms_body)
    {
        # code...
        $students=Student::where('campus_id', $section->campus_id)->where('current_class_id', $section->class_id)
        ->where('current_class_section_id', $section->id)->where('status', 'active')->get();
        //dd($students);
        foreach ($students as $std) {
            $student= Student::where('id', $std->id)->first();

            $response=$this->notificationUtil->SendNotification(null, $student, null, $sms_body);
        }

        return true;
    }


    public function FeeRemainderCreate()
    {
        $campuses=Campus::forDropdown();
        return view('notification.fee_remainder.fee_remainder_create')->with(compact('campuses'));
    }


    public function FeeRemainderPost(Request $request)
    {
        try {
            $input = $request->only(['campus_id','class_id']);
             $addSecond = 0;
            if (!empty($input['class_id'])) {
                $students = Student::where('campus_id', $input['campus_id'])
                ->where('status', 'active')
                ->where('current_class_id', $input['class_id'])
                ->get();
                foreach ($students as $std) {
                    $student = $this->studentUtil->getStudentDue($std->id);
                    if ($student->total_due >0) {
                        $addSecond =  $addSecond +30;

                        $this->notificationUtil->autoSendStudentPaymentNotification('fee_due_sms', $student, $addSecond);
                    }
                }
            } else {
                $students = Student::where('campus_id', $input['campus_id'])
                ->where('status', 'active')
                ->get();
                foreach ($students as $std) {
                    $student = $this->studentUtil->getStudentDue($std->id);
                    if ($student->total_due >0) {
                        $addSecond =  $addSecond +30;
                        $this->notificationUtil->autoSendStudentPaymentNotification('fee_due_sms', $student, null,$addSecond);
                    }
                }
            }
            $output = ['success' => true,
            'msg' => __("english.added_success")];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return redirect()->back()->with('status', $output);
    }
    public function mediacreate()
    {
        return view('notification.media-send');
    
    
    }
    public function mediaPost(Request $request)
    {
        $media=$request->file('file');
        $number='03429639593';
        $caption='Media';
       
        $client = new Client(['headers' => ['Authorization' => 'auth_trusted_header']]);
        $options = [
            'multipart' => [
                [
                    'Content-type' => 'multipart/form-data',
                    'name' => 'file',
                    'contents' => file_get_contents($request->file('file')->getPathname()),
                    'filename' => 'avata.' . $request->file('file')->getClientOriginalExtension()
                ],
                [ 'name' => 'number', 'contents' => $number ],
                [ 'name' => 'caption', 'contents' => $caption]
            ],
        ];
$response = $client->post('http://127.0.0.1:8000/send-media', $options);
        dd($response);
    }
}
