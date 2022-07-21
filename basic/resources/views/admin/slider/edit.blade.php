@extends('admin.admin_master')


@section('admin')

    @if(session('done'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>{{ session('done') }}</strong> 
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="py-12">
        <div class="container">
            <div class="row">
     
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Update Slider</div>
                        <div class="card-body">


                <form action="{{ url('slider/update/'.$sliders->id) }} " method="POST" 
                enctype="multipart/form-data">
                            @csrf
    <input type="hidden" name="old_image" value="{{ $sliders->image }}">
  <div class="form-group">    
    <label for="exampleInputEmail1" class="form-label"> Slider Name</label>
    <input type="text" name="title" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
     value="{{$sliders->title}}">
    <div id="emailHelp" class="form-text"></div>

    @error('title')
       <span class="text-danger">{{$message}}</span>
    @enderror
  </div>

  <div class="form-group">  
  <label for="exampleInputEmail1" class="form-label"> Slider Description</label>
  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"
  value="{{$sliders->description}}"></textarea>
  
    <div id="emailHelp" class="form-text"></div>

    @error('discriptions')
       <span class="text-danger">{{$message}}</span>
    @enderror
  </div>


  
  <div class="mb-">    
    <label for="exampleInputEmail1" class="form-label">Upadate Brand Image</label>
    <input type="file" name="image" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
     value="{{ $sliders->image}}">
    <div id="emailHelp" class="form-text"></div>

    @error('image')
       <span class="text-danger">{{$message}}</span>
    @enderror
  </div>

  <div class="form-group">
    <img src="{{ asset($sliders->image) }}" style="width:400px; height:200px;" >
  </div>
  
  <button type="submit" class="btn btn-primary">Update Slider</button>
</form>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection