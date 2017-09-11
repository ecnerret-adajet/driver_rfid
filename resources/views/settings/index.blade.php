@extends('layouts.app')

@section('content')

   <div class="card mx-auto">
        <div class="card-header">
        Set approver's email

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        
        </div>
        <div class="card-body">

        @foreach($settings as $setting)

                <form>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                            <label>Approver's Email</label>                            
                        
                            <select class="form-control" disabled>
                                <option value="0" selected="selected">{{ $setting->user->name }}</option>
                            </select>

                            </div> 
                        </div>
                    </div>

                <a href="{{url('/settings/'.$setting->id.'/edit')}}" class="btn btn-primary btn-block">Edit</a>

                </form>

        @endforeach

           


        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection


