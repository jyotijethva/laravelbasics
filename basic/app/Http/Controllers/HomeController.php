<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Carbon;
use Image;
use Auth;

class HomeController extends Controller
{
    public function HomeSlider(){

        $sliders = Slider::latest()->get();
        return view('admin.slider.index',compact('sliders'));
    }

    public function AddSlider(){
         return view('admin.slider.create');

    }

     public function StoreSlider(Request $request){

        $slider_image = $request->file('image');

        $name_gen = hexdec(uniqid()).'.'.$slider_image->getClientOriginalExtension();
        Image::make($slider_image)->resize(1920,1088)->save('image/slider/'.$name_gen);

        $last_img = 'image/slider/'.$name_gen;

        Slider::insert([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $last_img,
            'created_at' => Carbon::now()
        ]);

        return redirect()->route('home.slider')->with('done','Slider Inserted Sucessfully');
    
     }

      //edit slider 
     public function Edit($id){
        $sliders = Slider::find($id);
        return view('admin.slider.edit',compact('sliders'));
     }

     //update slider
     public function Update(Request $request, $id){
        $validatedData = $request->validate([
            'title' => 'required|min:4',  
           
       ],  
   
       [
           'title.required' => 'please Input Slider Name',
           'image.min' => 'Brand longer then 4 char'
       ]);
       $old_image= $request->old_image;

       $slider_image = $request->file('image');
       if($slider_image){

       $name_gen = hexdec(uniqid());
       $img_ext = strtolower($slider_image->getClientOriginalExtension());
       $img_name=$name_gen.'.'.$img_ext;
       $up_location = 'image/slider/';
       $last_img = $up_location.$img_name;
       $slider_image->move($up_location,$img_name);

       unlink($old_image);
       Slider::find($id)->update([
        'title' => $request->title,
        'description' => $request->description,
        'image' => $last_img,
        'created_at' => Carbon::now()
       ]);

       return redirect()->back()->with('done','Slider Upadated Sucessfully');


       }
       else{
        Slider::find($id)->update([
            'title' => $request->title,
            'created_at' => Carbon::now()
        ]);
 
        return redirect()->back()->with('done','Slider Upadated Sucessfully');
 
       }
     
    }

      // Delete Slider
     

      public function Delete($id){
         $slider_image =  Slider::find($id);
         $old_image =  $slider_image->image;
         unlink($old_image);

        Slider::find($id)->delete();
        return redirect()->back()->with('done','Slider Deleted Sucessfully');


    }


}
 