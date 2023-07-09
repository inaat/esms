<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\ClassSubjectLesson;
use App\Models\Campus;
use App\Rules\YouTubeUrl;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\File;
use Illuminate\Support\Facades\Validator;

class ClassSubjectLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!auth()->user()->can('lesson.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $subject_lessons = ClassSubjectLesson::with('file')->leftjoin('subject_chapters as chap', 'class_subject_lessons.chapter_id', '=', 'chap.id')
                ->select(['class_subject_lessons.name', 'chap.chapter_name as chapter_name', 'class_subject_lessons.description', 'class_subject_lessons.id']);
            if (request()->has('chapter_id')) {
                $chapter_id = request()->get('chapter_id');
                if (!empty($chapter_id)) {
                    $subject_lessons->where('chapter_id', $chapter_id);
                }
            }
            if (request()->has('subject_id')) {
                $subject_id = request()->get('subject_id');
                if (!empty($subject_id)) {
                    $subject_lessons->where('class_subject_lessons.subject_id', $subject_id);
                }
            }
            //dd(40);
            return Datatables::of($subject_lessons)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . __("english.actions") . '</button>
                             <ul class="dropdown-menu" style="">';
                        if (auth()->user()->can('lesson.update')) {
                            $html .= '<li><a class="dropdown-item  edit_lesson_button"data-href="' . action('Curriculum\ClassSubjectLessonController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                        }
                        if (auth()->user()->can('lesson.delete')) {
                            $html .= '<li><a class="dropdown-item  delete_lesson_button"data-href="' . action('Curriculum\ClassSubjectLessonController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
                        }
                        $html .= '</ul></div>';

                        return $html;
                    }
                )
                ->addColumn('files', function ($row) {
                    $html = '';
                    foreach ($row->file as $key => $file) {
                        if ($file->type == 1) {
                            $html .= '<h6 mb-0 text-uppercase text-info>File Upload</h6><br>';
                        } elseif ($file->type == 2) {
                            $html .= '<h6 mb-0 text-uppercase text-info>Youtube Link</h6><br>';

                        } elseif ($file->type == 3) {
                            $html .= '<h6 mb-0 text-uppercase text-info>Video Upload</h6><br>';

                        }
                        $html .= '<a href="' . $file->file_url . '" target="_blank">' . ($key + 1) . '.' . $file->file_name . '</a>';
                    }

                    return $html;
                })
                ->removeColumn('id')
                ->rawColumns(['action', 'files'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if (!auth()->user()->can('lesson.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses = Campus::forDropdown();
        $class_subject = ClassSubject::with(['classes'])->find($id);
        $chapters = SubjectChapter::forDropdown($id);


        return view('Curriculum.lesson.create')->with(compact('campuses', 'class_subject', 'chapters'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('lesson.create')) {
            abort(403, 'Unauthorized action.');
        }
        //dd($request->input());
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:class_subject_lessons,name',
                'subject_id' => 'required|numeric',

                'file' => 'nullable|array',
                'file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                'file.*.name' => 'required_with:file.*.type',
                'file.*.thumbnail' => 'required_if:file.*.type,youtube_link,video_upload,other_link',
                'file.*.file' => 'required_if:file.*.type,file_upload,video_upload',
                // 'file.*.link' => 'required_if:file.*.type,youtube_link,other_link',
                //Regex for Youtube Link
                'file.*.link' => ['required', new YouTubeUrl, 'nullable'],
                //Regex for Other Link
                // 'file.*.link'=>'required_if:file.*.type,other_link|url'
            ],
            [
                'name.unique' => __('english.already_exists')
            ]
        );
        if ($validator->fails()) {

            $output = [
                'success' => false,
                'msg' => $validator->errors()->first(),
            ];
            return $output;
        }
        try {
            $input = $request->only(['campus_id', 'subject_id', 'name', 'chapter_id', 'description']);
            $user_id = $request->session()->get('user.id');
            $subject_lesson = new ClassSubjectLesson();
            $subject_lesson->name = $request->name;
            $subject_lesson->campus_id = $request->campus_id;
            $subject_lesson->subject_id = $request->subject_id;
            $subject_lesson->chapter_id = $request->chapter_id;
            $subject_lesson->description = $request->description;
            $subject_lesson->created_by = $user_id;

            $subject_lesson->save();
            foreach ($request->file as $key => $file) {
                if ($file['type']) {
                    $subject_lesson_file = new File();
                    $subject_lesson_file->file_name = $file['name'];
                    $subject_lesson_file->modal()->associate($subject_lesson);

                    if ($file['type'] == "file_upload") {
                        $subject_lesson_file->type = 1;
                        $subject_lesson_file->file_url = $file['file']->store('lessons', 'public');
                    } elseif ($file['type'] == "youtube_link") {
                        $subject_lesson_file->type = 2;
                        $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                        $subject_lesson_file->file_url = $file['link'];
                    } elseif ($file['type'] == "video_upload") {
                        $subject_lesson_file->type = 3;
                        $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                        $subject_lesson_file->file_url = $file['file']->store('lessons', 'public');
                    } elseif ($file['type'] == "other_link") {
                        $subject_lesson_file->type = 4;
                        $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                        $subject_lesson_file->file_url = $file['link'];
                    }
                    $subject_lesson_file->save();
                }
            }

            $output = [
                'success' => true,
                'data' => $subject_lesson,
                'msg' => __("english.added_success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")
            ];
        }

        return $output;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('lesson.view')) {
            abort(403, 'Unauthorized action.');
        }
        $class_subject = ClassSubject::with(['classes'])->find($id);
        $chapters = SubjectChapter::forDropdown($id);

        return view('Curriculum.lesson.index')->with(compact('class_subject', 'chapters'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('lesson.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $subject_lesson = ClassSubjectLesson::find($id);
            $class_subject = ClassSubject::where('id', $subject_lesson->subject_id)->first();
            $chapters = SubjectChapter::forDropdown($subject_lesson->subject_id);


            return view('Curriculum.lesson.edit')->with(compact('subject_lesson', 'class_subject', 'chapters'));
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
        if (!auth()->user()->can('lesson.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:class_subject_lessons,name,' . $id,
                    'chapter_id' => 'required|numeric',
                    'edit_file' => 'nullable|array',
                    'edit_file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                    'edit_file.*.name' => 'required_with:edit_file.*.type',
                    'edit_file.*.link' => 'required_if:edit_file.*.type,youtube_link,other_link',

                    // for Youtube Link
                    'edit_file.*.link' => ['required', new YouTubeUrl, 'nullable'],

                    'file' => 'nullable|array',
                    'file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                    'file.*.name' => 'required_with:file.*.type',
                    'file.*.thumbnail' => 'required_if:file.*.type,youtube_link,video_upload,other_link',
                    'file.*.file' => 'required_if:file.*.type,file_upload,video_upload',
                    'file.*.link' => 'required_if:file.*.type,youtube_link,other_link',

                    //Regex for Youtube Link
                    'file.*.link' => ['required', new YouTubeUrl, 'nullable'],
                    //Regex for Other Link
                    // 'file.*.link'=>'required_if:file.*.type,other_link|url'
                ],
                [
                    'chapter_name.unique' => __('english.already_exists')
                ]
            );
            if ($validator->fails()) {
                $output = [
                    'success' => false,
                    'msg' => $validator->errors()->first(),
                ];
                return $output;
            }
            //try {
            $input = $request->only(['name', 'chapter_id', 'description']);
            $subject_lesson = ClassSubjectLesson::findOrFail($id);
            $subject_lesson->name = $request->name;
            $subject_lesson->description = $request->description;
            $subject_lesson->chapter_id = $request->chapter_id;
            $subject_lesson->save();
            $subject_lesson->save();
            // Update the Old Files
            if ($request->edit_file) {
                foreach ($request->edit_file as $file) {
                    if ($file['type']) {
                        $subject_lesson_file = File::find($file['id']);
                        $subject_lesson_file->file_name = $file['name'];

                        if ($file['type'] == "file_upload") {
                            $subject_lesson_file->type = 1;
                            if (!empty($file['file'])) {
                                if (Storage::disk('public')->exists($subject_lesson_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($subject_lesson_file->getRawOriginal('file_url'));
                                }
                                $subject_lesson_file->file_url = $file['file']->store('chapters', 'public');
                            }
                        } elseif ($file['type'] == "youtube_link") {
                            $subject_lesson_file->type = 2;
                            if (!empty($file['thumbnail'])) {
                                if (Storage::disk('public')->exists($subject_lesson_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($subject_lesson_file->getRawOriginal('file_url'));
                                }
                                $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('chapters', 'public');
                            }

                            $subject_lesson_file->file_url = $file['link'];
                        } elseif ($file['type'] == "video_upload") {
                            $subject_lesson_file->type = 3;
                            if (!empty($file['file'])) {
                                if (Storage::disk('public')->exists($subject_lesson_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($subject_lesson_file->getRawOriginal('file_url'));
                                }
                                $subject_lesson_file->file_url = $file['file']->store('chapters', 'public');
                            }

                            if (!empty($file['thumbnail'])) {
                                if (Storage::disk('public')->exists($subject_lesson_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($subject_lesson_file->getRawOriginal('file_url'));
                                }
                                $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('chapters', 'public');
                            }
                        } elseif ($file['type'] == "other_link") {
                            $subject_lesson_file->type = 4;
                            if (!empty($file['thumbnail'])) {
                                if (Storage::disk('public')->exists($subject_lesson_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($subject_lesson_file->getRawOriginal('file_url'));
                                }
                                $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('chapters', 'public');
                            }
                            $subject_lesson_file->file_url = $file['link'];
                        }

                        $subject_lesson_file->save();
                    }
                }
            }
            if ($request->file) {

                foreach ($request->file as $key => $file) {
                    if ($file['type']) {
                        $subject_lesson_file = new File();
                        $subject_lesson_file->file_name = $file['name'];
                        $subject_lesson_file->modal()->associate($subject_lesson);

                        if ($file['type'] == "file_upload") {
                            $subject_lesson_file->type = 1;
                            $subject_lesson_file->file_url = $file['file']->store('lessons', 'public');
                        } elseif ($file['type'] == "youtube_link") {
                            $subject_lesson_file->type = 2;
                            $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $subject_lesson_file->file_url = $file['link'];
                        } elseif ($file['type'] == "video_upload") {
                            $subject_lesson_file->type = 3;
                            $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $subject_lesson_file->file_url = $file['file']->store('lessons', 'public');
                        } elseif ($file['type'] == "other_link") {
                            $subject_lesson_file->type = 4;
                            $subject_lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $subject_lesson_file->file_url = $file['link'];
                        }
                        $subject_lesson_file->save();
                    }
                }
            }
            $output = [
                'success' => true,
                'msg' => __("english.updated_success")
            ];
            // } catch (\Exception $e) {
            //     \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            //     $output = ['success' => false,
            //                 'msg' => __("english.something_went_wrong")
            //             ];
            // }

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

        if (!auth()->user()->can('lesson.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $subject_lesson = ClassSubjectLesson::findOrFail($id);
                if ($subject_lesson->file && count($subject_lesson->file) > 0) {
                    foreach ($subject_lesson->file as $file) {
                        if (Storage::disk('public')->exists($file->file_url)) {
                            Storage::disk('public')->delete($file->file_url);
                        }
                    }
                }
                $subject_lesson->file()->delete();
                $subject_lesson->delete();
                $output = [
                    'success' => true,
                    'msg' => __("english.deleted_success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
            }

            return $output;
        }
    }
}