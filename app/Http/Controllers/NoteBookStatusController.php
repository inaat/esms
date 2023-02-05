<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Student;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\ClassSection;
use App\Models\NoteBookStatus;
use App\Models\HumanRM\HrmEmployee;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\StudentUtil;
use DB;
use File;

class NoteBookStatusController extends Controller
{
    public function __construct(StudentUtil $studentUtil)
    {
    $this->studentUtil= $studentUtil;
}
    public function index()
    {
        if (!auth()->user()->can('note_books_status.view')) {
            abort(403, 'Unauthorized action.');
        }
             $campuses=Campus::forDropdown();



        return view('note_book.index')->with(compact('campuses'));
    }

  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('note_books_status.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('note_book.create')->with(compact('campuses'));
    }
    public function noteBookAssignSearch(Request $request)
    {
        if (!auth()->user()->can('note_books_status.view')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->all();
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_subjects = ClassSubject::where('class_id', $class_id)->get();
        $date=$this->studentUtil->uf_date($input['check_date']);
        $class_section_id=$input['class_section_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);

        $campuses=Campus::forDropdown();
        $students=$this->studentUtil->getStudentList($system_settings_id, $class_id, $class_section_id, 'active');
        $MONTH= \Carbon::createFromFormat('Y-m-d', $date)->format('m');
        $YEAR= \Carbon::createFromFormat('Y-m-d', $date)->format('Y');
       // dd()
        $note_books=[];
        foreach ($students as $student) {
            $subjects_list=[];
            foreach ($class_subjects as $sub) {
                $note_book_status_list=NoteBookStatus::
                whereMonth("check_date", $MONTH)
                ->whereYear("check_date", $YEAR)
                ->where('campus_id', $campus_id)
                ->where('class_id', $class_id)
                ->where('class_section_id', $class_section_id)
                ->where('student_id', $student->id)
                ->where('subject_id', $sub->id)->first();
                // dd($note_book_status_list);
                if (!empty($note_book_status_list)) {
                    $subjects_list []=[
                        'subject_name' => $sub->name,
                        'subject_id' => $sub->id,
                        'status' => $note_book_status_list->status
                    ];
                } else {
                    $subjects_list []=[
                        'subject_name' => $sub->name,
                        'subject_id' => $sub->id,
                        'status' => null
                    ];
                }
            }

            $note_books[]=[
                'student_id' => $student->id,
                'student_name' => $student->student_name,
                'roll_no' => $student->roll_no,
                'subjects_list' => $subjects_list

            ];
        }
        $teachers=HrmEmployee::teacherDropdown();

        //dd($note_books);

        return view('note_book.note_book_assign')->with(compact('note_books', 'teachers', 'class_subjects', 'students', 'campuses', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id', 'date'));
    }

    public function noteBookAssignPost(Request $request)
    {
        if (!auth()->user()->can('note_books_status.view')) {
            abort(403, 'Unauthorized action.');
        }
        $output = ['success' => 0,
        'msg' => __('english.something_went_wrong')
    ];
        try {
        $input = $request->input();
        $date=$input['check_date'];
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_section_id=$input['class_section_id'];
        $MONTH= \Carbon::createFromFormat('Y-m-d', $date)->format('m');
        $YEAR= \Carbon::createFromFormat('Y-m-d', $date)->format('Y');
        DB::beginTransaction();

        foreach ($input['data'] as $students) {
            // dd($students['subjects']);
            foreach ($students['subjects'] as $sub) {
                if (array_key_exists("status", $sub)) {
                    $note_book_status_list=NoteBookStatus::
                    whereMonth("check_date", $MONTH)
                    ->whereYear("check_date", $YEAR)
                    ->where('campus_id', $campus_id)
                    ->where('class_id', $class_id)
                    ->where('class_section_id', $class_section_id)
                    ->where('student_id', $students['student_id'])
                    ->where('subject_id', $sub['subject_id'])->first();
                   // dd($note_book_status_list);
                    if (!empty($note_book_status_list)) {
                        $note_book_status_list->status=$sub['status'];
                        $note_book_status_list->save();
                    } else {
                        $note_book=[
                            'campus_id'=>$input['campus_id'],
                            'class_id'=>$input['class_id'],
                            'check_date'=>$date,
                            'class_section_id'=>$input['class_section_id'],
                            'student_id'=>$students['student_id'],
                            'subject_id' =>$sub['subject_id'],
                            'status' =>$sub['status'],
                            'employee_id'=>$input['employee_id']
                        ];
                        $note = NoteBookStatus::create($note_book);
                    }
                }
            }
        }
        DB::commit();

        $output = ['success' => 1,
                            'msg' => __('english.updated_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __('english.something_went_wrong')
                        ];
        }

       

        return redirect('note-book/create')->with('status', $output);
    }

    public function noteBookEmptyPrintCreate()
    {
        if (!auth()->user()->can('note_books_status.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('note_book.print_create')->with(compact('campuses'));
    }
    public function noteBookEmptyPrintStore(Request $request)
    {
    $input = $request->input();
    $students =Student::with(['campuses','current_class','current_class_section'])
    ->where('students.campus_id', $input['campus_id'])
      //  ->where('students.current_class_id ', $input['class_id'])
    ->where('students.current_class_section_id', $input['class_section_id'])
    ->where('students.status', '=', 'active')->get();
    if (File::exists(public_path('uploads/pdf/hrm.pdf'))) {
        File::delete(public_path('uploads/pdf/hrm.pdf'));
    }
    $pdf_name='hrm'.'.pdf';
    $subjects = ClassSubject::with(['campuses','classes'])->where('class_id', $input['class_id'])->where('campus_id', $input['campus_id'])->get();

    $pdf =  config('constants.mpdf');
    if ($pdf) {
        $data=[
            'students'=>$students,
            'subjects'=>$subjects

        ];
        $this->reportPDF('samplereport.css', $data, 'MPDF.empty_print', 'view', 'a4');
    }
    else{
    $snappy  = \WPDF::loadView('note_book.empty_print', compact('students', 'subjects'));
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'portrait')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}
    }

    public function noteBookPrint(Request $request)
    {

        if (!auth()->user()->can('note_books_status.view')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->all();
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_subjects = ClassSubject::with(['campuses','classes'])->where('class_id', $input['class_id'])->where('campus_id', $input['campus_id'])->get();
        $date=$this->studentUtil->uf_date($input['check_date']);
        $class_section_id=$input['class_section_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::with(['campuses','classes'])->where('class_id', $input['class_id'])->where('id', $class_section_id)->first();
         //dd($sections);
        $campuses=Campus::forDropdown();
        $students=$this->studentUtil->getStudentList($system_settings_id, $class_id, $class_section_id, 'active');
        $MONTH= \Carbon::createFromFormat('Y-m-d', $date)->format('m');
        $YEAR= \Carbon::createFromFormat('Y-m-d', $date)->format('Y');
        $note_books=[];
        foreach ($students as $student) {
            $subjects_list=[];
            foreach ($class_subjects as $sub) {
                $note_book_status_list=NoteBookStatus::
                whereMonth("check_date", $MONTH)
                ->whereYear("check_date", $YEAR)
                ->where('campus_id', $campus_id)
                ->where('class_id', $class_id)
                ->where('class_section_id', $class_section_id)
                ->where('student_id', $student->id)
                ->where('subject_id', $sub->id)->first();
                // dd($note_book_status_list);
                if (!empty($note_book_status_list)) {
                    $subjects_list []=[
                        'subject_name' => $sub->name,
                        'subject_id' => $sub->id,
                        'status' => $note_book_status_list->status
                    ];
                } else {
                    $subjects_list []=[
                        'subject_name' => $sub->name,
                        'subject_id' => $sub->id,
                        'status' => null
                    ];
                }
            }

            $note_books[]=[
                'student_id' => $student->id,
                'student_name' => $student->student_name,
                'roll_no' => $student->roll_no,
                'subjects_list' => $subjects_list

            ];
        }
        if (File::exists(public_path('uploads/pdf/hrm.pdf'))) {
            File::delete(public_path('uploads/pdf/hrm.pdf'));
        }
        $pdf =  config('constants.mpdf');
        if ($pdf) {
            $data=[
                'note_books'=>$note_books,
                'class_subjects'=>$class_subjects,
                'sections'=>$sections

            ];
            $this->reportPDF('samplereport.css', $data, 'MPDF.note_book_status_report', 'view', 'a4');
        }
        else{
    $pdf_name='hrm'.'.pdf';
    $snappy  = \WPDF::loadView('note_book.print_data', compact('note_books', 'class_subjects', 'sections'));
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'portrait')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}
}
}
