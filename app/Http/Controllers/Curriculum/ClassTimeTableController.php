<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassTimeTablePeriod;
use App\Models\Curriculum\ClassTimeTable;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Utils\Util;
use App\Models\Classes;
use App\Models\HumanRM\HrmEmployee;

use Yajra\DataTables\Facades\DataTables;
use DB;
use File;

class ClassTimeTableController extends Controller
{
          /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('class_routine.view')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
         
        $check_period =[];
        $sections=ClassSection::with(['campuses','classes','time_table','time_table.subjects','time_table.teacher','time_table.periods'])->orderBy('class_id');
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $sections->whereIn('class_sections.campus_id', $permitted_campuses);
        }
        if (request()->has('campus_id')) {
            $campus_id = request()->get('campus_id');
            if (!empty($campus_id)) {
                $sections->where('class_sections.campus_id', $campus_id);
               
            }
        }else{
            $campus_id=null;
        }
        if (request()->has('class_id')) {
            $class_id = request()->get('class_id');
            if (!empty($class_id)) {
                $sections->where('class_sections.class_id', $class_id);
               
            }
        }
        else{
            $class_id=null;
        }
        if (request()->has('class_section_id')) {
            $class_section_id = request()->get('class_section_id');
            if (!empty($class_section_id)) {
                $sections->where('class_sections.id', $class_section_id);
               
            }
        }  else{
            $class_section_id=null;
        }
        $sections_t=$sections->get();
        $periods=ClassTimeTablePeriod::orderBy('id')->get();
        $sections=[];
        foreach ($sections_t as $section){
            
             if($section->time_table->count()> 0){
                $class_time_table=[];
                foreach($periods as $period){
                    
                    $tt=ClassTimeTable::with(['campuses','classes','subjects','teacher','periods'])
                    ->where('campus_id',$section->campus_id)
                    ->where('class_id',$section->class_id)
                    ->where('class_section_id',$section->id)
                    ->where('period_id',$period->id)->first();
                    if(!empty($tt)){
                    
                        $class_time_table[]= $tt;
                    }else{
                       $class_time_table[]= [];

                    }
                }
              
                $sections[]=['section_name'=>$section->classes->title.'  ' . $section->section_name,
                'timetables'=>$class_time_table];
           
             }
        } 
        $campuses=Campus::forDropdown();
        if (!empty($campus_id)) {
            $classes=Classes::forDropdown($system_settings_id, false, $campus_id);
        }else{
            $classes=[];
        }
    
        if (!empty($class_id)) {
            $class_sections=ClassSection::forDropdown($system_settings_id, false, $class_id);
        }else{
            $class_sections=[];
        }
        foreach($periods as $period){
            $class_time_table_title[]= $this->commonUtil->format_time($period->start_time).' To '.$this->commonUtil->format_time($period->end_time).' '.$period->name;
        }
        $all_subjects=ClassSubject::allSubjectDropdown();
        $teachers=HrmEmployee::forDropdown();
        if (request()->has('print')) {
            $print = request()->get('print');
            if (!empty($print)) {
                $snappy=$this->generateFeeCard($sections,$class_time_table_title);
                return $snappy->stream();            }
        }
      
       
        return view('Curriculum.class_time_table.index')->with(compact('classes','class_sections','sections','class_time_table_title','campuses','campus_id','class_id','class_section_id','all_subjects','teachers'));
    }
         /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('class_routine.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('Curriculum.class_time_table.create')->with(compact('campuses'));
    }
    //   /**
    //  * Store a newly created resource in storage.
    //  * @param Request $request
    //  * @return Response
    //  */
    public function store(Request $request)
    {
        if (!auth()->user()->can('class_routine.create')) {
            abort(403, 'Unauthorized action.');
        }
     
      try {
            $input = $request->only(['campus_id','subject_id','class_id','class_section_id','period_id','other','note']);
            $checkClassTimeTable = ClassTimeTable::where('campus_id', $input['campus_id'])->where('class_id',$input['class_id'])
           ->where('class_section_id',$input['class_section_id'])->where('period_id',$input['period_id'])->first();
           
           $classSubject =  SubjectTeacher::where('subject_id',$input['subject_id'])-> where('campus_id', $input['campus_id'])->where('class_id',$input['class_id'])
           ->where('class_section_id',$input['class_section_id'])->first();
           $input['multi_subject_ids'] = !empty($request->input('multi_subject_ids')) ? $request->input('multi_subject_ids') :null;
           if(!empty($input['multi_subject_ids'])){
            $teacher_ids=[];
            foreach($input['multi_subject_ids'] as $value){
            $multi_Subject_teacher =  SubjectTeacher::where('subject_id',$value)-> where('campus_id', $input['campus_id'])->where('class_id',$input['class_id'])
            ->where('class_section_id',$input['class_section_id'])->first();
            array_push($teacher_ids,$multi_Subject_teacher->teacher_id);

        }
        $input['multi_teacher'] = !empty($teacher_ids) ? $teacher_ids :null;

        }
           if (!empty($classSubject)) {
               //dd('44');
               $input['teacher_id']=$classSubject->teacher_id;
           
               $check_teacher_slot=ClassTimeTable::where('period_id', $input['period_id'])
                                                ->where('teacher_id', $classSubject->teacher_id)->first();
           }
            if (empty($check_teacher_slot)) {
                if (empty($checkClassTimeTable)) {
                    ClassTimeTable::create($input);

                    $output = ['success' => true,
                            'msg' => __("english.added_success")
                        ];
                } else {
                    $output = ['success' => false,
                'msg' => __("english.already_exists")

            ];
                }
            }else {
                $output = ['success' => false,
            'msg' => __("english.already_exists")

        ];
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")

                        ];
        }

        return $output;
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('class_routine.update')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $classTimeTable= ClassTimeTable::findOrFail($id);
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id, false, $classTimeTable->campus_id);
        $class_sections=ClassSection::forDropdown($system_settings_id, false, $classTimeTable->class_id);

        $classSubject = SubjectTeacher::forDropdown($classTimeTable->class_id,$classTimeTable->class_section_id);
        $classTimeTablePeriod = ClassTimeTablePeriod::forDropdown($classTimeTable->campus_id);

        return view('Curriculum.class_time_table.edit')->with(compact('campuses','classes','class_sections','classSubject','classTimeTablePeriod','classTimeTable'));
    }
       /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('class_routine.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['campus_id','subject_id','class_id','class_section_id','period_id','other','note']);
                $time_table =ClassTimeTable::findOrFail($id);
                $Subject_teacher =  SubjectTeacher::where('subject_id',$input['subject_id'])-> where('campus_id', $input['campus_id'])->where('class_id',$input['class_id'])
                ->where('class_section_id',$input['class_section_id'])->first();
                $input['multi_subject_ids'] = !empty($request->input('multi_subject_ids')) ? $request->input('multi_subject_ids') :null;
                if(!empty($input['multi_subject_ids'])){
                    $teacher_ids=[];
                    foreach($input['multi_subject_ids'] as $value){
                    $multi_Subject_teacher =  SubjectTeacher::where('subject_id',$value)-> where('campus_id', $input['campus_id'])->where('class_id',$input['class_id'])
                    ->where('class_section_id',$input['class_section_id'])->first();
                    array_push($teacher_ids,$multi_Subject_teacher->teacher_id);

                }
                $input['multi_teacher'] = !empty($teacher_ids) ? $teacher_ids :null;

                }
                if (!empty($Subject_teacher)) {
                    $input['teacher_id']=$Subject_teacher->teacher_id;
                }else{
                    $input['teacher_id']=null;
                }
                $time_table->fill($input);
                //dd($input);
                if (!empty($input['teacher_id'])) {
                    $classSubject = ClassSubject::findOrFail($input['subject_id']);
                    $input['teacher_id']=$classSubject->teacher_id;
                    $check_teacher_slot=ClassTimeTable::where('period_id', $input['period_id'])
                                                 ->where('subject_id',$input['subject_id'])
                                                ->where('teacher_id', $classSubject->teacher_id)->first();

                    if ($time_table->teacher_id==$classSubject->teacher_id) {
                        $time_table->save();
                        $output = ['success' => true,
                    'msg' => __("english.updated_success")
                    ];
                    } else {
                        //dd($check_teacher_slot);
                        if (empty($check_teacher_slot)) {
                            $time_table->save();
                            $output = ['success' => true,
                    'msg' => __("english.updated_success")
                    ];
                } else {
                            $output = ['success' => false,
                                    'msg' => __("english.already_exists")];
                        }
                    }
                }
                else{
                    $time_table->save();
                    $output = ['success' => true,
            'msg' => __("english.updated_success")
            ];
               
                }
               
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
  
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }
  
            return $output;
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        if (!auth()->user()->can('class_routine.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $period =ClassTimeTable::findOrFail($id);
                $period->delete();

                $output = ['success' => true,
                        'msg' => __("english.deleted_success")
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
            }

            return $output;
        }
    }
    private function generateFeeCard($sections,$class_time_table_title)
    {
        if (!auth()->user()->can('class_routine.view')) {
            abort(403, 'Unauthorized action.');
        }
        $sections=$sections;
        $class_time_table_title=$class_time_table_title;
        $logo = 'Pk';
        $pdf_name='time_table'.'.pdf';
        $all_subjects=ClassSubject::allSubjectDropdown();
        $teachers=HrmEmployee::forDropdown();
        if (File::exists(public_path('uploads/pdf/time_table.pdf'))) {
            File::delete(public_path('uploads/pdf/time_table.pdf'));
        }       $pdf =  config('constants.mpdf');
        if ($pdf) { 
            $data=[
                'sections'=>$sections,
                 'class_time_table_title'=>$class_time_table_title, 
                 'all_subjects'=>$all_subjects, 
                 'teachers'=>$teachers
            ] ;
            $this->reportPDF('samplereport.css', $data, 'MPDF.class_wise_table','view','a4','landscape');

         }else{
   
    $snappy  = \WPDF::loadView('school-printing.time_table.print', compact('sections', 'class_time_table_title', 'all_subjects', 'teachers'));
    $headerHtml = view()->make('common._header', compact('logo'))->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'landscape')->setOption('margin-top', 30)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 15);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file

    return $snappy;
}
    }
}
