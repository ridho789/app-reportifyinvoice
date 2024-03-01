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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" rowspan="2">BL Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Total Packages <br> pkgs</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Total Weight <br> kgs</th>
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
                                    <td>
                                        <p class="text-xs text-secondary mb-0">-</p>
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
                </div>
                <div class="card-body px-4 pt-0 pb-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shipper</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ship</th>
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
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <p class="text-xs text-secondary mb-0">-</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">-</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive py-3">
                        <!-- <h6>List of Shipment Sea Freight</h6> -->
                        <p class="text-sm mb-0">
                            List of sea freight shipment.
                        </p>
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" rowspan="2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" rowspan="2">Code</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Marking</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="2">Quantity</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Weight <br> kg</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="3">Dimension</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="1" colspan="2">Total CBM</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">Desc</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan="2">State</th>
                                </tr>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pkgs</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Loose</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">p</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">l</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">t</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">i</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ii</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <p class="text-xs text-secondary mb-0">-</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">-</p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">-</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                    <!-- qty -->
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
                                    <!-- dimension -->
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
                                    <!-- total cbm -->
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
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-normal">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection