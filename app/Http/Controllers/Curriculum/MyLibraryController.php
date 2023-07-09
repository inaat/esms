<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassSubjectLesson;

use App\Models\Campus;
use App\Models\Classes;
use App\Models\HumanRM\HrmEmployee;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use DB;
use File;
class MyLibraryController extends Controller
{
    protected $commonUtil;

    /**
    * Constructor
    *
    */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }
    public function index(){
        
        $classes=Classes::where('campus_id',1)->get();
        return view('Curriculum.my_library.index')->with(compact('classes'));
    }
    public function getSubjectsByClass($id){
        $classes=Classes::find($id);

        $subjects=ClassSubject::where('class_id',$id)->get();
        return view('Curriculum.my_library.subjects')->with(compact('subjects','classes'));
    }
    public function getSubjectsChapters($id){
        $subject= ClassSubject::with(['classes'])->find($id);
        
        $chapters=SubjectChapter::where('subject_id',$id)->get();
        return view('Curriculum.my_library.chapters')->with(compact('chapters','subject'));
    }
    public function getChaptersLessons($id){
       
        $chapter=SubjectChapter::where('id',$id)->first();
        $subject= ClassSubject::with(['classes'])->find($chapter->subject_id);

        $lessons=ClassSubjectLesson::where('chapter_id',$id)->get();
       // dd($lessons);
        return view('Curriculum.my_library.lesson')->with(compact('chapter','subject','lessons'));
    }
 
}
