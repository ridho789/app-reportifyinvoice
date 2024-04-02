@extends('layouts.base')
<!-- @section('title', 'Sea Freight Shipment') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div>
            <div class="card mb-4">
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
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <p class="text-xs text-secondary mb-0">-</p>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-secondary text-xs font-weight-normal">-</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <!-- total cbm -->
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <!-- ### -->
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-5">
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
                                                <input type="text" class="form-control" name="no_aju" value="{{ $seaShipment->no_aju ?? '-' }}" placeholder="...">
                                            </div>
                                        </td>
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="date" class="form-control" name="date" value="{{ $seaShipment->date }}">
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-cust" name="id_customer">
                                                <option value="">...</option>
                                                @foreach ($customers as $c)
                                                <option value="{{ $c->id_customer }}" data-shipper-ids="{{ $c->shipper_ids }}" 
                                                    {{ old('id_customer', $seaShipment->id_customer) == $c->id_customer ? 'selected' : '' }}>{{ $c->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <select class="form-select text-xs select-shipper" name="id_shipper">
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
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="text" class="form-control text-center" name="origin" value="{{ $seaShipment->origin ?? '-' }}" placeholder="...">
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
                                            <input type="date" class="form-control" name="etd" value="{{ $seaShipment->etd }}">
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
                                            <input type="text" class="form-control text-center" name="code[]" value="{{ $ssl->code ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <input type="text" class="form-control text-center" name="marking[]" value="{{ $ssl->marking ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- qty -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="qty_pkgs[]" value="{{ $ssl->qty_pkgs ?? '-' }}" placeholder="..." style="border: 0px;">
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
                                            <input type="text" class="form-control text-center" name="qty_loose[]" value="{{ $ssl->qty_loose ?? '-' }}" placeholder="..." style="border: 0px;">
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
                                            <input type="text" class="form-control text-center" name="weight[]" value="{{ $ssl->weight ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- dimension -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="p[]" value="{{ $ssl->dimension_p ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="l[]" value="{{ $ssl->dimension_l ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="t[]" value="{{ $ssl->dimension_t ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- ### -->
                                        <!-- total cbm -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="cbm1[]" value="{{ $ssl->tot_cbm_1 ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="cbm2[]" value="{{ $ssl->tot_cbm_2 ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- ### -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="lts[]" value="{{ $ssl->lts ?? '-' }}" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="text" class="form-control text-center" name="desc[]" value="{{ $ssl->desc ?? '-' }}" placeholder="..." style="border: 0px;">
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
                                                <input type="text" class="form-control" name="number" placeholder="...">
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
                                        <td class="align-middle text-center" width=5.5%>
                                            <input type="text" class="form-control text-center" name="origin" placeholder="...">
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
                                            <input type="date" class="form-control" name="etd">
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
                                            <input type="text" class="form-control text-center" name="code[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=10%>
                                            <input type="text" class="form-control text-center" name="marking[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <!-- qty -->
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="qty_pkgs[]" placeholder="..." style="border: 0px;">
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
                                            <input type="text" class="form-control text-center" name="qty_loose[]" placeholder="..." style="border: 0px;">
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
                                            <input type="text" class="form-control text-center" name="p[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="l[]" placeholder="..." style="border: 0px;">
                                        </td>
                                        <td class="align-middle text-center" width=5%>
                                            <input type="text" class="form-control text-center" name="t[]" placeholder="..." style="border: 0px;">
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
                                            <input type="text" class="form-control text-center" name="lts[]" placeholder="..." style="border: 0px;">
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
                    </form>
                    @endif 
                </div>
            </div>
        </div>
    </div>
</div>
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

    var qtyPkgInputs = document.querySelectorAll('input[name="weight[]"]');
    qtyPkgInputs.forEach(function(input) {
        input.addEventListener('change', calculateTotalWeight);
    });

</script>
@endsection