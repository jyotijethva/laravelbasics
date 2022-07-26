<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Carbon;
use Image;
use App\Models\Multipic;
use Auth;



class BrandController extends Controller

// insert Brand
{

    public function __construct(){
        $this->middleware('auth');
      }
  
      
    public function AllBrand(){
        $brands = Brand::latest()->paginate(5);      
          return view('admin.brand.index' , compact('brands'));
    }

    public function StoreBrand(Request $request){
        $validatedData = $request->validate([
             'brand_name' => 'required|unique:brands|min:4',  
            'brand_image' => 'mimes:jpeg,bmp,png,jpg',

        ],  
    
        [
            'brand_name.required' => 'please Input Brand Name',
            'brand_image.min' => 'Brand longer then 4 char'
        ] );

        $brand_image = $request->file('brand_image');

        // $name_gen = hexdec(uniqid());
        // $img_ext = strtolower($brand_image->getClientOriginalExtension());

        // $img_name=$name_gen.'.'.$img_ext;
        // $up_location = 'image/brand/';
        // $last_img = $up_location.$img_name;
        // $brand_image->move($up_location,$img_name);

        $name_gen = hexdec(uniqid()).'.'.$brand_image->getClientOriginalExtension();
        Image::make($brand_image)->resize(300,300)->save('image/brand/'.$name_gen);

        $last_img = 'image/brand/'.$name_gen;

        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_image' => $last_img,
            'created_at' => Carbon::now()
        ]);

        return redirect()->back()->with('done','Brand inserted Sucessfully');
    }
//  Edit Brand
    public function Edit($id){
        $brands = Brand::find($id);
        return view('admin.brand.adit',compact('brands'));


    }
    // Upadate Brand

    public function Update(Request $request, $id){
        $validatedData = $request->validate([
            'brand_name' => 'required|min:4',  
           
       ],  
   
       [
           'brand_name.required' => 'please Input Brand Name',
           'brand_image.min' => 'Brand longer then 4 char'
       ]);
       $old_image= $request->old_image;

       $brand_image = $request->file('brand_image');
       if($brand_image){

       $name_gen = hexdec(uniqid());
       $img_ext = strtolower($brand_image->getClientOriginalExtension());
       $img_name=$name_gen.'.'.$img_ext;
       $up_location = 'image/brand/';
       $last_img = $up_location.$img_name;
       $brand_image->move($up_location,$img_name);

       unlink($old_image);
       Brand::find($id)->update([
           'brand_name' => $request->brand_name,
           'brand_image' => $last_img,
           'created_at' => Carbon::now()
       ]);

       return redirect()->back()->with('done','Brand Upadated Sucessfully');


       }
       else{
        Brand::find($id)->update([
            'brand_name' => $request->brand_name,
            'created_at' => Carbon::now()
        ]);
 
        return redirect()->back()->with('done','Brand Upadated Sucessfully');
 
       }

       
    }
    // Delete Brand 
     

    public function Delete($id){
        $image = Brand::find($id);
        $old_image = $image->brand_image;
        unlink($old_image);

        Brand::find($id)->delete();
        return redirect()->back()->with('done','Brand Deleted Sucessfully');


    }



    //// thai is for multi image

    public function Multpic() {
        $images = Multipic::all();
         return view('admin.multipic.index', compact('images'));
    }
    
  
    public function StoreImg(Request $request){
        $image = $request->file('image');

        foreach($image as $multi_img){

        

        $name_gen = hexdec(uniqid()).'.'.$multi_img->getClientOriginalExtension();
        Image::make($multi_img)->resize(300,300)->save('image/multi/'.$name_gen);

        $last_img = 'image/multi/'.$name_gen;

        Multipic::insert([
            
            'image' => $last_img,
            'created_at' => Carbon::now()
        ]);
    } //end of the foreach

        return redirect()->back()->with('done','Images inserted Sucessfully');

    }

    public function Logout(){
        Auth::logout();
        return Redirect()->route('login')->with('done','User Logout');
    }
}
