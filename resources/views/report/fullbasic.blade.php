@extends('layouts.master')

@section('content')

    @foreach($report as $stepId=>$step)
        <div class="col-md-12 row">
            <h2>Step {{$stepId}}</h2>
            @foreach($step['log'] as $log)
                <div class="col-md-12 row">Hora {{ $log['currentTime'] }}
                    <div class="row">
                        <div class="col-md-4">Ascensor</div>
                        <div class="col-md-2">Piso Inicial</div>
                        <div class="col-md-2">Piso Final</div>
                        <div class="col-md-2">Movimiento en Turno</div>
                        <div class="col-md-2">Movimiento Hoy</div>
                        @foreach($log['lifts'] as $id => $lift)
                            <div class="col-md-4">Ascensor Número {{ $id + 1 }}</div>
                            <div class="col-md-2">{{ $lift['initPosition'] }}</div>
                            <div class="col-md-2">{{ $lift['endPosition'] }}</div>
                            <div class="col-md-2">{{ $lift['MoveNow'] }}</div>
                            <div class="col-md-2">{{ $lift['MoveCount'] }}</div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    @endforeach
@stop
