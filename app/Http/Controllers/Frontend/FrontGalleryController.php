<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use App\Models\Frontend\FrontGalleryContent;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;
use App\Models\Sim;
use App\Models\Sms;

use File;

class FrontGalleryController extends Controller
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
       // $sims=Sim::get();
    //     $sims=Sim::whereNotNull('current_class')->get();
    //    // dd($sims);

    //     $ff=[];
    //     foreach ($sims as $sim) {
    //         $sms=Sms::where('ID_No', $sim->Reg_no)->first();
    //         if (!empty($sms)) {
               
    //             $up=Sim::where('Reg_no', $sim->Reg_no)->first();
    //             $up->current_class=$sms->Class;
    //             $up->Fee_before_discount=$sms->Fee_before_discount;
    //             $up->Dis_=$sms->Dis_;
    //             $up->Fee_After_Discount=$sms->Fee_After_Discount;
    //             $up->Arears=$sms->Arears;
    //             $up->others_balance=$sms->others_balance;
    //             $up->save();
    //             $ff[]=$sim->Reg_no;
    //         }
    //     }
// //         $ff=[];
//         foreach ($sims as $sim) {
//             $sms=Sms::where('Reg_no', $sim->Reg_no)->first();
//             if (!empty($sms)) {
               
//                 $up=Sim::where('Reg_no', $sim->Reg_no)->first();
//                 $up->SLC_Certificate=$sms->SLC_Certificate;
// if (!empty($sms->FORM_B)) {
//     $up->FORM_B=$sms->FORM_B;
// }
//                 $up->save();
//                 $ff[]=$sim->Reg_no;
//             }
//         }
        //dd($ff);
        if (!auth()->user()->can('grade.view')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
        if (request()->ajax()) {
            $galleries = FrontGalleryContent::select(['title', 'id','thumb_image','description','status']);

            return DataTables::of($galleries)
                            ->addColumn(
                                // 'action',
                                // '
                                // <div class="d-flex order-actions">
                                // <a href="{{action(\'Frontend\FrontGalleryController@addImage\', [$id])}}" class=""><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.add_photos")</a>
                                //     &nbsp;
                                // <button data-href="{{action(\'Frontend\FrontGalleryController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_gallery_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                                //     &nbsp;

                                // </div>
                                // '
                                //)
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  class="dropdown-item  " href="' . action('Frontend\FrontGalleryController@element', [$row->id]).'"><i class="fas fa-image" aria-hidden="true"></i>' . __("english.upload") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontGalleryController@edit', [$row->id]).'" class=" edit_gallery_button dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontGalleryController@destroy', [$row->id]).'" class=" delete_gallery_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                                ->editColumn('thumb_image', function ($row) {
                                    $image = file_exists(public_path('uploads/front_image/'.$row->thumb_image)) ? $row->thumb_image : 'default.jpg';
                                    $img=' <img src="'.url('uploads/front_image/' . $image).'" class="img-border " width="50" height="50" alt="" ></div>';
                                    return $img;
                                })
                            // ->editColumn('registration_date', function ($row) {
                            //     return $this->accountTransactionUtil->format_date($row->registration_date);
                            // })
                            ->removeColumn('id')
                            ->rawColumns(['action','thumb_image','description'])
                            ->make(true);
        }

        return view('frontend.backend.gallery.index');
    }

   public function SearchInGallery(Request $request)
   {
       $output = [];
       $title=$request->input('title');
       //dd($title);
       try {
           $query=FrontGalleryContent::select('title', 'id', 'slug', 'thumb_image', 'description');
           if (!empty($title)) {
               $query->orWhere('title', 'like', '%' . $title.'%');
           }
           $albums=$query->orderBy('id', 'desc')->get();
           //dd($albums);
           $output['html_content'] =  view('frontend.gallery.row-gallery')
           ->with(compact(
               'albums'
           ))
           ->render();
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output['success'] = false;
           $output['msg'] = __('english.something_went_wrong');
       }

       return $output;
   }
   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','description']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $filename=$this->commonUtil->uploadFile($request, 'thumb_image', 'front_image', 'image');
           $input['thumb_image']=$filename;
           $input['category_id']=1;
           $input['slug']=  Str::slug($input['title']);

           FrontGalleryContent::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('galleries')->with('status', $output);
   }
   public function create()
   {
       return view('frontend.backend.gallery.create');
   }

   public function edit($id)
   {
       $gallery= FrontGalleryContent::find($id);

       return view('frontend.backend.gallery.edit')->with(compact('gallery'));
       ;
   }
   public function update(Request $request, $id)
   {
       try {
           $input = $request->only(['title','description']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           $gallery= FrontGalleryContent::find($id);
           $gallery->title= $input['title'];
           $gallery->status= $input['status'];
           $gallery->description= $input['description'];
           $gallery->slug=  Str::slug($input['title']);
           if ($request->hasFile('thumb_image') && $request->file('thumb_image')->isValid()) {
               if (File::exists(public_path('uploads/front_image/'. $gallery->thumb_image))) {
                   File::delete(public_path('uploads/front_image/'. $gallery->thumb_image));

               }
               
               $filename=$this->commonUtil->uploadFile($request, 'thumb_image', 'front_image', 'image');
               $gallery->thumb_image=$filename;
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
       return $output;
   }


   public function addImage($id)
   {
       $gallery= FrontGalleryContent::find($id);
       $getJson = json_decode($gallery->elements, true);
       return view('frontend.backend.gallery.add_image')->with(compact('gallery', 'getJson'));
       ;
   }
   public function element($id)
   {
       $gallery= FrontGalleryContent::find($id);
       $getJson = json_decode($gallery->elements, true);
       return view('frontend.backend.gallery.element')->with(compact('gallery', 'getJson'));
       ;
   }

   public function storeElement(Request $request, $id)
   {
       try {
           $arr = [];
           $count = 1;
           $gallery= FrontGalleryContent::find($id);
           $getJson = json_decode($gallery->elements, true);
           if (!empty($getJson)) {
               if (!empty(array_keys($getJson))) {
                   $count = (max(array_keys($getJson))) + 1;
               }
               foreach ($getJson as $key => $value) {
                   $arr[$key] = array(
                       'image' => $value['image'],
                       'type' => $value['type'],
                       'date' => $value['date'],
                       'video_url' => $value['video_url'],
                   );
               }
           }
           $type=$request->input('type');
           $video_url=$request->input('video_url');

           if ($request->hasFile('thumb_image')) {
               $files=$request->file('thumb_image');
               foreach ($files as $file) {
                   $uploaded_file_name = null;
                   $new_file_name = time() . '_' . $file->getClientOriginalName();
                   if ($file->storeAs('front_image', $new_file_name)) {
                       $uploaded_file_name = $new_file_name;
                   }
                   $arr[$count] =  array(
                   'image' => $uploaded_file_name,
                   'type' => $type,
                   'video_url' => $video_url,
                   'date' => date("Y-m-d H:i:s"),
    );
                   $count++;
               }
           }
           $gallery->elements=json_encode($arr);
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
       return redirect('gallery-element/'.$gallery->id)->with('status', $output);
   }

   public function upload_delete($id, $elem_id)
   {
       try {
           $gallery= FrontGalleryContent::find($id);
           if (!empty($gallery->elements)) {
               $getJson = json_decode($gallery->elements, true);
               foreach ($getJson as $key => $value) {
                   if ($key == $elem_id) {
                       unset($getJson[$key]);

                       // delete gallery user image
                       $image = $value['image'];
                       if (File::exists(public_path('uploads/front_image/'. $image))) {
                           File::delete(public_path('uploads/front_image/'. $image));
                       }
                   }
               }
               $gallery->elements=json_encode($getJson);
               $gallery->save();
           }

           $output = ['success' => true,
           'msg' => __("english.deleted_success")
];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('gallery-element/'.$gallery->id)->with('status', $output);
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
              $gallery= FrontGalleryContent::find($id);
              if (!empty($gallery->elements)) {
                  $getJson = json_decode($gallery->elements, true);
                  foreach ($getJson as $key => $value) {
                      unset($getJson[$key]);
                      // delete gallery user image
                      $image = $value['image'];
                      if (File::exists(public_path('uploads/front_image/'. $image))) {
                          File::delete(public_path('uploads/front_image/'. $image));
                      }
                  }
              }
              if (File::exists(public_path('uploads/front_image/'. $gallery->thumb_image))) {
                  File::delete(public_path('uploads/front_image/'. $gallery->thumb_image));
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
