@extends('layouts.base')
<!-- @section('title', 'Pricelists') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 d-flex ms-auto">
            <div>
                <button class="btn bg-gradient-dark" type="button" id="dropdownImport" data-bs-toggle="modal" data-bs-target="#insuranceModal">
                    Import
                </button>
            </div>
        </div>
    </div>

    <!-- Modal - Import Pricelists -->
    <div class="modal fade" id="insuranceModal" tabindex="-1" role="dialog" aria-labelledby="insuranceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal text-md" id="insuranceModalLabel"><b>Import Pricelists</b></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('import-pricelist') }}" method="POST" enctype="multipart/form-data">
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

    <!-- Modal - Edit Pricelist -->
    <div class="modal fade" id="pricelistEditModal" tabindex="-1" role="dialog" aria-labelledby="pricelistEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal text-md" id="pricelistEditModalLabel"><b>Edit Pricelist</b></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('pricelist-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">

                        <div class="input-group input-group-static mb-1">
                            <label>Customer (<span class="text-info">Optional</span>)</label>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <select class="form-select select-cust" id="customer" name="id_customer" style="border: none; border-bottom: 1px solid #ced4da; border-radius: 0px;">
                                <option value="">...</option>
                                @foreach ($customers as $c)
                                <option value="{{ $c->id_customer }}" data-shipper-ids="{{ $c->shipper_ids }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="input-group input-group-static mb-1">
                            <label>Shipper (<span class="text-info">Optional</span>)</label>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <select class="form-select select-shipper" id="shipper" name="id_shipper" style="border: none; border-bottom: 1px solid #ced4da; border-radius: 0px;">
                                <option value="">...</option>
                                @foreach ($shippers as $s)
                                <option value="{{ $s->id_shipper }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="input-group input-group-static mb-1">
                            <label>Origin <span class="text-danger">*</span></label>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <select class="form-select text-left" id="origin" name="origin" style="border: none; border-bottom: 1px solid #ced4da; border-radius: 0px;" required>
                                <option value="">...</option>
                                <option value="BTH-JKT">BTH-JKT</option>
                                <option value="BTH-SIN">BTH-SIN</option>
                                <option value="SIN-BTH">SIN-BTH</option>
                                <option value="SIN-JKT">SIN-JKT</option>
                            </select>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}" placeholder="..." required>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <div class="col-5">
                                <label>Start Period (<span class="text-info">Optional</span>)</label>
                                <input type="date" class="form-control" id="start_period" name="start_period" value="{{ old('start_period') }}">
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <label>End Period (<span class="text-info">Optional</span>)</label>
                                <input type="date" class="form-control" id="end_period" name="end_period" value="{{ old('end_period') }}">
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn bg-gradient-secondary btn-md" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn bg-gradient-primary btn-md ms-1">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session()->has('logErrors'))
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-sm text-danger">Error Log</h5>
                    @if(is_array(session('logErrors')))
                    @foreach(session('logErrors') as $logError)
                    {{ $logError }} <br>
                    @endforeach
                    @else
                    {{ session('logErrors') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-5">
                    <h6>List Pricelists</h6>
                    <p class="text-sm mb-0">
                        View all list of pricelists in the system.
                    </p>
                </div>
                @if (count($pricelists) > 0)
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shipper</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Origin</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start Period</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End Period</th>
                                    <th class="text-center text-uppercase text-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pricelists as $p)
                                <tr data-id="{{ $p->id_pricelist }}" data-id-customer="{{ $p->id_customer }}" data-id-shipper="{{ $p->id_shipper }}">
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-sm font-weight-normal text-secondary mb-0">{{ $loop->iteration }}.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="customer-selected text-sm font-weight-normal mb-0">{{ $customer[$p->id_customer] ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="shipper-selected text-sm font-weight-normal mb-0">{{ $shipper[$p->id_shipper] ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="origin-selected text-sm font-weight-normal mb-0">{{ $p->origin ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="price-selected text-sm font-weight-normal mb-0">{{ 'Rp ' . number_format($p->price ?? 0, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if (!empty($p->start_period))
                                        <p class="start-period-selected text-sm font-weight-normal mb-0" data-start-period="{{ $p->start_period }}">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $p->start_period)->format('d-M-y') ?? '-' }}</p>
                                        @else
                                        <p class="start-period-selected text-sm font-weight-normal mb-0">-</p>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if (!empty($p->end_period))
                                        <p class="end-period-selected text-sm font-weight-normal mb-0" data-end-period="{{ $p->end_period }}">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $p->end_period)->format('d-M-y') ?? '-' }}</p>
                                        @else
                                        <p class="end-period-selected text-sm font-weight-normal mb-0">-</p>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="mx-4 edit-button" data-bs-toggle="modal" data-bs-target="#pricelistEditModal">
                                            <i class="material-icons text-secondary position-relative text-lg">drive_file_rename_outline</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="card-body px-0 pt-0 pb-0">
                    <div class="d-flex justify-content-center mb-3">
                        <span class="text-xs mb-3"><i>No available data to display..</i></span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<script>
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
        var editButtons = document.querySelectorAll(".edit-button");
        editButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var customer = row.getAttribute("data-id-customer");
                var shipper = row.getAttribute("data-id-shipper");
                var origin = row.querySelector(".origin-selected").textContent;
                var price = row.querySelector(".price-selected").textContent.trim();

                const priceConvert = parseFloat(price);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("customer").value = customer;
                document.getElementById("shipper").value = shipper;
                document.getElementById("origin").value = origin;
                document.getElementById("price").value = price;

                var startPeriodElement = row.querySelector(".start-period-selected");
                if (startPeriodElement.hasAttribute("data-start-period")) {
                    var startPeriod = row.querySelector(".start-period-selected").getAttribute("data-start-period").trim();
                    document.getElementById("start_period").value = startPeriod;

                } else {
                    document.getElementById("start_period").value = null;
                }
                
                var endPeriodElement = row.querySelector(".end-period-selected");
                if (endPeriodElement.hasAttribute("data-end-period")) {
                    var endPeriod = row.querySelector(".end-period-selected").getAttribute("data-end-period").trim();
                    document.getElementById("end_period").value = endPeriod;

                } else {
                    document.getElementById("end_period").value = null;
                }
            });
        });

        let inputPrices = document.querySelectorAll("#price");
        inputPrices.forEach(function(inputPrice) {
            inputPrice.addEventListener("input", function() {
                this.value = formatCurrency(this.value);
            });
        });
    });
</script>