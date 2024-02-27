@extends('layouts.base')
<!-- @section('title', 'Bill Recap (SOA)') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 d-flex ms-auto">
            <div>
                <button class="btn bg-gradient-dark" type="button" id="dropdownImport" data-bs-toggle="dropdown" aria-expanded="false">
                    Import
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="form-new-bill-recap">
            <div class="card mb-4">
                <div class="card-header pb-4">
                    <h6>Form Bill Recap (SOA)</h6>
                    <p class="text-sm mb-0">
                        Fill out the new bill recap form below.
                    </p>
                </div>
                <div class="card-body px-4 pt-0 pb-0">
                    @if ($bill)
                    <form class="form" action="{{ url('bill_recap-update') }}" method="POST">
                        @csrf
                        <div class="form-bill-recap mb-4">
                            <input type="hidden" name="id" value="{{ $bill->id_bill_recap }}">
                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</label>
                                @if (count($customers) > 0)
                                <select class="form-control" name="id_customer" required>
                                    <option value="">Choose one..</option>
                                    @foreach ($customers as $c)
                                    <option value="{{ $c->id_customer }}" {{ old('id_customer', $bill->id_customer) == $c->id_customer ? 'selected' : '' }}>{{ $c->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <select class="form-control" name="id_customer" disabled>
                                    <option value="">No data available</option>
                                </select>
                                @endif
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Load Date</label>
                                <input type="date" name="load_date" class="form-control" value="{{ old('load_date', $bill->load_date) }}" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Inv</label>
                                <input type="text" name="no_inv" class="form-control" value="{{ old('no_inv', $bill->no_inv) }}" style="width: 170px;" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Freight</label>
                                <select class="form-control" name="freight" required>
                                    <option value="">Choose one..</option>
                                    <option value="SIN BTH" {{ old('freight', $bill->freight_type) == 'SIN BTH' ? 'selected' : '' }}>SIN BTH</option>
                                    <option value="SIN JKT" {{ old('freight', $bill->freight_type) == 'SIN JKT' ? 'selected' : '' }}>SIN JKT</option>
                                    <option value="BTH JKT" {{ old('freight', $bill->freight_type) == 'BTH JKT' ? 'selected' : '' }}>BTH JKT</option>
                                </select>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entry Date / BL</label>
                                <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', $bill->entry_date) }}" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size (M3)</label>
                                <input type="text" name="size" id="size" class="form-control" value="{{ old('size', $bill->size) }}" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Price</label>
                                <input type="text" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price', $bill->unit_price) }}" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" value="{{ old('amount', $bill->amount) }}" readonly>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Date</label>
                                <input type="date" name="payment_date" value="{{ old('payment_date', $bill->payment_date) }}" class="form-control">
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Amount</label>
                                <input type="text" name="payment_amount" id="payment_amount" value="{{ old('payment_amount', $bill->payment_amount) }}" class="form-control">
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remaining Bill</label>
                                <input type="text" name="remaining_bill" id="remaining_bill" value="{{ old('remaining_bill', $bill->remaining_bill) }}" class="form-control" readonly>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Overdue Bill</label>
                                <input type="date" name="overdue_bill" value="{{ old('overdue_bill', $bill->overdue_bill) }}" class="form-control">
                            </div>
                        </div>

                        <button type="submit" id="submitBtnBill" class="btn btn-primary btn-sm">Update</button>
                    </form>
                    @else
                    <form class="form" action="{{ url('bill_recap-store') }}" method="POST">
                        @csrf
                        <div class="form-bill-recap mb-4">
                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</label>
                                @if (count($customers) > 0)
                                <select class="form-control" name="id_customer" required>
                                    <option value="">Choose one..</option>
                                    @foreach ($customers as $c)
                                    <option value="{{ $c->id_customer }}" {{ old('id_customer') == $c->id_customer ? 'selected' : '' }}>{{ $c->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <select class="form-control" name="id_customer" disabled>
                                    <option value="">No data available</option>
                                </select>
                                @endif
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Load Date</label>
                                <input type="date" name="load_date" class="form-control" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Inv</label>
                                <input type="text" name="no_inv" class="form-control" style="width: 170px;" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Freight</label>
                                <select class="form-control" name="freight" required>
                                    <option value="">Choose one..</option>
                                    <option value="SIN BTH">SIN BTH</option>
                                    <option value="SIN JKT">SIN JKT</option>
                                    <option value="BTH JKT">BTH JKT</option>
                                </select>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entry Date / BL</label>
                                <input type="date" name="entry_date" class="form-control" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size (M3)</label>
                                <input type="text" name="size" id="size" class="form-control" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Price</label>
                                <input type="text" name="unit_price" id="unit_price" class="form-control" required>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</label>
                                <input type="text" name="amount" id="amount" class="form-control" readonly>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control">
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Amount</label>
                                <input type="text" name="payment_amount" id="payment_amount" class="form-control">
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remaining Bill</label>
                                <input type="text" name="remaining_bill" id="remaining_bill" class="form-control" readonly>
                            </div>

                            <div class="input-group input-group-static">
                                <label class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Overdue Bill</label>
                                <input type="date" name="overdue_bill" class="form-control">
                            </div>
                        </div>

                        <button type="submit" id="submitBtnBill" class="btn btn-primary btn-sm">Submit</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-bill-recap {
        display: flex;
        overflow-x: auto;
    }

    .input-group-static {
        margin-right: 10px;
    }
</style>

<script>
    const sizeInput = document.getElementById('size');
    const unitPriceInput = document.getElementById('unit_price');
    const amountInput = document.getElementById('amount');
    const paymentAmountInput = document.getElementById('payment_amount');
    const remainingBillInput = document.getElementById('remaining_bill');

    sizeInput.addEventListener('input', updateAmount);
    unitPriceInput.addEventListener('input', updateAmount);
    paymentAmountInput.addEventListener('input', updateAmount);

    function updateAmount() {
        const sizeValue = parseFloat(sizeInput.value.replace(/\./g, '').replace(',', '.'));
        const unitPriceValue = parseFloat(unitPriceInput.value.replace(/\./g, '').replace(',', '.'));
        const paymentAmountValue = parseFloat(paymentAmountInput.value.replace(/\./g, '').replace(',', '.'));

        if (!isNaN(sizeValue) && !isNaN(unitPriceValue)) {
            const amount = sizeValue * unitPriceValue;
            const formattedAmount = new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 0
            }).format(amount);
            amountInput.value = formattedAmount;

            if (!isNaN(paymentAmountValue)) {
                const convertFormattedAmount = parseFloat(formattedAmount.replace(/\./g, '').replace(',', '.'));
                if (paymentAmountValue < convertFormattedAmount) {
                    const remainingBill = amount - paymentAmountValue;
                    const formattedRemainingBill = new Intl.NumberFormat('id-ID', {
                        maximumFractionDigits: 0
                    }).format(remainingBill);
                    remainingBillInput.value = formattedRemainingBill;
                    remainingBillInput.classList.remove('text-danger');

                } else if (paymentAmountValue == convertFormattedAmount) {
                    remainingBillInput.value = '0';
                    remainingBillInput.classList.remove('text-danger');

                } else {
                    remainingBillInput.value = 'Overpayment';
                    remainingBillInput.classList.add('text-danger');
                    paymentAmountInput.value = '';
                }

            } else {
                remainingBillInput.value = '';
            }

        } else {
            amountInput.value = '';
            remainingBillInput.value = '';
            remainingBillInput.classList.remove('text-danger');
        }
    }

    // currency
    function formatCurrency(num) {
        num = num.toString().replace(/[^\d-]/g, '');

        num = num.replace(/-+/g, (match, offset) => offset > 0 ? "" : "-");

        let isNegative = false;
        if (num.startsWith("-")) {
            isNegative = true;
            num = num.slice(1);
        }

        let formattedNum = Math.abs(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        if (isNegative) {
            formattedNum = "-" + formattedNum;
        }

        return formattedNum;
    }

    document.addEventListener('DOMContentLoaded', function() {
        let inputPrices = document.querySelectorAll("#unit_price, #payment_amount");
        inputPrices.forEach(function(inputPrice) {
            inputPrice.addEventListener("input", function() {
                this.value = formatCurrency(this.value);
            });
        });
    });
</script>
@endsection