<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
    
        
   /*     
        $ranges = [ // the start of each age-range.
    
    '0-0' => 0,
    '1-1' => 1,
    '2-2' => 2,
    '3-3' => 3,
    '4-4' => 4,
    '5-5' => 5,
    '6-6' => 6,
    '7-7' => 7,
    '8-8' => 8,
    '9-9' => 9,
    '10-10' => 10,
    '11-11' => 11,
    '12-12' => 12,
    '13-13' => 13,
    '14-14' => 14,
    '15-15' => 15,
    '16-16' => 16,
    '17-17' => 17,
    '18-18' => 18,
    '19-19' => 19,
    '20-20' => 20,
    '21+' => 21
];
$classes=Classes::get();
$data=[];
foreach($classes as $cls){
$female = Student::where('status','active')->where('current_class_id',$cls->id)->where('gender','female')->
    get()
    ->map(function ($user) use ($ranges) {
        $age = Carbon::parse($user->birth_date)->age;
        foreach($ranges as $key => $breakpoint)
        {
            if ($breakpoint >= $age)
            {
                $user->range = $key;
                break;
            }
        }

        return $user;
    })
    ->mapToGroups(function ($user, $key) {
        return [$user->range => $user];
    })
    ->map(function ($group) {
        return count($group);
    })
    ->sortKeys();
    $male = Student::where('status','active')->where('current_class_id',$cls->id)->where('gender','male')->
    get()
    ->map(function ($user) use ($ranges) {
        $age = Carbon::parse($user->birth_date)->age;
        foreach($ranges as $key => $breakpoint)
        {
            if ($breakpoint >= $age)
            {
                $user->range = $key;
                break;
            }
        }

        return $user;
    })
    ->mapToGroups(function ($user, $key) {
        return [$user->range => $user];
    })
    ->map(function ($group) {
        return count($group);
    })
    ->sortKeys();
$data[]=['clasa_name'=>$cls->title,'male'=>$male,'female'=>$female];
//dd($output);
}
dd($data);*/
    
class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(view('sliders.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpeg,png,jpg|image|max:2048',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }

        try {
            $slider = new Slider();
            $slider->image = $request->file('image')->store('sliders', 'public');
            $slider->save();

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
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

        $sql = new Slider();
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('name', 'LIKE', "%$search%")->orwhere('mobile', 'LIKE', "%$search%");
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
            $operate = '<a href=' . route('sliders.edit', $row->id) . ' class="btn btn-xs btn-gradient-primary btn-rounded btn-icon edit-data" data-id=' . $row->id . ' title="Edit" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
            $operate .= '<a href=' . route('sliders.destroy', $row->id) . ' class="btn btn-xs btn-gradient-danger btn-rounded btn-icon delete-form" data-id=' . $row->id . '><i class="fa fa-trash"></i></a>';

            $tempRow['id'] = $row->id;
            $tempRow['no'] = $no++;
            $tempRow['image'] = $row->image;
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg|image|max:2048',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }

        try {
            $slider = Slider::find($id);
            if ($request->hasFile('image')) {
                if (Storage::disk('public')->exists($slider->image)) {
                    Storage::disk('public')->delete($slider->image);
                }
                $slider->image = $request->file('image')->store('subjects', 'public');
            }
            $slider->save();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $slider = Slider::find($id);
            if (Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $slider->delete();
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
}
