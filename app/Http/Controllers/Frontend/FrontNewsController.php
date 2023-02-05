<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use Illuminate\Http\Request;

class FrontNewsController extends Controller
{
   
   public function index(){
      $news=FrontNews::get();
      //dd();
      $slider=FrontSlider::get();
    return view('frontend.index')->with(compact('slider','news'));;

   }
   public function create(){

    return view('frontend.about_us');

   }
   public function show($slug, $id){
      $data = FrontNews::where('id', $id)
     // ->orWhere('slug', $slug)
      ->firstOrFail();
      $nav=FrontNews::select('title','id','slug')->get();
      //dd();
    return view('frontend.news.show')->with(compact('data','nav'));

   }
  
   public function edit(){

      return view('frontend.exam_result');
  
     }
   public function update(){

      return view('frontend.event');
  
     }
    
   public function destroy(){

      return view('frontend.teachers');
  
     }
   
}
