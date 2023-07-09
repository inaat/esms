<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontAboutUs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontAboutController extends Controller
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

        if (request()->ajax()) {
            $sliders = FrontAboutUs::select(['title', 'id','image','home_title','description','status']);

            return DataTables::of($sliders)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  href="' .action('Frontend\FrontAboutController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontAboutController@destroy', [$row->id]).'" class=" delete_slider_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                            ->editColumn('image', function ($row) {
                                    $image = file_exists(public_path('uploads/front_image/'.$row->image)) ? $row->image : 'default.jpg';
                                    $img=' <img src="'.url('uploads/front_image/' . $image).'" class="img-border " width="50" height="50" alt="" ></div>';
                                    return $img;
                            })
                            ->removeColumn('id')
                            ->rawColumns(['action','image','description'])
                            ->make(true);
        }

        return view('frontend.backend.about.index');
    }


   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','home_title','description','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $input['slug']=  Str::slug($input['title']);

           $filename=$this->commonUtil->uploadFile($request, 'image', 'front_image', 'image');
           $input['image']=$filename;
           FrontAboutUs::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-abouts')->with('status', $output);
   }
   public function create()
   {
       return view('frontend.backend.about.create');
   }

   public function edit($id)
   {
       $about= FrontAboutUs::find($id);
       return view('frontend.backend.about.edit')->with(compact('about'));
       
   }
   public function update(Request $request, $id)
   {
       try {
           $input = $request->only(['title','home_title','description','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           //dd($input);
           $gallery= FrontAboutUs::find($id);
           $gallery->title= $input['title'];
           $gallery->home_title= $input['home_title'];
           $gallery->slug=  Str::slug($input['title'].$input['home_title']);
           $gallery->status= $input['status'];
           $gallery->description= $input['description'];
           if ($request->hasFile('image') && $request->file('image')->isValid()) {
               if (File::exists(public_path('uploads/front_image/'. $gallery->image))) {
                   File::delete(public_path('uploads/front_image/'. $gallery->image));

                   $filename=$this->commonUtil->uploadFile($request, 'image', 'front_image', 'image');
                   $gallery->image=$filename;
               }
           }
           $gallery->save();
           $output = ['success' => true,
           'msg' => __("english.updated_success")
];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-abouts')->with('status', $output);
   }


   /*
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      if (!auth()->user()->can('fee_head.delete')) {
          abort(403, 'Unauthorized action.');
      }

      if (request()->ajax()) {
          try {
              $gallery= FrontAboutUs::find($id);
             
              if (File::exists(public_path('uploads/front_image/'. $gallery->image))) {
                  File::delete(public_path('uploads/front_image/'. $gallery->image));
              }
              $gallery->delete();
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
}
