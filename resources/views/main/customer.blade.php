@extends('layouts.base')
<!-- @section('title', 'Customers') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 d-flex ms-auto">
            @if (count($customers) > 0)
            <div>
                <button class="btn btn-icon bg-gradient-primary" id="btn-new-customer">
                    new customer
                </button>
            </div>
            <div>
                <button class="btn bg-gradient-dark ms-2" type="button" id="dropdownImport" data-bs-toggle="dropdown" aria-expanded="false">
                    Import
                </button>
            </div>
            @else
            <div>
                <button class="btn bg-gradient-dark" type="button" id="dropdownImport" data-bs-toggle="dropdown" aria-expanded="false">
                    Import
                </button>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- alert error -->
        @if(session('error_type') == 'duplicate-alert')
        <div class="col-12" id="error-message" style="opacity: 1; transition: opacity 1s ease;">
            <div class="alert alert-danger text-white" role="alert">
                <strong>Error!</strong> {{ session('error') }}!
            </div>
        </div>
        @endif

        <div class="col-12">
            <!-- form new customer -->
            <div id="form-new-customer" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header pb-4">
                        <h6>New Customer</h6>
                        <p class="text-sm mb-0">
                            Fill out the new customer form below.
                        </p>
                    </div>
                    <div class="card-body px-4 pt-0 pb-0">
                        <form action="{{ url('customer-store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="input-group input-group-static">
                                        <label>Enter a customer</label>
                                        <input type="text" name="customer" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="input-group input-group-static text-xs">
                                        @if (count($shippers) > 0)
                                            <label style="margin-left: -1px; margin-bottom: 11px;">Select a shipper (<span class="text-info">Optional</span>)</label>
                                            <select class="form-control select2" name="id_shipper[]" multiple style="width: 100%;">
                                                @foreach ($shippers as $s)
                                                    <option value="{{ $s->id_shipper }}" {{ (is_array(old('id_shipper')) && in_array($s->id_shipper, old('id_shipper'))) ? 'selected' : '' }}>{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <label style="margin-bottom: 4.5px;">Select a shipper (<span class="text-info">Optional</span>)</label>
                                            <select class="form-control" disabled>
                                                <option value="">No data available</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- form edit customer -->
            <div class="col-12" id="form-edit-customer" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header pb-4">
                        <h6>Edit Customer</h6>
                        <p class="text-sm mb-0">
                            Fill out the edit customer form below.
                        </p>
                    </div>
                    <div class="card-body px-4 pt-0 pb-0">
                        <form action="{{ url('customer-update') }}" method="POST">
                            @csrf
                            <input type="hidden" id="edit-id" name="id">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="input-group input-group-static">
                                        <label>Enter a customer</label>
                                        <input type="text" name="customer" id="edit-customer" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="input-group input-group-static text-xs">
                                        @if (count($shippers) > 0)
                                            <label style="margin-left: -1px; margin-bottom: 12px;">Select a shipper (<span class="text-info">Optional</span>)</label>
                                            <select class="form-control select2" name="id_shipper[]" multiple style="width: 100%;" id="edit-shipper">
                                                @foreach ($shippers as $s)
                                                    <option value="{{ $s->id_shipper }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <label style="margin-bottom: 4.5px;">Select a shipper (<span class="text-info">Optional</span>)</label>
                                            <select class="form-control" disabled>
                                                <option value="">No data available</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header pb-5">
                    <h6>List Customers</h6>
                    <p class="text-sm mb-0">
                        View all list of customers in the system.
                    </p>
                </div>
                @if (count($customers) > 0)
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Shipper</th>
                                    <th class="text-center text-uppercase text-secondary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $c)
                                <tr data-id="{{ $c->id_customer }}" data-shipper="{{ implode(',', explode(',', $c->shipper_ids)) }}">
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <p class="text-sm font-weight-normal text-secondary mb-0">{{ $loop->iteration }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="name-customer-selected">
                                        <p class="text-sm font-weight-normal mb-0">{{ $c->name }}</p>
                                    </td>
                                    <td class="shipper-selected align-middle text-center text-sm">
                                        @php
                                            $shipperNamesString = null;
                                            if ($c->shipper_ids) {
                                                $shipperIds = explode(',', $c->shipper_ids);
                                                $shipperNames = [];
    
                                                foreach ($shipperIds as $shipperId) {
                                                    // Periksa apakah shipperId ada dalam array $shipperName sebelum menambahkannya ke $shipperNames
                                                    if (isset($shipperName[$shipperId])) {
                                                        $shipperNames[] = $shipperName[$shipperId];
                                                    }
                                                }
    
                                                $shipperNamesString = implode(', ', $shipperNames);
                                            }
                                        @endphp

                                        <p class="text-sm font-weight-normal mb-0">{{ $shipperNamesString ?? '-' }}</p>
                                    </td>
                                    <td>
                                        <a href="#" class="mx-4 btn-edit-customer" id="btn-edit-customer" style="float: right;">
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
                        <button type="button" class="btn bg-gradient-dark" id="btn-new-customer">New Customer</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    // multi select
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please choose..",
            maximumSelectionLength: 7
        });
    });

    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.opacity = '0';
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 1000);
        }
    }, 3500);

    document.addEventListener("DOMContentLoaded", function() {
        const toggleFormButton = document.getElementById('btn-new-customer');
        const myForm = document.getElementById('form-new-customer');

        toggleFormButton.addEventListener('click', function() {
            if (myForm.style.display === 'none') {
                myForm.style.display = 'block';
            }

            if (myEditForm.style.display === 'block') {
                myEditForm.style.display = 'none';
            }
        });

        const myEditForm = document.getElementById('form-edit-customer');
        var editButtons = document.querySelectorAll(".btn-edit-customer");

        editButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                var row = this.closest("tr");
                var id = row.getAttribute("data-id");
                var customer = row.querySelector(".name-customer-selected").textContent;
                var shipperIds = row.getAttribute("data-shipper").split(',');
                $('#edit-shipper').val(shipperIds).trigger('change');

                if (shipperIds.length > 0) {
                    var selectElement = document.getElementById("edit-shipper");
                    selectElement.querySelectorAll('option').forEach(function(option) {
                        if (shipperIds.includes(option.value)) {
                            option.setAttribute('selected', 'selected');
                        } else {
                            option.removeAttribute('selected');
                        }
                    });
                }

                document.getElementById("edit-id").value = id;
                document.getElementById("edit-customer").value = customer.trim();

                if (myEditForm.style.display === 'none') {
                    myEditForm.style.display = 'block';
                }

                if (myForm.style.display === 'block') {
                    myForm.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection