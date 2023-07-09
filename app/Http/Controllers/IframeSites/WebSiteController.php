<?php

namespace App\Http\Controllers\IframeSites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class WebSiteController extends Controller
{
   public function index(){

    return view('websites.index');
   }
   public function create(){

    return view('websites.create');
   }
}