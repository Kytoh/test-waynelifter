@extends('layouts.master')
@section('title', 'Full Report')
@section('content')
    <div class="col-md-12">
        <h2>Full Report</h2>
    </div>
    @foreach($report as $log)
            <div class="col-md-2 row">Hora
                @php
                    echo date('G:i', $log['currentTime'])
                @endphp
            </div>
            <div class="col-md-10 row m-3">
                <div class="col-md-2"></div>
                <div class="col-md-2">Piso Inicial</div>
                <div class="col-md-2">Piso Llamada</div>
                <div class="col-md-2">Piso Final</div>
                <div class="col-md-2">Movimiento</div>
                <div class="col-md-2">Suma Total</div>
                @foreach($log['lifts'] as $id => $lift)
                    <div class="col-md-2">Ascensor Número {{ $id + 1 }}</div>
                    <div class="col-md-2">{{ $lift['initPosition'] }}</div>
                    <div class="col-md-2">{{ $lift['callPosition'] }}</div>
                    <div class="col-md-2">{{ $lift['endPosition'] }}</div>
                    <div class="col-md-2">{{ $lift['MoveNow'] }}</div>
                    <div class="col-md-2">{{ $lift['MoveCount'] }}</div>
                @endforeach
            </div>
    @endforeach
@stop
