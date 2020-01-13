@extends('layouts.app')

@section('content')

   <div class="card mx-auto">
        <div class="card-header">
        Email Settings

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        
        </div>
        <div class="card-body">

        @foreach($settings->where('id',1) as $setting)

                <form class="mb-3">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                            <label>Driver's Approver</label>                            
                        
                            <select class="form-control" disabled>
                                <option value="0" selected="selected">{{ $setting->user->name }}</option>
                            </select>

                            </div> 
                        </div>
                    </div>

                <a href="{{url('/settings/'.$setting->id.'/edit')}}" class="btn btn-secondary btn-block">Edit</a>

                </form>

        @endforeach

        @foreach($settings->where('id',2) as $setting)

                <form class="mb-3">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                            <label>Printing Email</label>                            
                        
                            <select class="form-control" disabled>
                                <option value="0" selected="selected">{{ $setting->user->name }}</option>
                            </select>

                            </div> 
                        </div>
                    </div>

                <a href="{{url('/settings/'.$setting->id.'/edit')}}" class="btn btn-secondary btn-block">Edit</a>

                </form>

        @endforeach

        {{--  @foreach($settings->where('id',3) as $setting)

                <form class="mb-3">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                            <label>Hauler's Approver</label>                            
                        
                            <select class="form-control" disabled>
                                <option value="0" selected="selected">{{ $setting->user->name }}</option>
                            </select>

                            </div> 
                        </div>
                    </div>

                <a href="{{url('/settings/'.$setting->id.'/edit')}}" class="btn btn-secondary btn-block">Edit</a>

                </form>

        @endforeach  --}}

           


        </div><!-- end card-body -->
    </div> <!-- end card -->


    <allowedCompany></allowedCompany>
 


@endsection


