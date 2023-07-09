<?php

namespace App\Http\Controllers\Examination;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Exam\ExamGrade;
use App\Models\Campus;
use DB;
use Yajra\DataTables\Facades\DataTables;

class GradeController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('grade.view')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
        if (request()->ajax()) {
            $campuses = ExamGrade::
                                select(['id','name','point','percentage_from','percentage_to','remark']);
            
            return DataTables::of($campuses)
                            ->addColumn(
                                'action',
                                '
                                <div class="d-flex order-actions">
                                <button data-href="{{action(\'Examination\GradeController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_grade_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                                    &nbsp;
                                   
                                </div>
                                ')
                            // ->editColumn('registration_date', function ($row) {
                            //     return $this->accountTransactionUtil->format_date($row->registration_date);
                            // })
                            ->removeColumn('id')
                            ->rawColumns(['action','campus_name','registration_code','registration_date','mobile','phone','address','address'])
                            ->make(true);
        }



      return view('Examination.grade.index');

    }
      
     /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {  if (!auth()->user()->can('grade.view')) {
    abort(403, 'Unauthorized action.');
}
      return view('Examination.grade.create');
  }
   /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if (!auth()->user()->can('grade.view')) {
        abort(403, 'Unauthorized action.');
    }

      try {
          $input = $request->only(['name','point','percentage_from','percentage_to','remark']);

          $exam_grade = ExamGrade::create($input);
        

          $output = ['success' => true,
                          'data' => $exam_grade,
                          'msg' => __("english.added_success")
                      ];
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
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    if (!auth()->user()->can('grade.view')) {
        abort(403, 'Unauthorized action.');
    }

      if (request()->ajax()) {

          $exam_grade = ExamGrade::find($id);
          return view('Examination.grade.edit')
              ->with(compact('exam_grade'));
      }
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
    if (!auth()->user()->can('grade.view')) {
        abort(403, 'Unauthorized action.');
    }

      if (request()->ajax()) {
          try {
            $input = $request->only(['name','point','percentage_from','percentage_to','remark']);
            $exam_grade = ExamGrade::findOrFail($id);
            $exam_grade->fill($input);
            $exam_grade->save();

              $output = ['success' => true,
                          'msg' => __("english.updated_success")
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
}