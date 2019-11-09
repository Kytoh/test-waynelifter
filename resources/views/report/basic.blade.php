@extends('layouts.master')

@section('content')

    @foreach($report as $stepId=>$step)
        <div class="col-md-12 row">
            <h2 class="col-md-12">Step {{$stepId}}</h2>
            @foreach($step['log'] as $log)
                <div class="col-md-2 row">Hora
                    @php
                    echo date('G:i', $log['currentTime'])
                    @endphp
                </div>
                <div class="col-md-10 row m-3">
                    <div class="col-md-3"></div>
                    <div class="col-md-2">Piso Inicial</div>
                    <div class="col-md-2">Piso Final</div>
                    <div class="col-md-2">Movimiento</div>
                    <div class="col-md-2">Suma Total</div>
                    @foreach($log['lifts'] as $id => $lift)
                        <div class="col-md-3">Ascensor Número {{ $id + 1 }}</div>
                        <div class="col-md-2">{{ $lift['initPosition'] }}</div>
                        <div class="col-md-2">{{ $lift['endPosition'] }}</div>
                        <div class="col-md-2">{{ $lift['MoveNow'] }}</div>
                        <div class="col-md-2">{{ $lift['MoveCount'] }}</div>
                    @endforeach
                </div>
            @endforeach

        </div>
    @endforeach
@stop
