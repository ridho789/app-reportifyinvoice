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
                    <li><a class="dropdown-item border-radius-md" href="#" data-bs-toggle="modal" data-bs-target="#seafreightModal">Shipment - Sea Freight</a></li>
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Air Freight</a></li>
                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Shipment - Singapore</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal - Import Sea Shipment -->
    <div class="modal fade" id="seafreightModal" tabindex="-1" role="dialog" aria-labelledby="seafreightModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal text-md" id="seafreightModalLabel"><b>Import Shipment - Sea Freight</b></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('import-sea-shipment') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="basic-form custom_file_input">
                            @csrf
                                <input class="form-control" type="file" name="file" required>
                            @error('file')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary btn-md" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-primary btn-md">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <a href="{{ url('/list_sea_shipment') }}">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="text-gradient text-primary"><span id="status1" countto="{{ $seaShipment }}">{{ $seaShipment }}</span> <span class="text-xs ms-n2">TS</span></h1>
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