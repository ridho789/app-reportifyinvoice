@extends('layouts.base')
<!-- @section('title', 'Sea Freight Shipment') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div>
            @if ($groupSeaShipmentLines)
            <div class="card mb-5">
                <div class="card-header pb-2">
                    <h6>Summary of Sea Freight Shipment</h6>
                    <p class="text-sm mb-0">
                        Summary of Sea Freight shipment.
                    </p>
                </div>
                <div class="card-body px-4 pt-0 pb-0 mb-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">No.</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">BL Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Total Packages</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Total Weight</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="3">Total CBM</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Description</th>
                                </tr>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">i</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ii</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">sf</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($groupSeaShipmentLines as $date => $gsl)
                                <tr>
                                    <td width=5%>
                                        <div class="d-flex px-3 py-1">
                                            <p class="text-xs text-secondary mb-0">{{ $loop->iteration }}.</p>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-M-y') }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">{{ $gsl['total_qty_pkgs'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">{{ $gsl['total_weight'] ?? '-' }}</span>
                                    </td>
                                    <!-- total cbm -->
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">{{ $gsl['total_cbm1'] ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">{{ $gsl['total_cbm2'] ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">{{ $gsl['cbm_difference'] ?? '-' }}</span>
                                    </td>
                                    <!-- ### -->
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mt-0 p-0">
                <!-- <div class="card-header pb-4">
                    <h6>Shipment Sea Freight</h6>
                    <p class="text-sm mb-0">
                        Main shipment sea freight.
                    </p>
                </div> -->
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow text-center border-radius-xl mt-n4 me-3 float-start">
                        <i class="material-icons opacity-10">event</i>
                    </div>
                    <h6 class="mb-0">Sea Freight Shipment</h6>
                    <p class="text-sm mb-0">
                        List of sea freight shipment.
                    </p>
                </div>
                <div class="card-body px-4 pt-0 pb-0">
                    @if ($seaShipment)
                    <form id="form-sea-freight" method="POST" action="{{ url('sea_shipment-update') }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Aju</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shipper</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ship</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Origin</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Ships</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Packages</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Weight</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Volume</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Etd</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Eta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width=10%>
                                            <div class="d-flex px-3 py-1">
                                                <input type="hidden" name="id_sea_shipment" value="{{ $seaShipment->id_sea_shipment }}">
                                                <input type="text" class="form-control" name="no_aju" value="{{ $seaShipment->no_aju ?? '-' }}" 
                                                oninput="this.value = this.value.toUpperCase()" placeholder="...">
                                            </div>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="date" value="{{ $seaShipment->date }}">
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-cust" name="id_customer" required>
                                                <option value="">...</option>
                                                @foreach ($customers as $c)
                                                <option value="{{ $c->id_customer }}" data-shipper-ids="{{ $c->shipper_ids }}" 
                                                    {{ old('id_customer', $seaShipment->id_customer) == $c->id_customer ? 'selected' : '' }}>{{ $c->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-shipper" name="id_shipper" required>
                                                <option value="">...</option>
                                                @foreach ($shippers as $s)
                                                <option value="{{ $s->id_shipper }}" 
                                                    {{ old('id_shipper', $seaShipment->id_shipper) == $s->id_shipper ? 'selected' : '' }}>{{ $s->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-ship" name="id_ship">
                                                <option value="">...</option>
                                                @foreach ($ships as $s)
                                                <option value="{{ $s->id_ship }}" 
                                                    {{ old('id_ship', $seaShipment->id_ship) == $s->id_ship ? 'selected' : '' }}>{{ $s->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=7.5%>
                                            <!-- <input type="text" class="form-control text-center" name="origin" value="{{ $seaShipment->origin ?? '-' }}" 
                                            oninput="this.value = this.value.toUpperCase()" placeholder="..."> -->
                                            <select class="form-select text-center text-xs" name="origin" style="border: 0px;" required>
                                                <option value="" {{ old('origin', $seaShipment->origin) == '' ? 'selected' : '' }}>...</option>
                                                <option value="BTH-JKT" {{ old('origin', $seaShipment->origin) == 'BTH-JKT' ? 'selected' : '' }}>BTH-JKT</option>
                                                <option value="BTH-SIN" {{ old('origin', $seaShipment->origin) == 'BTH-SIN' ? 'selected' : '' }}>BTH-SIN</option>
                                                <option value="SIN-BTH" {{ old('origin', $seaShipment->origin) == 'SIN-BTH' ? 'selected' : '' }}>SIN-BTH</option>
                                                <option value="SIN-JKT" {{ old('origin', $seaShipment->origin) == 'SIN-JKT' ? 'selected' : '' }}>SIN-JKT</option>
                                            </select>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_ships" placeholder="..." disabled>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_pkgs" placeholder="..." disabled>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_weight" placeholder="..." disabled>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_vol" placeholder="..." disabled>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="etd" value="{{ $seaShipment->etd }}" required>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="eta" value="{{ $seaShipment->eta }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive py-3 mt-3">
                            <!-- <h6>List of Shipment Sea Freight</h6> -->
                            <p class="text-sm mb-0">
                                <!-- List of sea freight shipment. -->
                            </p>
                            <table class="table table-bordered align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">No.</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">BL Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Code</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Marking</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="4">Quantity</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Weight <br> kg</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="3">Dimension</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="2">Total CBM</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Lartas</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Desc</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">State</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" colspan="2">Pkgs</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" colspan="2">Loose</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">p</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">l</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">t</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">i</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ii</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach($seaShipmentLines as $ssl)
                                    <tr>
                                        <input type="hidden" name="id_sea_shipment_line[]" value="{{ $ssl->id_sea_shipment_line }}">
                                        <td class="align-middle text-center text-sm" width=2.5%>
                                            <div class="d-flex px-3 py-1">
                                                {{ $loop->iteration }}.
                                            </div>
                                        </td>
                                        <td class="align-middle text-center" width=7.5%>
                                            <input type="date" class="form-control text-center" name="bldate[]" value="{{ $ssl->date }}" style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=7.5%>
                                            <input type="text" class="form-control text-center" name="code[]" value="{{ $ssl->code }}" 
                                            oninput="this.value = this.value.toUpperCase()" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=12.5%>
                                            <input type="text" class="form-control text-center" name="marking[]" value="{{ $ssl->marking ?? '-' }}" 
                                            oninput="this.value = this.value.toUpperCase()" placeholder="..." style="border: 0px;" required>
                                        </td>
                                        <!-- qty -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="qty_pkgs[]" value="{{ $ssl->qty_pkgs }}" 
                                            placeholder="..." style="border: 0px;" min="1">
                                        </td>
                                        <td class="align-middle" width=5%>
                                            <select class="form-select text-center text-xs" name="unit_qty_pkgs[]" style="border: 0px;">
                                                <option value="" {{ old('unit_qty_pkgs', $ssl->unit_qty_pkgs) == '' ? 'selected' : '' }}>-</option>
                                                <option value="cse" {{ old('unit_qty_pkgs', $ssl->unit_qty_pkgs) == 'cse' ? 'selected' : '' }}>CSE</option>
                                                <option value="ctn" {{ old('unit_qty_pkgs', $ssl->unit_qty_pkgs) == 'ctn' ? 'selected' : '' }}>CTN</option>
                                                <option value="pkg" {{ old('unit_qty_pkgs', $ssl->unit_qty_pkgs) == 'pkg' ? 'selected' : '' }}>PKG</option>
                                                <option value="plt" {{ old('unit_qty_pkgs', $ssl->unit_qty_pkgs) == 'plt' ? 'selected' : '' }}>PLT</option>
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="qty_loose[]" value="{{ $ssl->qty_loose }}" 
                                            placeholder="..." style="border: 0px;" min="1">
                                        </td>
                                        <td class="align-middle" width=5%>
                                            <select class="form-select text-center text-xs" name="unit_qty_loose[]" style="border: 0px;">
                                                <option value="" {{ old('unit_qty_loose', $ssl->unit_qty_loose) == '' ? 'selected' : '' }}>-</option>
                                                <option value="cse" {{ old('unit_qty_pkgs', $ssl->unit_qty_pkgs) == 'cse' ? 'selected' : '' }}>CSE</option>
                                                <option value="ctn" {{ old('unit_qty_loose', $ssl->unit_qty_loose) == 'ctn' ? 'selected' : '' }}>CTN</option>
                                                <option value="pkg" {{ old('unit_qty_loose', $ssl->unit_qty_loose) == 'pkg' ? 'selected' : '' }}>PKG</option>
                                                <option value="plt" {{ old('unit_qty_loose', $ssl->unit_qty_loose) == 'plt' ? 'selected' : '' }}>PLT</option>
                                            </select>
                                        </td>
                                        <!-- ### -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="weight[]" value="{{ $ssl->weight }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- dimension -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="p[]" value="{{ $ssl->dimension_p ?? '-' }}" 
                                            placeholder="..." style="border: 0px;" min="1" required>
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="l[]" value="{{ $ssl->dimension_l ?? '-' }}" 
                                            placeholder="..." style="border: 0px;" min="1" required>
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="t[]" value="{{ $ssl->dimension_t ?? '-' }}" 
                                            placeholder="..." style="border: 0px;" min="1" required>
                                        </td>
                                        <!-- ### -->
                                        <!-- total cbm -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="cbm1[]" value="{{ $ssl->tot_cbm_1 }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="cbm2[]" value="{{ $ssl->tot_cbm_2 }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- ### -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="lts[]" value="{{ $ssl->lts }}" 
                                            oninput="this.value = this.value.toUpperCase()" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="desc[]" value="{{ $ssl->desc }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle" width=7.5%>
                                            <select class="form-select text-center text-xs" name="state[]" style="border: 0px;">
                                                <option value="" {{ old('state', $ssl->state) == '' ? 'selected' : '' }}>-</option>
                                                <option value="hold" {{ old('state', $ssl->state) == 'hold' ? 'selected' : '' }}>HOLD</option>
                                                <option value="continue" {{ old('state', $ssl->state) == 'continue' ? 'selected' : '' }}>CONTINUE</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            <button type="button" class="btn btn-secondary btn-sm ms-2 btn-print" data-bs-toggle="modal" data-bs-target="#setPrintModal">Print</button>
                        </div>
                    </form>
                    @else
                    <form id="form-sea-freight" method="POST" action="#">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Aju</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shipper</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ship</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Origin</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Ships</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Packages</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Weight</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Volume</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Etd</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Eta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width=10%>
                                            <div class="d-flex px-3 py-1">
                                                <input type="text" class="form-control" name="number" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                                            </div>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="dates">
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-cust" name="id_customer">
                                                <option value="">...</option>
                                                @foreach ($customers as $c)
                                                <option value="{{ $c->id_customer }}" data-shipper-ids="{{ $c->shipper_ids }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-shipper" name="id_shipper">
                                                <option value="">...</option>
                                                @foreach ($shippers as $s)
                                                <option value="{{ $s->id_shipper }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-ship" name="id_ship">
                                                <option value="">...</option>
                                                @foreach ($ships as $s)
                                                <option value="{{ $s->id_ship }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=7.5%>
                                            <!-- <input type="text" class="form-control text-center" name="origin" oninput="this.value = this.value.toUpperCase()" placeholder="..."> -->
                                            <select class="form-select text-center text-xs" name="origin" style="border: 0px;">
                                                <option value="">...</option>
                                                <option value="BTH-JKT">BTH-JKT</option>
                                                <option value="BTH-SIN">BTH-SIN</option>
                                                <option value="SIN-BTH">SIN-BTH</option>
                                                <option value="SIN-JKT">SIN-JKT</option>
                                            </select>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_ships" placeholder="..." readonly>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_pkgs" placeholder="..." readonly>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_weight" placeholder="..." readonly>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="tot_vol" placeholder="..." readonly>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="etd" required>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="eta">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive py-3 mt-3">
                            <!-- <h6>List of Shipment Sea Freight</h6> -->
                            <p class="text-sm mb-0">
                                <!-- List of sea freight shipment. -->
                            </p>
                            <table class="table table-bordered align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">No.</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">BL Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Code</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Marking</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="4">Quantity</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Weight <br> kg</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="3">Dimension</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="2">Total CBM</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Lartas</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Desc</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">State</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" colspan="2">Pkgs</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" colspan="2">Loose</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">p</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">l</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">t</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">i</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ii</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td width=2.5%>
                                            <div class="d-flex px-3 py-1">
                                                <input type="text" class="form-control text-center" placeholder="..." style="border: 0px;">
                                            </div>
                                        </td>
                                        <td class="align-middle text-center" width=7.5%>
                                            <input type="date" class="form-control text-center" name="bldate[]" style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=7.5%>
                                            <input type="text" class="form-control text-center" name="code[]" oninput="this.value = this.value.toUpperCase()" 
                                            placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <input type="text" class="form-control text-center" name="marking[]" oninput="this.value = this.value.toUpperCase()" 
                                            placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- qty -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="qty_pkgs[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <select class="form-select text-center text-xs" name="unit_qty_pkgs[]" style="border: 0px;">
                                                <option value="">...</option>
                                                <option value="cse">CSE</option>
                                                <option value="ctn">CTN</option>
                                                <option value="pkg">PKG</option>
                                                <option value="plt">PLT</option>
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="qty_loose[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle" width=5%>
                                            <select class="form-select text-center text-xs" name="unit_qty_loose[]" style="border: 0px;">
                                                <option value="">...</option>
                                                <option value="cse">CSE</option>
                                                <option value="ctn">CTN</option>
                                                <option value="pkg">PKG</option>
                                                <option value="plt">PLT</option>
                                            </select>
                                        </td>
                                        <!-- ### -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="weight[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- dimension -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="p[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="l[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="number" class="form-control text-center" name="t[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- ### -->
                                        <!-- total cbm -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="cbm1[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="cbm2[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- ### -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="lts[]" oninput="this.value = this.value.toUpperCase()" 
                                            placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="desc[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle" width=7.5%>
                                            <select class="form-select text-center text-xs" name="state[]" style="border: 0px;">
                                                <option value="">...</option>
                                                <option value="hold">HOLD</option>
                                                <option value="continue">CONTINUE</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                    @endif 
                </div>
            </div>
        </div>
    </div>
</div>
@if ($seaShipment)
<!-- Modal - SetPrint -->
<div class="modal fade" id="setPrintModal" tabindex="-1" role="dialog" aria-labelledby="setPrintModalLabel" aria-hidden="true">
    @if (in_array($seaShipment->origin, ['SIN-BTH', 'SIN-JKT']))
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    @else
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    @endif
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal text-xs" id="setPrintModalLabel"><i class="material-icons text-xs">priority_high</i>
                    <b>Before printing the document, make sure you've filled in all the required (<span class="text-primary">*</span>) data.</b>
                </h5>
            </div>
            <form action="{{ url('print-sea-shipment') }}" method="POST" enctype="multipart/form-data" target="_blank">
                @csrf
                <div class="modal-body">
                    @if (in_array($seaShipment->origin, ['SIN-BTH', 'SIN-JKT']))
                        <div class="row">
                            <div class="col-6">
                                <h5 class="text-sm">Form</h5>
                                <input type="hidden" name="id" value="{{ $seaShipment->id_sea_shipment }}">
                                <div class="input-group input-group-static mb-4">
                                    <label>Invoice No. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="inv_no" value="{{ old('inv_no', $seaShipment->id_sea_shipment) }}" placeholder="..." required>
                                </div>
            
                                <div class="input-group input-group-static mb-1">
                                    <label class="text-sm">Company <span class="text-danger">*</span></label>
                                </div>
                                <div class="input-group input-group-static mb-4">
                                    <select class="form-select select-company" name="id_company" style="border: none; border-bottom: 1px solid #ced4da; border-radius: 0px;" required>
                                        <option value="">...</option>
                                        @foreach ($companies as $c)
                                        <option value="{{ $c->id_company }}" {{ old('id_company', $customer->id_company) == $c->id_company ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="input-group input-group-static mb-4">
                                    <div class="col-5">
                                        <label>Term <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="term" name="term" min="1" value="{{ old('term', $seaShipment->term) }}" placeholder="..." required>
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-6">
                                        <label>Payment Due</label>
                                        <input type="date" class="form-control" id="payment_due" name="payment_due" value="{{ old('payment_due', $seaShipment->etd) }}" readonly>
                                    </div>
                                </div>
            
                                <div class="input-group input-group-static">
                                    <div class="col-5">
                                        <label>Banker (<span class="text-info">Opt</span>)</label>
                                        <input type="text" class="form-control" name="banker" value="{{ old('banker') }}" placeholder="...">
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-6">
                                        <label>Account No. (<span class="text-info">Opt</span>)</label>
                                        <input type="text" class="form-control" name="account_no" value="{{ old('account_no') }}" placeholder="...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                @if ($groupSeaShipmentLines)
                                    <h5 class="text-sm">Fee Setup</h5>
                                    @if ($checkCbmDiff) 
                                    <div class="input-group input-group-static mb-4">
                                        <label>Difference Bill <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="bill_diff" id="bill_diff" value="{{ old('bill_diff', $customer->bill_diff) }}" 
                                        placeholder="..." required>
                                    </div>
                                    @endif
                                    
                                    <div class="input-group input-group-static mb-1">
                                        <label class="text-sm">Invoice Type <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <select class="form-select text-left" id="inv_type" name="inv_type" 
                                        style="border: none; border-bottom: 1px solid #ced4da; border-radius: 0px; {{ $checkCbmDiff ? '' : 'margin-top: -4px;' }}" required>
                                            <option value="" {{ old('inv_type', $customer->inv_type) == '' ? 'selected' : '' }}>...</option>
                                            <option value="basic" {{ old('inv_type', $customer->inv_type) == 'basic' ? 'selected' : '' }}>Basic</option>
                                            <option value="separate" {{ old('inv_type', $customer->inv_type) == 'separate' ? 'selected' : '' }}>Separate</option>
                                        </select>
                                    </div>
                                    
                                    <div class="accordion-1">
                                        <div class="row">
                                            <div class="accordion" id="accordionRental">
                                                @foreach ($groupSeaShipmentLines as $date => $gsl)
                                                @php
                                                    $checkSeaShipmentBill = null;
                                                    if ($seaShipmentBill) {
                                                        $checkSeaShipmentBill = $seaShipmentBill->where('date', $date)->first();
                                                    }
                                                @endphp
                                                <div class="accordion-item" style="margin-bottom: 23.5px;">
                                                    <div class="accordion-header" id="headingOne-{{ $loop->index }}">
                                                        @php
                                                            $marginTop = $checkCbmDiff ? '-9.5px' : '-8.75px';
                                                        @endphp
                                                        <label style="margin-left: 0; margin-bottom: 7.5px;">Other Fees</label>
                                                        <button class="accordion-button border-bottom font-weight-bold collapsed" type="button" data-bs-toggle="collapse" 
                                                            data-bs-target="#collapseOne-{{ $loop->index }}" aria-expanded="false" aria-controls="collapseOne-{{ $loop->index }}" 
                                                            style="padding: 0.5rem; margin-top: {{ $marginTop }}; margin-bottom: 8.5px;">
                                                            <label class="font-weight-normal" style="margin-bottom: 2px; margin-left: -7.5px;">
                                                                <b style="color: #344767;">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-M-y') }}</b>
                                                                <input type="hidden" class="form-control" id="dateBL" name="dateBL[]" value="{{ $date }}">
                                                            </label>
                                                            <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3 collapse-icon" aria-hidden="true"></i>
                                                            <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3 collapse-icon d-none" aria-hidden="true"></i>
                                                        </button>
                                                    </div>

                                                    <div id="collapseOne-{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="headingOne-{{ $loop->index }}" 
                                                    data-bs-parent="#accordionRental">
                                                        <div class="accordion-body">
                                                            <div class="input-group input-group-static mb-4">
                                                                <div class="col-5">
                                                                    <label>Code (<span class="text-info">Opt</span>)</label>
                                                                    <input type="text" class="form-control" id="codeShipment-{{ $loop->index }}" name="codeShipment[]" 
                                                                    value="{{ old('codeShipment', $checkSeaShipmentBill ? $checkSeaShipmentBill->code : '') }}" 
                                                                    oninput="this.value = this.value.toUpperCase()" placeholder="...">
                                                                </div>
                                                                <div class="col-1"></div>
                                                                <div class="col-6">
                                                                    <label>Transport Bill (<span class="text-info">Opt</span>)</label>
                                                                    <input type="text" class="form-control transport" name="transport[]" id="transport-{{ $loop->index }}" 
                                                                    value="{{ old('transport', $checkSeaShipmentBill ? $checkSeaShipmentBill->transport : '') }}" placeholder="...">
                                                                </div>
                                                            </div>

                                                            <div class="input-group input-group-static mb-0">
                                                                <div class="col-3">
                                                                    <label>BL (<span class="text-info">Opt</span>)</label>
                                                                    <input type="text" class="form-control bl" id="bl-{{ $loop->index }}" name="bl[]" 
                                                                    value="{{ old('bl', $checkSeaShipmentBill ? $checkSeaShipmentBill->bl : '') }}" placeholder="...">
                                                                </div>
                                                                <div class="col-1"></div>
                                                                <div class="col-3">
                                                                    <label>Permit (<span class="text-info">Opt</span>)</label>
                                                                    <input type="text" class="form-control permit" id="permit-{{ $loop->index }}" name="permit[]" 
                                                                    value="{{ old('permit', $checkSeaShipmentBill ? $checkSeaShipmentBill->permit : '') }}" placeholder="...">
                                                                </div>
                                                                <div class="col-1"></div>
                                                                <div class="col-4">
                                                                    <label>Insurance (<span class="text-info">Opt</span>)</label>
                                                                    <input type="text" class="form-control insurance" id="insurance-{{ $loop->index }}" name="insurance[]" 
                                                                    value="{{ old('insurance', $checkSeaShipmentBill ? $checkSeaShipmentBill->insurance : '') }}" placeholder="...">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <h5 class="text-sm">Form</h5>
                        <input type="hidden" name="id" value="{{ $seaShipment->id_sea_shipment }}">
                        <div class="input-group input-group-static mb-4">
                            <label>Invoice No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="inv_no" value="{{ old('inv_no', $seaShipment->id_sea_shipment) }}" placeholder="..." required>
                        </div>
    
                        <div class="input-group input-group-static mb-1">
                            <label class="text-sm">Company <span class="text-danger">*</span></label>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <select class="form-select select-company" name="id_company" style="border: none; border-bottom: 1px solid #ced4da; border-radius: 0px;" required>
                                <option value="">...</option>
                                @foreach ($companies as $c)
                                <option value="{{ $c->id_company }}" {{ old('id_company', $customer->id_company) == $c->id_company ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="input-group input-group-static mb-4">
                            <div class="col-5">
                                <label>Term <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="term" name="term" min="1" value="{{ old('term') }}" placeholder="..." required>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <label>Payment Due</label>
                                <input type="date" class="form-control" id="payment_due" name="payment_due" value="{{ old('payment_due', $seaShipment->etd) }}" readonly>
                            </div>
                        </div>
    
                        <div class="input-group input-group-static">
                            <div class="col-5">
                                <label>Banker (<span class="text-info">Optional</span>)</label>
                                <input type="text" class="form-control" name="banker" value="{{ old('banker') }}" placeholder="...">
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <label>Account No. (<span class="text-info">Optional</span>)</label>
                                <input type="text" class="form-control" name="account_no" value="{{ old('account_no') }}" placeholder="...">
                            </div>
                        </div>
                    @endif
                    
                    <div class="text-end mt-3">
                        <button type="button" class="btn bg-gradient-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-gradient-primary btn-sm ms-1">Process</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<script>
    // select2
    $(document).ready(function() {
        $('.select-cust').select2();
        $('.select-shipper').select2();
        $('.select-ship').select2({
            tags: true
        });
    });

    $('.select-cust').change(function() {
        var selectedCustomerId = $(this).val();
        var selectedCustomerShipperIds = $('option:selected', this).data('shipper-ids');

        var $selectShipper = $('.select-shipper');
        $('.select-shipper').val(null).trigger('change');
        $selectShipper.find('option').prop('disabled', true);

        if (selectedCustomerShipperIds) {
            if (typeof selectedCustomerShipperIds === 'string') {
                var shipperIdsArray = selectedCustomerShipperIds.split(',');

                $.each(shipperIdsArray, function(index, value) {
                    var shipperName = $('option[value="' + value + '"]', $selectShipper).text();
                    var existingOption = $selectShipper.find('option[value="' + value + '"]');
                    if (existingOption.length) {
                        existingOption.prop('disabled', false);
                    }
                });

            } else {
                var shipperId = selectedCustomerShipperIds;
                var shipperName = $('option[value="' + shipperId + '"]', $selectShipper).text();
                var existingOption = $selectShipper.find('option[value="' + shipperId + '"]');
                if (existingOption.length) {
                    existingOption.prop('disabled', false);
                }
            }
        }
    });

    // Function to update total ships
    function updateTotalShips() {
        var blDates = [];
        var rows = document.querySelectorAll('input[name="bldate[]"]');

        rows.forEach(function(row) {
            if (row.value.trim() !== '') {
                blDates.push(row.value.trim());
            }
        });

        var uniqueBlDates = Array.from(new Set(blDates));
        document.querySelector('input[name="tot_ships"]').value = uniqueBlDates.length;
    }

    updateTotalShips();

    var blDateInputs = document.querySelectorAll('input[name="bldate[]"]');
    blDateInputs.forEach(function(input) {
        input.addEventListener('change', updateTotalShips);
    });

    // Function to update total packages
    function calculateTotalPackages() {
        var totalPackages = 0;
        var rows = document.querySelectorAll('input[name="qty_pkgs[]"]');
        
        rows.forEach(function(row) {
            if (row.value.trim() !== '') {
                totalPackages += parseInt(row.value) || 0;
            }
        });

        document.querySelector('input[name="tot_pkgs"]').value = totalPackages;
    }

    calculateTotalPackages();

    var qtyPkgInputs = document.querySelectorAll('input[name="qty_pkgs[]"]');
    qtyPkgInputs.forEach(function(input) {
        input.addEventListener('change', calculateTotalPackages);
    });

    // Function to update total weight
    function calculateTotalWeight() {
        var totalWeight = 0;
        var rows = document.querySelectorAll('input[name="weight[]"]');
        
        rows.forEach(function(row) {
            if (row.value.trim() !== '') {
                totalWeight += parseFloat(row.value) || 0;
            }
        });

        document.querySelector('input[name="tot_weight"]').value = totalWeight;
    }

    calculateTotalWeight();

    // Function to update total volume
    function calculateTotalVolume() {
        var totalVolume = 0;
        var rows = document.querySelectorAll('input[name="cbm2[]"]');
        
        rows.forEach(function(row) {
            if (row.value.trim() !== '') {
                totalVolume += parseFloat(row.value) || 0;
            }
        });

        document.querySelector('input[name="tot_vol"]').value = totalVolume.toFixed(3);
    }

    calculateTotalVolume();

    var qtyPkgInputs = document.querySelectorAll('input[name="weight[]"]');
    qtyPkgInputs.forEach(function(input) {
        input.addEventListener('change', calculateTotalWeight);
    });

    // Update CBM
    var inputsP = document.querySelectorAll('input[name="p[]"]');
    var inputsL = document.querySelectorAll('input[name="l[]"]');
    var inputsT = document.querySelectorAll('input[name="t[]"]');

    inputsP.forEach(function(input, index) {
        input.addEventListener('input', function() {
            updateCBM(index);
        });
    });

    inputsL.forEach(function(input, index) {
        input.addEventListener('input', function() {
            updateCBM(index);
        });
    });

    inputsT.forEach(function(input, index) {
        input.addEventListener('input', function() {
            updateCBM(index);
        });
    });

    function updateCBM(index) {
        var row = inputsP[index].closest('tr');
        var p = parseFloat(inputsP[index].value);
        var l = parseFloat(inputsL[index].value);
        var t = parseFloat(inputsT[index].value);

        var volume = ((p * l * t) / 1000000).toFixed(3);

        var inputCBM1 = row.querySelector('input[name="cbm1[]"]');
        var inputCBM2 = row.querySelector('input[name="cbm2[]"]');

        var inputQtyPkgs = row.querySelector('input[name="qty_pkgs[]"]');
        var inputQtyLoose = row.querySelector('input[name="qty_loose[]"]');

        if (inputQtyPkgs.value && inputQtyPkgs.value != '-') {
            inputCBM1.value = volume * inputQtyPkgs.value;
        } else {
            inputCBM1.value = '';
        }

        if (inputQtyLoose.value && inputQtyLoose.value != '-') {
            inputCBM2.value = volume * inputQtyLoose.value;
        } else {
            inputCBM2.value = '';
        }
    }

    // set value unit qty pkgs and unit qty looose
    var QtyPkgs = document.querySelectorAll('input[name="qty_pkgs[]"]');
    QtyPkgs.forEach(function(input, index) {
        input.addEventListener('change', function() {
            // update CBM
            updateCBM(index);

            var row = input.closest('tr');
            var unitQtyPkgs = row.querySelector('select[name="unit_qty_pkgs[]"]');

            if (!input.value || input.value === '0') {
                unitQtyPkgs.value = '';
                unitQtyPkgs.removeAttribute('required');

            } else {
                unitQtyPkgs.setAttribute('required', 'required');
            }
        });
    });

    var QtyLoose = document.querySelectorAll('input[name="qty_loose[]"]');
    QtyLoose.forEach(function(input, index) {
        input.addEventListener('change', function() {
            // update CBM
            updateCBM(index);
            
            var row = QtyLoose[index].closest('tr');
            var unitQtyLoose = row.querySelector('select[name="unit_qty_loose[]"]');

            if (!input.value || input.value === '0') {
                unitQtyLoose.value = '';
                unitQtyLoose.removeAttribute('required');

            }else {
                unitQtyLoose.setAttribute('required', 'required');
            }
        });
    });

    // Function to add days to a date
    function addDays(date, days) {
        var result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    // Original payment due date
    var originalPaymentDue = document.getElementById('payment_due').value;

    // Function to update payment due date based on term
    function updatePaymentDue() {
        var term = parseInt(document.getElementById('term').value);

        if (!isNaN(term) && term > 0) {
            var newPaymentDue = addDays(originalPaymentDue, term);
            document.getElementById('payment_due').valueAsDate = newPaymentDue;

        } else {
            document.getElementById('payment_due').valueAsDate = new Date(originalPaymentDue);
        }
    }

    // Event listener for term input
    document.getElementById('term').addEventListener('input', updatePaymentDue);

    // Currency
    function formatCurrency(num) {
        num = num.toString().replace(/[^\d-]/g, '');

        num = num.replace(/-+/g, (match, offset) => offset > 0 ? "" : "-");

        let isNegative = false;
        if (num.startsWith("-")) {
            isNegative = true;
            num = num.slice(1);
        }

        let formattedNum = "Rp " + Math.abs(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        if (isNegative) {
            formattedNum = "-" + formattedNum;
        }

        return formattedNum;
    }

    document.addEventListener('DOMContentLoaded', function() {
        function formatInputs() {
            let priceBillDiff = document.querySelectorAll("#bill_diff");
            priceBillDiff.forEach(function(billDiff) {
                if (billDiff.value.trim() !== "") {
                    billDiff.value = formatCurrency(billDiff.value);
                }
                billDiff.addEventListener("input", function() {
                    if (this.value.trim() !== "") {
                        this.value = formatCurrency(this.value);
                    }
                });
            });

            let priceBillTransport = document.querySelectorAll(".transport");
            priceBillTransport.forEach(function(billTransport) {
                if (billTransport.value.trim() !== "") {
                    billTransport.value = formatCurrency(billTransport.value);
                }
                billTransport.addEventListener("input", function() {
                    if (this.value.trim() !== "") {
                        this.value = formatCurrency(this.value);
                    }
                });
            });

            let pricebl = document.querySelectorAll(".bl");
            pricebl.forEach(function(bl) {
                if (bl.value.trim() !== "") {
                    bl.value = formatCurrency(bl.value);
                }
                bl.addEventListener("input", function() {
                    if (this.value.trim() !== "") {
                        this.value = formatCurrency(this.value);
                    }
                });
            });

            let pricepermit = document.querySelectorAll(".permit");
            pricepermit.forEach(function(permit) {
                if (permit.value.trim() !== "") {
                    permit.value = formatCurrency(permit.value);
                }
                permit.addEventListener("input", function() {
                    if (this.value.trim() !== "") {
                        this.value = formatCurrency(this.value);
                    }
                });
            });

            let priceinsurance = document.querySelectorAll(".insurance");
            priceinsurance.forEach(function(insurance) {
                if (insurance.value.trim() !== "") {
                    insurance.value = formatCurrency(insurance.value);
                }
                insurance.addEventListener("input", function() {
                    if (this.value.trim() !== "") {
                        this.value = formatCurrency(this.value);
                    }
                });
            });
        }

        $('#setPrintModal').on('shown.bs.modal', function() {
            formatInputs();
        });

        var accordionButtons = document.querySelectorAll('.accordion-button');

        accordionButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var icons = this.querySelectorAll('.collapse-icon');
                icons.forEach(function (icon) {
                    icon.classList.toggle('d-none');
                });
            });
        });
    });
</script>
@endsection