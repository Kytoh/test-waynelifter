@extends('layouts.master')
@section('title', 'Config Steps')
@section('content')
    <form method="POST">
        @csrf
        <h3>Lifter Config</h3>
        <div class="genericConfig row m-3">
            <div class="form-group col-md-12">
                <label class="col-md-4">Lift Number</label>
                <input type="number" step="1" name="liftCount" value="{{ $config['liftCount'] }}">
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-4">Floor Number (Excluding Ground Floor)</label>
                <input type="number" step="1" name="floorCount" value="{{ $config['floorCount'] }}">
            </div>
        </div>
        <h3>Lifter Steps</h3>
        <div class="stepsConfig row m-3">
            <div class="col-md-12 row">
                <div class="col-md-2">Minute Interval</div>
                <div class="col-md-2">Start Time</div>
                <div class="col-md-2">End Time</div>
                <div class="col-md-2">Start Floor</div>
                <div class="col-md-2">End Floor</div>
            </div>
            @foreach($steps as $step)
                <div class="col-md-12 row step-{{$step->id}}">
                    <div class="col-md-2">
                        <input type="number" value="{{$step->minuteInterval}}">
                    </div>
                    <div class="col-md-2">
                        <input type="time" value="{{$step->startTime}}">
                    </div>
                    <div class="col-md-2">
                        <input type="time" value="{{$step->endTime}}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" value="{{$step->startFloor}}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" value="{{$step->endFloor}}">
                    </div>
                </div>
            @endforeach
        </div>
        <button class="btn btn-block btn-success m-3">Save Config & Steps</button>
    </form>
@stop
