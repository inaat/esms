<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Campus;
use App\Utils\Util;

class StrengthController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(Util $util)
    {
        $this->util= $util;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('strength_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {

       
            $count_class_sections_student=ClassSection::leftjoin('campuses as cam', 'class_sections.campus_id', '=', 'cam.id')
            ->join('students', 'students.current_class_section_id', '=', 'class_sections.id')
        ->leftJoin('classes as c-class', 'class_sections.class_id', '=', 'c-class.id')
        ->select([
            'cam.campus_name',
            'cam.id as campus_id',
            'c-class.title',
            'c-class.id as class_id',
            'class_sections.section_name',
            'class_sections.id',
            DB::raw('count(students.id) as total_student')
        ])->where('students.status', '=', 'active')
        ->groupBy('class_sections.id')->orderBy('c-class.id');
        if (request()->has('campus_id')) {
            $campus_id = request()->get('campus_id');
            if (!empty($campus_id)) {
                $count_class_sections_student->where('class_sections.campus_id', $campus_id);
            }
        }
        if (request()->has('class_id')) {
            $class_id = request()->get('class_id');
            if (!empty($class_id)) {
                $count_class_sections_student->where('class_sections.class_id', $class_id);
            }
        }
        if (request()->has('class_section_id')) {
            $class_section_id = request()->get('class_section_id');
            if (!empty($class_section_id)) {
                $count_class_sections_student->where('class_sections.id', $class_section_id);
            }
        }
            
            return DataTables::of($count_class_sections_student)
            ->editColumn('total_student', function ($row) {
                $status='<div><a  href="' . action('StudentController@index','campus_id='.$row->campus_id.'&class_id='.$row->class_id.'&class_section_id='.$row->id) . '">'.ucwords($row->total_student).'</a></div>';
               
                // $status .='</a></div>';
                return $status;
            })
                ->rawColumns(['title','section_name','total_student','campus_name'])
            ->make(true);
        }
        $campuses=Campus::forDropdown();
      
        return view('Report.strength.index')->with(compact('campuses'));
    }

    public function show(Request $request){
        if (!auth()->user()->can('strength_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $data=[];
            try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
                if ($request->input('print_type')=='section_wise') {
                $count_class_sections_student=ClassSection::leftjoin('campuses as cam', 'class_sections.campus_id', '=', 'cam.id')
                ->join('students', 'students.current_class_section_id', '=', 'class_sections.id')
            ->leftJoin('classes as c-class', 'class_sections.class_id', '=', 'c-class.id')
            ->select([
                'cam.campus_name',
                'c-class.title',
                'class_sections.section_name',
                DB::raw('count(students.id) as total_student')
            ])->where('students.status', '=', 'active')
            ->groupBy('class_sections.id')->orderBy('c-class.id');
                if (request()->has('print_type')) {
                    $campus_id = request()->get('campus_id');
                    if (!empty($campus_id)) {
                        $count_class_sections_student->where('class_sections.campus_id', $campus_id);
                    }
                }
                if (request()->has('class_id')) {
                    $class_id = request()->get('class_id');
                    if (!empty($class_id)) {
                        $count_class_sections_student->where('class_sections.class_id', $class_id);
                    }
                }
                if (request()->has('class_section_id')) {
                    $class_section_id = request()->get('class_section_id');
                    if (!empty($class_section_id)) {
                        $count_class_sections_student->where('class_sections.id', $class_section_id);
                    }
                }
                $data=$count_class_sections_student->get();

            }else{
                 $count_classes_student=Classes::leftjoin('campuses as cam', 'classes.campus_id', '=', 'cam.id')
                 ->join('students','students.current_class_id','=','classes.id')
            ->select([
                'cam.campus_name',
                'classes.title',
                DB::raw('count(students.id) as total_student')
            ])->where('students.status','=','active')
            ->groupBy('classes.id')->get();
            $data=$count_classes_student;
            }
                $receipt = $this->receiptContent($data);

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => 0,
                        'msg' => trans("messages.something_went_wrong")
                        ];
            }

            return $output;
        }        
    }
    
    /**
     * Returns the content for the receipt
     *
     * @param  int  $business_id
     * @param  int  $location_id
     * @param  int  $transaction_id
     * @param string $printer_type = null
     *
     * @return array
     */
    private function receiptContent($data)
    {
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];

        $output['html_content'] = view('Report.strength.print', compact('data'))->render();
        
        return $output;
    }

}
