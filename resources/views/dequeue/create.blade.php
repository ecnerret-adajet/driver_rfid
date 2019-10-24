@extends('layouts.app')

@section('content')


<div class="content-header">
        <div class="row mb-2">
        <div class="col-6">
        <h1 class="m-0 text-dark">Dequeue</h1>
        </div><!-- /.col -->
        <div class="col-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">{{ Carbon\Carbon::today()->format('M d, Y') }}</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>


<div class="row mb-3">
    <div class="col-sm-12">
        <div class="card mx-auto">
            <div class="card-header">
                Dequeue an entry

                <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                Back
                </a>
            </div>
        <div class="card-body" id="form-truck">


        <table class="table py-3">
            <tbody>

                <tr>

                <td class="text-center">
                    <span class="display-4">
                     {{ $queueEntry->queue_number }}
                    </span>
                </td>

                <td>
                    <div class="row">
                        <div class="col-3">
                            <img class="rounded-circle mx-auto align-middle" style="height: 100px; width: auto;" src="{{asset('/storage/'. $queueEntry->avatar)}}" align="middle">
                        </div>
                        <div class="col-9">
                            {{ $queueEntry->driver_name }} <br/>
                            {{ $queueEntry->plate_number }} <br/>
                            {{ $queueEntry->hauler_name }} <br/>
                        </div>
                    </div>

                </td>
                <td>
                    <small class="text-uppercase text-muted">
                        LAST DR SUBMISSION
                    </small> <br/>
                        {{ $queueEntry->isDRCompleted }}
                        <br/>
                    <small class="text-uppercase text-muted">
                        TAPPED IN QUEUE
                    </small><br/>

                     {{ date('Y-m-d h:i:s A', strtotime($queueEntry->LocalTime)) }}

                </td>
              
            </tr>
            </tbody>
        </table>


            <create-dequeue queue_entry_id="{{$queueEntry->id }}"></create-dequeue>
        
        </div>
        </div>
    </div>
</div><!-- end row -->


@endsection