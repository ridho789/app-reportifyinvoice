@extends('layouts.base')
<!-- @section('title', 'Shipments') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 d-flex ms-auto">
            <div class="btn-group dropdown">
                <button type="button" class="btn bg-gradient-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    new shipment
                </button>
                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item border-radius-md" href="{{ url('/form_sea_shipment') }}">Shipment - Sea Freight</a></li>
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Air Freight</a></li>
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Singapore</a></li>
                </ul>
            </div>
            <div class="btn-group dropdown">
                <button class="btn bg-gradient-dark dropdown-toggle ms-2" type="button" id="dropdownImport" data-bs-toggle="dropdown" aria-expanded="false">
                    Import
                </button>
                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Sea Freight</a></li>
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Air Freight</a></li>
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Singapore</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <a href="#">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="text-gradient text-primary"><span id="status1" countto="21">21</span> <span class="text-xs ms-n2">TS</span></h1>
                        <h6 class="mb-0 font-weight-bolder">Sea Freight</h6>
                        <p class="opacity-8 mb-0 text-sm">S F</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 py-4 py-md-0">
            <a href="#">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="text-gradient text-primary"> <span id="status2" countto="44">44</span> <span class="text-xs ms-n1">TS</span></h1>
                        <h6 class="mb-0 font-weight-bolder">Air Freight</h6>
                        <p class="opacity-8 mb-0 text-sm">A F</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="#">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="text-gradient text-primary"><span id="status3" countto="87">87</span> <span class="text-xs ms-n2">TS</span></h1>
                        <h6 class="mb-0 font-weight-bolder">Singapore Freight</h6>
                        <p class="opacity-8 mb-0 text-sm">S I N</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection