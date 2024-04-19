@extends('layouts.base')
<!-- @section('title', 'Cas') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 d-flex ms-auto">
            <div>
                <button class="btn bg-gradient-dark" type="button" id="dropdownImport" data-bs-toggle="modal" data-bs-target="#casModal">
                    Import
                </button>
            </div>
        </div>
    </div>

    <!-- Modal - Import Cas -->
    <div class="modal fade" id="casModal" tabindex="-1" role="dialog" aria-labelledby="casModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal text-md" id="casModalLabel"><b>Import Cas</b></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('import-cas') }}" method="POST" enctype="multipart/form-data">
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

    <!-- Modal - Edit Cas -->
    <div class="modal fade" id="casEditModal" tabindex="-1" role="dialog" aria-labelledby="casEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal text-md" id="casEditModalLabel"><b>Edit Cas</b></h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('cas-update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <div class="input-group input-group-static mb-4">
                            <label>Customer</label>
                            <input type="text" class="form-control" id="customer" name="customer" value="{{ old('customer') }}" disabled>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Shipper</label>
                            <input type="text" class="form-control" id="shipper" name="shipper" value="{{ old('shipper') }}" disabled>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>LTS</label>
                            <input type="text" class="form-control" id="lts" name="lts" value="{{ old('lts') }}" required>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Charge</label>
                            <input type="text" class="form-control" id="charge" name="charge" value="{{ old('charge') }}" required>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Desc</label>
                            <input type="text" class="form-control" id="desc" name="desc" value="{{ old('desc') }}" required>
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
                    <h6>List Cas</h6>
                    <p class="text-sm mb-0">
                        View all list of cas in the system.
                    </p>
                </div>
                @if (count($cas) > 0)
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shipper</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lts</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Charge</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Desc</th>
                                    <th class="text-center text-uppercase text-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cas as $c)
                                <tr data-id="{{ $c->id_cas }}">
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-sm font-weight-normal text-secondary mb-0">{{ $loop->iteration }}.</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="customer-selected text-sm font-weight-normal mb-0">{{ $customer[$c->id_customer] ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="shipper-selected text-sm font-weight-normal mb-0">{{ $shipper[$c->id_shipper] ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="lts-selected text-sm font-weight-normal mb-0">{{ $c->lts ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="charge-selected text-sm font-weight-normal mb-0">{{ 'Rp ' . number_format($c->charge ?? 0, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="desc-selected text-sm font-weight-normal mb-0">{{ $c->desc ?? '-' }}</p>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="mx-4 edit-button" data-bs-toggle="modal" data-bs-target="#casEditModal">
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
                var customer = row.querySelector(".customer-selected").textContent;
                var shipper = row.querySelector(".shipper-selected").textContent;
                var lts = row.querySelector(".lts-selected").textContent;
                var charge = row.querySelector(".charge-selected").textContent.trim();
                var desc = row.querySelector(".desc-selected").textContent;

                const chargeConvert = parseFloat(charge);

                // Mengisi data ke dalam formulir
                document.getElementById("id").value = id;
                document.getElementById("customer").value = customer;
                document.getElementById("shipper").value = shipper;
                document.getElementById("lts").value = lts;
                document.getElementById("charge").value = charge;
                document.getElementById("desc").value = desc;
            });
        });

        let inputCharges = document.querySelectorAll("#charge");
        inputCharges.forEach(function(inputCharge) {
            inputCharge.addEventListener("input", function() {
                this.value = formatCurrency(this.value);
            });
        });
    });
</script>