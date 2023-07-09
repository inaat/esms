<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ClassSchool;
use App\Models\ClassSection;
use App\Models\ClassSubject;
use App\Models\File;
use App\Models\Settings;
use App\Models\Student;
use App\Models\Students;
use App\Models\Subject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Models\Campus;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $sql = Announcement::with('table', 'file');
        dd($sql->get());
        // if (!Auth::user()->can('announcement-list')) {
        //     $response = array(
        //         'message' => trans('no_permission_message')
        //     );
        //     return redirect(route('home'))->withErrors($response);
        // }
        // $class_section = ClassSection::SubjectTeacher()->with('class.medium', 'section')->get();
        // return view('announcement.index', compact('class_section'));
    }
    public function create(){
        $campuses=Campus::forDropdown();
        return response(view('announcement.create', compact('campuses'))); 
    }
    public function getAssignData(Request $request) {
        $data = $request->data;
        $class_id = $request->class_id;
        if ($data == 'class_section' && $class_id != '') {
            $info = ClassSubject::where('class_id', $class_id)->with('subject')->get();
        } elseif ($data == 'class') {
            $info = ClassSchool::get();
        } else {
            $info = '';
        }
        return response()->json($info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // if (!Auth::user()->can('announcement-create')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message')
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }
       // try {
            $user = array();
            $session_id =  getActiveSession();
            if (!empty($request->get_data)) {
                $getdata = count($request->get_data);
            } else {
                $getdata = 1;
            }
            for ($i = 0; $i < $getdata; $i++) {
                $announcement = new Announcement();
                $announcement->title = $request->title;
                $announcement->description = $request->description;
                $announcement->session_id = $session_id;
                if (!empty($request->set_data)) {
                    if ($request->set_data == 'class_section') {
                        $teacher_id = Auth::user()->teacher->id;
                        
                        $subject_teacher_id = SubjectTeacher::select('id')->where(['class_section_id' => $request->class_section_id,'teacher_id' => $teacher_id ,'subject_id' => $request->get_data[$i]])->get()->pluck('id');
                        $subject_name = SubjectTeacher::where(['teacher_id' => $teacher_id,'class_section_id' => $request->class_section_id,'subject_id' => $request->get_data[$i]])->with('class_subject')->get();
                        

                        if(count($subject_name)){
                            if (count($subject_teacher_id) != 0) {
                                for ($j = 0; $j < count($subject_teacher_id); $j++) {
                                    $subject_teacher = SubjectTeacher::find($subject_teacher_id[$j]);
                                    $announcement->table()->associate($subject_teacher);
                                    $user = Student::select('user_id')->where('current_class_section_id', $request->class_section_id)->whereNotNull('user_id')->where('status', 'active')->get()->pluck('user_id');
                                    dd($user);
                                }
                            }
                            $title = 'New announcement in ' . $subject_name[0]->class_subject->name;
                            $body = $request->title;
                            //dd($announcement);
                        }
                        else{
                            $response = array(
                                'error' => true,
                                'message' => trans('no_data_found')
                            );
                            return response()->json($response);
                        }
                    }
                    // if ($request->set_data == 'class') {
                    //     // $class = ClassSchool::find($request->get_data[$i]);
                    //     // $announcement->table()->associate($class);
                    //     // $get_class = ClassSection::select('id')->where('class_id', $request->get_data[$i])->get()->pluck('id');
                    //     // $user = Students::select('user_id')->where('class_section_id', $get_class[$i])->get()->pluck('user_id');
                    //     // $title = $request->title;
                    //     // $body = $request->description;
                    // }
                    if ($request->set_data == 'general') {
                        $announcement->table_id = null;
                        $announcement->table_type = "";
                        $user = Student::select('user_id')->whereNotNull('user_id')->where('status', 'active')->get()->pluck('user_id');
                        $title = 'Noticeboard updated';
                        $body = $request->title;
                    }
                }
                $type = $request->set_data;
                $announcement->save();
                //send_notification($user, $title, $body, $type);
                if ($request->hasFile('file')) {
                    foreach ($request->file as $file_upload) {
                        $file = new File();
                        $file->file_name = $file_upload->getClientOriginalName();
                        $file->type = 1;
                        $file->file_url = $file_upload->store('announcement', 'public');
                        $file->modal()->associate($announcement);
                        $file->save();
                    }
                }
            }
            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully')
            );
        // } catch (\Throwable $e) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('error_occurred')
        //     );
        // }
        dd($response);
    }

    public function update(Request $request) {
        if (!Auth::user()->can('announcement-edit')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $request->validate([
            'title' => 'required'
        ]);
        // try {
            $user = array();
            $data = getSettings('session_year');
            if(Auth::user()->teacher){
                $teacher_id = Auth::user()->teacher->id;
            }
            $announcement = Announcement::find($request->id);
            $announcement->title = $request->title;
            $announcement->description = $request->description;
            $announcement->session_year_id = $data['session_year'];
            if (!empty($request->set_data)) {
                if ($request->set_data == 'class_section') {
                    $subject_teacher_id = SubjectTeacher::select('id')->where(['class_section_id' => $request->class_section_id, 'subject_id' => $request->get_data,'teacher_id'=>$teacher_id])->get()->pluck('id');
                    $subject_name = SubjectTeacher::where(['class_section_id' => $request->class_section_id, 'subject_id' => $request->get_data,'teacher_id'=>$teacher_id])->with('subject')->get();
                    if (count($subject_teacher_id) != 0) {
                        for ($j = 0; $j < count($subject_teacher_id); $j++) {
                            $subject_teacher = SubjectTeacher::find($subject_teacher_id[$j]);
                            $announcement->table()->associate($subject_teacher);
                            $user = Students::select('user_id')->where('class_section_id', $request->class_section_id)->get()->pluck('user_id');
                        }
                        $title = 'Update announcement in ' . $subject_name[0]->subject->name;
                        $body = $request->title;
                    }
                }
                if ($request->set_data == 'class') {
                    $class = ClassSchool::find($request->get_data);
                    $announcement->table()->associate($class);
                    $get_class = ClassSection::select('id')->where('class_id', $request->get_data)->get()->pluck('id');
                    $user = Students::select('user_id')->where('class_section_id', $get_class)->get()->pluck('user_id');
                    $title = $request->title;
                    $body = $request->description;
                }
                if ($request->set_data == 'general') {
                    $announcement->table_id = null;
                    $announcement->table_type = "";
                    $user = Students::select('user_id')->get()->pluck('user_id');
                    $title = 'Noticeboard updated';
                    $body = $request->title;
                }
            }
            $type = $request->set_data;
            $announcement->save();
            send_notification($user, $title, $body, $type);
            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('announcement', 'public');
                    $file->modal()->associate($announcement);
                    $file->save();
                }
            }
            $response = [
                'error' => false,
                'message' => trans('data_update_successfully'),
            ];
        // } catch (Throwable $e) {
        //     $response = [
        //         'error' => true,
        //         'message' => trans('error_occurred'),
        //     ];
        // }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        if (!Auth::user()->can('announcement-list')) {
            $response = array(
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        // $announcement=Announcement::get();
        // return view('announcement.list',compact('announcement'));
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
        $sql = Announcement::with('table', 'file');
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")
                ->orwhere('title', 'LIKE', "%$search%")
                ->orwhere('description', 'LIKE', "%$search%");
        }
        $total = $sql->count();
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $no = 1;
        $user = Auth::user();
        foreach ($res as $row) {
            $operate = '';
            if ($user->hasRole('Super Admin') && $row->table_type == "") {
                $operate .= '<a class="btn btn-xs btn-gradient-primary btn-rounded btn-icon editdata" data-id="' . $row->id . '"  title="Edit" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                $operate .= '<a class="btn btn-xs btn-gradient-danger btn-rounded btn-icon deletedata" data-id="' . $row->id . '" data-url="' . url('announcement', $row->id) . '" title="Delete"><i class="fa fa-trash"></i></a>';
            } elseif ($user->hasRole('Teacher') && $row->table_type == "App\\Models\\SubjectTeacher") {
                $operate .= '<a class="btn btn-xs btn-gradient-primary btn-rounded btn-icon editdata" data-id="' . $row->id . '"  title="Edit" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                $operate .= '<a class="btn btn-xs btn-gradient-danger btn-rounded btn-icon deletedata" data-id="' . $row->id . '" data-url="' . url('announcement', $row->id) . '" title="Delete"><i class="fa fa-trash"></i></a>';
            }

            $tempRow['id'] = $row->id;
            $tempRow['no'] = $no++;
            $tempRow['title'] = $row->title;
            $tempRow['description'] = $row->description;
            $tempRow['type'] = $row->table_type;
            if ($tempRow['type'] == "App\\Models\\ClassSection") {
                $assign = 'class_section';
                $class = $row->table->class->name . ' - ' . $row->table->section->name;
                $class1 = $class;
            }
            if ($tempRow['type'] == "App\\Models\\ClassSchool") {
                $assign = 'class';
                $class = $row->table->name;
                $class1 = $class;
            }
            if ($tempRow['type'] == "App\\Models\\SubjectTeacher") {
                $assign = 'Subject';
                $class = $row->table;
                $class1 = $row->table->class_section->class->name . '-' . $row->table->class_section->section->name . '  ' . $row->table->subject->name;
            }
            if ($tempRow['type'] == "") {
                $assign = 'general';
                $class = trans("general");
                $class1 = $class;
            }
            $tempRow['assign'] = $assign;
            $tempRow['assign_to'] = $class;
            $tempRow['assignto'] = $class1;
            $tempRow['get_data'] = $row->table_id;
            $tempRow['file'] = $row->file;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (!Auth::user()->can('announcement-delete')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        try {
            Announcement::find($id)->delete();
            $response = array(
                'error' => false,
                'message' => trans('data_delete_successfully')
            );
        } catch (Throwable $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred')
            );
        }
        return response()->json($response);
    }
}
