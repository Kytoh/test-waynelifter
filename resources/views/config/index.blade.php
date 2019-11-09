@extends('layouts.master')
@section('title', 'Config Steps')
@section('content')
    <form method="POST">
        @csrf
        <h3>Lifter Config</h3>
        <div class="genericConfig row m-3">
            <div class="form-group col-md-12">
                <label class="col-md-4">Lift Number</label>
                <input type="number" class="form-control" step="1" name="liftCount" value="{{ $config['liftCount'] }}">
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4">Floor Number (Excluding Ground Floor)</label>
                <input type="number" class="form-control" step="1" name="floorCount" value="{{ $config['floorCount'] }}">
            </div>
        </div>
        <h3>Lifter Steps</h3>
        <div class="stepsConfig row m-3">
            <div class="col-md-12 row">
                <div class="col-md-1">Number</div>
                <div class="col-md-2">Minute Interval</div>
                <div class="col-md-2">Start Time</div>
                <div class="col-md-2">End Time</div>
                <div class="col-md-2">Start Floor</div>
                <div class="col-md-2">End Floor</div>
                <div class="col-md-1">Actions</div>
            </div>
            @foreach($steps as $step)
                <div class="col-md-12 row steps step-{{$step->id}}">
                    <div class="col-md-1 form-group">
                        <input type="number" class="form-control stepId" disabled value="{{$step->id}}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="number" class="form-control minuteInterval" value="{{$step->minuteInterval}}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="time" class="form-control startTime" value="{{$step->startTime}}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="time" class="form-control endTime" value="{{$step->endTime}}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" class="form-control startFloor" value="{{$step->startFloor}}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" class="form-control endFloor" value="{{$step->endFloor}}">
                    </div>
                    <div class="col-md-1 form-group">
                        <button data-id="{{$step->id}}" class="deleteConfig btn btn-danger"> X</button>
                    </div>

                </div>
            @endforeach
            <span class="prependHere"></span>
            <div class="col-md-9">
                <button class="saveConfig btn btn-block btn-success m-3">Save Config & Steps</button>
            </div>
            <div class="col-md-3">
                <button class="newLine btn btn-block btn-warning m-3">Create new Step Line</button>
            </div>
        </div>
    </form>
@stop
