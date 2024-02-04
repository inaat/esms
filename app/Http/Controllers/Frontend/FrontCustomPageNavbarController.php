<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontCustomPageNavbar;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontCustomPageNavbarController extends Controller
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

    public function index()
    {
  
        if (!auth()->user()->can('grade.view')) {
            abort(403, 'Unauthorized action.');
        }
       
        if (request()->ajax()) {
            $sliders = FrontCustomPageNavbar::select(['id','title','type','status']);

            return DataTables::of($sliders)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  href="' .action('Frontend\FrontCustomPageNavbarController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontCustomPageNavbarController@destroy', [$row->id]).'" class=" delete_event_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                          
                            ->removeColumn('id')
                            ->rawColumns(['action'])
                            ->make(true);
        }

        return view('frontend.backend.page_navbar.index');
}

public function create()
{
    if (!auth()->user()->can('grade.view')) {
        abort(403, 'Unauthorized action.');
    }
   
    return view('frontend.backend.page_navbar.create');
}

public function store(Request $request)
{
    try {
        $input = $request->only(['title','type','status']);
        if (!empty($request->input('status'))) {
            $input['status']='publish';
        }
        $input['slug']=  Str::slug($input['title']);
  
        FrontCustomPageNavbar::create($input);
        $output = ['success' => true,
                'msg' => __("english.added_success")
            ];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
        ];
    }
    return redirect('front-page-navbar')->with('status', $output);
}
public function edit($id)
{
    $navbar= FrontCustomPageNavbar::find($id);
    return view('frontend.backend.page_navbar.edit')->with(compact('navbar'));
    
}
public function update(Request $request, $id)
{
    try {
        $input = $request->only(['title','type']);
        if (!empty($request->input('status'))) {
            $input['status']='publish';
        } else {
            $input['status']='not_publish';
        }
        //dd($input);
        $navbar= FrontCustomPageNavbar::find($id);
        $navbar->title= $input['title'];
    
        $navbar->status= $input['status'];
        $navbar->type= $input['type'];
     
        $navbar->save();
        $output = ['success' => true,
        'msg' => __("english.updated_success")
];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
        ];
    }
    return redirect('front-page-navbar')->with('status', $output);
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
           $navbar= FrontCustomPageNavbar::find($id);
          
      
           $navbar->delete();
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