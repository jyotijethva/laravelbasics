<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function __construct(){
      $this->middleware('auth');
    }

    public function AllCat(){
      //jion table using query builder using  Eloquent ORM
        $list2 = DB::table('categories')
        ->join('users','categories.user_id','users.id')
        ->select('categories.*' , 'users.name')
        ->latest()->paginate(5);
        $list2 =Category::latest()->paginate(5);
        $trachCat = Category::onlyTrashed()->latest()->paginate(3);
     //   using  Query Builder 
        // $list = DB::table('categories')->latest()->paginate(5);
        return view('admin.category.index', compact('list2','trachCat'));
    }

    public function AddCat(Request $request){
        $validatedData = $request->validate([
            'category_name' => 'required|unique:categories|max:255',
            
        ],
        [
            'category_name.required' => 'please Enter Catgory Name',
            
        ] );

        
          //insert data using eloquent ORM|(1)

         category::insert([
            'category_name' => $request->category_name,
             'user_id' => Auth::user()->id,
             'created_at' => Carbon::now()
        ]); 


         //insert data using eloquent ORM|(2)

        // $category = new Category;
        // $category->category_name = $request->category_name;
        // $category->user_id = Auth::user()->id; 
        // $category->save(); 
         
        //insert data using query builder
        // $data = array();
        // $data['category_name'] = $request->category_name;
        // $data['user_id'] = Auth::user()->id;
        // DB::table('categories')->insert($data);

        return Redirect()->back()->with('done','category inserted sucssfully');
    }


    public function Edit($id){
        $categories = Category::find($id);
        return view('admin.category.edit',compact('categories'));
    }


    public function Update(Request $request ,$id){

        $update = Category::find($id)->update([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id
        ]);
        return Redirect()->route('all.category')->with('done','category updated sucssfully');

    }


    public function softDelete($id){
        $delete = Category::find($id)->delete();
        return Redirect()->back()->with('done','Category Soft Delete Sucessfully');
    }


    public function Restore($id){
        $delete = Category::withTrashed()->find($id)->restore();
        return Redirect()->back()->with('done','Category restore Sucessfully');

    }
    

     public function Pdelete($id){
        $delete= Category::onlyTrashed()->find($id)->forceDelete();
        return Redirect()->back()->with('done','Category permanently 
        Deleted');
     }
    
}
