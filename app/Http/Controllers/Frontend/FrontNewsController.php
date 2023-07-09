<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use Illuminate\Http\Request;
use App\Models\Frontend\FrontAboutUs;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontNewsController extends Controller
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
               $news = FrontNews::select(['title', 'id','date','description','status']);
   
               return DataTables::of($news)
                               ->addColumn(
                                   'action',
                                   function ($row) {
                                       $html= '<div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                                <ul class="dropdown-menu" style="">';
                                       $html .= '<li><a  href="' .action('Frontend\FrontNewsController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                       $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontNewsController@destroy', [$row->id]).'" class=" delete_news_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';
   
   
                                       $html .= '</ul></div>';
   
                                       return $html;
                                   }
                               )
                               
                               ->removeColumn('id')
                               ->rawColumns(['action','description'])
                               ->make(true);
           }
   
           return view('frontend.backend.news.index');
       }
   
   
 
   public function show($slug, $id){
      $data = FrontNews::where('id', $id)
     // ->orWhere('slug', $slug)
      ->firstOrFail();
      $nav=FrontNews::select('title','id','slug')->get();
      //dd();
    return view('frontend.news.show')->with(compact('data','nav'));

   }
   public function create()
   {
       return view('frontend.backend.news.create');
   }
   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','date','description','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $input['slug']=  Str::slug($input['title']);

           $date=$this->commonUtil->uf_date($input['date']);
           $input['date']=$date;
           FrontNews::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-news')->with('status', $output);
   }
   public function edit($id)
   {
       $news= FrontNews::find($id);
       return view('frontend.backend.news.edit')->with(compact('news'));
   }
   public function update(Request $request, $id)
   {
       try {
         $input = $request->only(['title','date','description','status']);
         if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           //dd($input);
           $news= FrontNews::find($id);
           $news->title= $input['title'];
           $news->slug=  Str::slug($input['title']);
           $news->status= $input['status'];
           $news->description= $input['description'];
           $date=$this->commonUtil->uf_date($input['date']);
           $input['date']=$date;
           $news->save();
           $output = ['success' => true,
           'msg' => __("english.updated_success")
];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-news')->with('status', $output);
   }


    
     public function destroy($id)
     {
         if (!auth()->user()->can('fee_head.delete')) {
             abort(403, 'Unauthorized action.');
         }
   
         if (request()->ajax()) {
             try {
                 $news= FrontNews::find($id);
                
                
                 $news->delete();
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
