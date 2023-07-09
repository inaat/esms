<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;
use App\Models\Students;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Models\Campus;
use App\Models\Announcement;

use Yajra\DataTables\Facades\DataTables;
use DB;
class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!Auth::user()->can('assignment-list')) {
        //     $response = array(
        //         'message' => trans('no_permission_message')
        //     );
        //     return redirect(route('home'))->withErrors($response);
        // }
        if (request()->ajax()) {

        $sql = Assignment::leftjoin('campuses as cam', 'assignments.campus_id', '=', 'cam.id')
            ->leftjoin('classes as c', 'assignments.class_id', '=', 'c.id')
            ->leftjoin('class_sections as cs', 'assignments.class_section_id', '=', 'cs.id')
            ->leftjoin('class_subjects as sub', 'assignments.subject_id', '=', 'sub.id')
            ->leftjoin('hrm_employees as th', 'assignments.teacher_id', '=', 'th.id')
            ->select([
                'assignments.id',
                'assignments.name',
                'assignments.instructions',
                'assignments.due_date',
                'assignments.points',
                'assignments.resubmission',
                'assignments.extra_days_for_resubmission',
             
        'cam.campus_name as campus_name',
        'c.title as class_name',
        'cs.section_name as section_name',

        DB::raw("CONCAT(COALESCE(sub.name, ''),' (',COALESCE(sub.code,''),')') as subject_name"),
        DB::raw("CONCAT(COALESCE(th.first_name, ''),' ',COALESCE(th.last_name,'') ,'(',COALESCE(th.employeeID,''),')' ) as teacher_name")
        ]);
        return Datatables::of($sql)
        ->addColumn(
            'action',
            function ($row) {
                $html= '<div class="dropdown">
                     <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                     <ul class="dropdown-menu" style="">';
                   //  $html.='<li><a class="dropdown-item  edit_class_subject_button"data-href="' . action('Curriculum\AssignSubjectTeacherController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';

                $html .= '</ul></div>';

                return $html;
            }
        )
        ->editColumn('subject_name', function ($row) {
            return ucwords($row->subject_name);
        })
        ->editColumn('class_name', function ($row) {
            $current_class_section_name = $row->class_name. ' '. $row->section_name;
            return  $current_class_section_name;
        })
        ->removeColumn('id','section_name')
        ->rawColumns(['action','campus_name','class_name','subject_name'])
        ->make(true);
    }
       // $assignments=$sql->get();
       //dd($assignments);
        // $class_section = ClassSection::SubjectTeacher()->with('class.medium', 'section')->get();
        // $subjects = Subject::SubjectTeacher()->orderBy('id', 'ASC')->get();
        $campuses=Campus::forDropdown();
         return response(view('assignment.index', compact('campuses')));
    }
    public function create(){
        $campuses=Campus::forDropdown();
        return response(view('assignment.create', compact('campuses'))); 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!Auth::user()->can('assignment-create')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message')
        //     );
        //     return response()->json($response);
        // }
        // $validator = Validator::make($request->all(), [
        //     "class_section_id" => 'required|numeric',
        //     "subject_id" => 'required|numeric',
        //     "name" => 'required',
        //     "description" => 'nullable',
        //     "due_date" => 'required|date',
        //     "points" => 'nullable',
        //     "resubmission" => 'nullable|boolean',
        //     "extra_days_for_resubmission" => 'nullable|numeric',

        //     // 'file_upload' => 'required|numeric',
        //     // 'video_upload' => 'required|numeric',
        // ]);


        // if ($validator->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator->errors()->first(),
        //     );
        //     return response()->json($response);
        // }
        try {

            $session_id =  getActiveSession();

            $assignment = new Assignment();
            $assignment->campus_id = $request->campus_id;
            $assignment->class_id = $request->class_id;
            $assignment->class_section_id = $request->class_section_id;
            $assignment->subject_id = $request->subject_id;
            $assignment->name = $request->name;
            $assignment->instructions = $request->instructions;
            if ($request->has('transaction_date')) {
                $assignment->due_date = true ? $this->uf_date($request->due_date, true) : $request->due_date;
            } else {
                $assignment->due_date = \Carbon::now();
            }
            $assignment->points = $request->points;
            if ($request->resubmission) {
                $assignment->resubmission = 1;
                $assignment->extra_days_for_resubmission = $request->extra_days_for_resubmission;
            } else {
                $assignment->resubmission = 0;
                $assignment->extra_days_for_resubmission = null;
            }
            $assignment->session_id  = $session_id ;

            //$subject_name = Subject::select('name')->where('id', $request->subject_id)->pluck('name')->first();
            //$title = 'New assignment added in ' . $subject_name;
           // $body = $request->name;
           // $type = "assignment";
            //$user = Students::select('user_id')->where('class_section_id', $request->class_section_id)->get()->pluck('user_id');
            $assignment->save();
          //  send_notification($user, $title, $body, $type);

            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('assignment', 'public');
                    $file->modal()->associate($assignment);
                    $file->save();
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully')
            );
        } catch (Throwable $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'exception' => $e
            );
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!Auth::user()->can('assignment-list')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            $sort = $_GET['sort'];
        if (isset($_GET['order']))
            $order = $_GET['order'];

        $sql = Assignment::assignmentteachers()->with('class_section', 'file', 'subject');
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")
                ->orwhere('name', 'LIKE', "%$search%")
                ->orwhere('instructions', 'LIKE', "%$search%")
                ->orwhere('points', 'LIKE', "%$search%")
                ->orwhere('session_year_id', 'LIKE', "%$search%")
                ->orwhere('extra_days_for_resubmission', 'LIKE', "%$search%")
                ->orwhere('due_date', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                ->orwhere('created_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                ->orwhere('updated_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                ->orWhereHas('class_section.class', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('class_section.section', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('subject', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
        }
        if ($_GET['subject_id']) {
            $sql = $sql->where('subject_id', $_GET['subject_id']);
        }
        $total = $sql->count();

        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $no = 1;

        foreach ($res as $row) {

            $row = (object)$row;
            $operate = '<a href=' . route('assignment.edit', $row->id) . ' class="btn btn-xs btn-gradient-primary btn-rounded btn-icon edit-data" data-id=' . $row->id . ' title="Edit" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
            $operate .= '<a href=' . route('assignment.destroy', $row->id) . ' class="btn btn-xs btn-gradient-danger btn-rounded btn-icon delete-form" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['no'] = $no++;
            $tempRow['class_section_id'] = $row->class_section_id;
            $tempRow['class_section_name'] = $row->class_section->class->name . ' ' . $row->class_section->section->name;
            $tempRow['subject_id'] = $row->subject_id;
            $tempRow['subject_name'] = isset($row->subject) ? $row->subject->name : '-';
            $tempRow['name'] = $row->name;
            $tempRow['instructions'] = $row->instructions;
            $tempRow['file'] = $row['file'];
            $tempRow['due_date'] = $row->due_date;
            $tempRow['points'] = $row->points;
            $tempRow['resubmission'] = $row->resubmission;
            $tempRow['extra_days_for_resubmission'] = $row->extra_days_for_resubmission;
            $tempRow['session_year_id'] = $row->session_year_id;
            $tempRow['created_at'] = $row->created_at;
            $tempRow['updated_at'] = $row->updated_at;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('assignment-edit')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            "class_section_id" => 'required|numeric',
            "subject_id" => 'required|numeric',
            "name" => 'required',
            "description" => 'nullable',
            "due_date" => 'required|date',
            "points" => 'nullable',
            "resubmission" => 'nullable|boolean',
            "extra_days_for_resubmission" => 'nullable|numeric',
        ]);


        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
            );
            return response()->json($response);
        }
        try {

            $session_year = getSettings('session_year');
            $session_year_id = $session_year['session_year'];

            $assignment = Assignment::find($id);
            $assignment->class_section_id = $request->class_section_id;
            $assignment->subject_id = $request->subject_id;
            $assignment->name = $request->name;
            $assignment->instructions = $request->instructions;
            $assignment->due_date = $request->due_date;
            $assignment->points = $request->points;
            if ($request->resubmission) {
                $assignment->resubmission = 1;
                $assignment->extra_days_for_resubmission = $request->extra_days_for_resubmission;
            } else {
                $assignment->resubmission = 0;
                $assignment->extra_days_for_resubmission = null;
            }

            $assignment->session_year_id = $session_year_id;
            $subject_name = Subject::select('name')->where('id', $request->subject_id)->pluck('name')->first();
            $title = 'Update assignment in ' . $subject_name;
            $body = $request->name;
            $type = "assignment";
            $user = Students::select('user_id')->where('class_section_id', $request->class_section_id)->get()->pluck('user_id');
            $assignment->save();
            send_notification($user, $title, $body, $type);

            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('assignment', 'public');
                    $file->modal()->associate($assignment);
                    $file->save();
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully')
            );
        } catch (Throwable $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'exception' => $e
            );
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('assignment-delete')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        try {
            $assignment = Assignment::find($id);
            //            //Delete all the Assignment Submissions first
            //            $assignment_submission = AssignmentSubmission::where('assignment_id', $id)->get();
            //            if ($assignment_submission) {
            //                foreach ($assignment_submission as $submission) {
            //                    if (isset($submission->file)) {
            //                        foreach ($submission->file as $file) {
            //                            if (Storage::disk('public')->exists($file->file_url)) {
            //                                Storage::disk('public')->delete($file->file_url);
            //                            }
            //                        }
            //                        $submission->delete();
            //                    }
            //                }
            //            }
            //
            //            //After that Delete Assignment and its files from the server
            //            if ($assignment->file) {
            //                foreach ($assignment->file as $file) {
            //                    if (Storage::disk('public')->exists($file->file_url)) {
            //                        Storage::disk('public')->delete($file->file_url);
            //                    }
            //                }
            //            }
            //            $assignment->file()->delete();
            $assignment->delete();
            $response = array(
                'error' => false,
                'message' => trans('data_delete_successfully')
            );
        } catch (\Throwable $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred')
            );
        }
        return response()->json($response);
    }

    public function viewAssignmentSubmission()
    {
        if (!Auth::user()->can('assignment-submission')) {
            $response = array(
                'message' => trans('no_permission_message')
            );
            return redirect(route('home'))->withErrors($response);
        }
        $class_section = ClassSection::with('class', 'section')->get();
        $subjects = Subject::orderBy('id', 'ASC')->get();
        return response(view('assignment.submission', compact('class_section', 'subjects')));
    }

    public function assignmentSubmissionList()
    {
        if (!Auth::user()->can('assignment-submission')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            $sort = $_GET['sort'];
        if (isset($_GET['order']))
            $order = $_GET['order'];

        $sql = AssignmentSubmission::assignmentsubmissionteachers()->with('assignment.subject', 'student.user:first_name,last_name,id');

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")
                ->orwhere('session_year_id', 'LIKE', "%$search%")
                ->orwhere('created_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                ->orwhere('updated_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                ->orWhereHas('assignment.subject', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('assignment', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('student.user', function ($q) use ($search) {
                    $q->orWhereRaw("concat(users.first_name,' ',users.last_name) LIKE '%" . $search . "%'");
                });
        }



        if ($_GET['subject_id']) {

            $sql = $sql->whereHas('assignment', function ($q) {
                $q->where('subject_id', $_GET['subject_id']);
            });
        }


        $total = $sql->count();

        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $no = 1;
        foreach ($res as $row) {
            $row = (object)$row;
            $operate = '<a href=' . route('class.edit', $row->id) . ' class="btn btn-xs btn-gradient-primary btn-rounded btn-icon edit-data" data-id=' . $row->id . ' title="Edit" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';

            $tempRow['id'] = $row->id;
            $tempRow['no'] = $no++;
            $tempRow['assignment_id'] = $row->assignment_id;
            $tempRow['assignment_name'] = $row->assignment->name;
            $tempRow['assignment_points'] = $row->assignment->points;
            $tempRow['subject'] = $row->assignment->subject->name;

            $tempRow['student_id'] = $row->student_id;
            $tempRow['student_name'] = $row->student->user->first_name . ' ' . $row->student->user->last_name;

            $tempRow['file'] = $row->file;
            $tempRow['points'] = $row->points;

            $tempRow['session_year_id'] = $row->session_year_id;
            $tempRow['feedback'] = $row->feedback;
            $tempRow['status'] = $row->status;

            $tempRow['created_at'] = $row->created_at;
            $tempRow['updated_at'] = $row->updated_at;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }


    public function updateAssignmentSubmission(Request $request, $id)
    {
        if (!Auth::user()->can('assignment-submission')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric',
            'feedback' => 'nullable',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }

        try {
            $assignment_submission = AssignmentSubmission::findOrFail($id);
            $assignment_submission->feedback = $request->feedback;
            if ($request->status == 1) {
                $assignment_submission->points = $request->points;
            } else {
                $assignment_submission->points = null;
            }
            $assignment_submission->status = $request->status;
            $assignment_submission->save();

            $assignment_data = Assignment::where('id', $assignment_submission->assignment_id)->with('subject')->first();
            $user = Students::select('user_id')->where('id', $assignment_submission->student_id)->get()->pluck('user_id');
            if ($request->status == 2) {
                $title = "Assignment rejected";
                $body = $assignment_data->name . " rejected in " . $assignment_data->subject->name . " subject";
            }
            if ($request->status == 1) {
                $title = "Assignment accepted";
                $body = $assignment_data->name . " accepted in " . $assignment_data->subject->name . " subject";
            }
            $type = "assignment";
            send_notification($user, $title, $body, $type);
            $response = array(
                'error' => false,
                'message' => trans('data_update_successfully'),
            );
        } catch (\Throwable $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'data' => $e
            );
        }
        return response()->json($response);
    }
}
