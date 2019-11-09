@extends('layouts.master')
@section('title', 'Welcome')

@section('content')
    <div class="col-md-12">
        <h2>Welcome</h2>
        to Wayne Lifter App commonly called The Welter.

        <p class="mt-5">Notes from the developer to the developer with love:
        <ul>
            <li>On Config Menu, you will be able to edit the elevators and the steps.</li>
            <li>The App allows up to 999 elevators (for your safety, please, don't try if its not extremely necessary, for the sake of your device)</li>
            <li>There is a 'Floor Number' variable, but currently its useless. It was though to what you could put on "start floor" and "end floor", but... new ticket)</li>
            <li>The app try to balance the use of every elevator (except when there are three elevators, currently dont know why, could be great to know, but beh... new ticket)</li>
            <li>Because if you create two new tickes, probably you will need to create a third...</li>
            <li>There is available a "noob report" here -> <a href="{{route('report.basic')}}">X</a>. But is so noob, so please, dont look at it. Here every step is isolated, so the app to first step, then restart, second step, then restart, etc.... Yup, very noob :) Don't kill me yet</li>
        </ul></p>
    </div>
@stop
