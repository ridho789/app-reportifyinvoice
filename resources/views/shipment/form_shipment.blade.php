@extends('layouts.base')
<!-- @section('title', 'Form Shipment') -->
@section('content')
<div class="container-fluid py-4">
    <div class="row mt-4">
        <div class="col-lg-9 col-12 mx-auto position-relative">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow text-center border-radius-xl mt-n4 me-3 float-start">
                        <i class="material-icons opacity-10">event</i>
                    </div>
                    <h6 class="mb-0">New Shipment</h6>
                </div>
                <div class="card-body pt-2">
                    <form class="multisteps-form__form">
                        <div class="multisteps-form__panel border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="multisteps-form__content">
                                <div class="row mt-3">
                                    <div class="col-12 col-sm-5">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Shipper</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">No.</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-5 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Date</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 col-sm-6">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Consigne</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Ship Name</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 col-sm-6">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Total Number of Ships / BL</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Total Weight</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Etd</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 col-sm-6">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Total Number of Packages</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Total Volume</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                                        <div class="input-group input-group-dynamic">
                                            <label class="form-label">Eta</label>
                                            <input class="multisteps-form__input form-control" type="text" onfocus="focused(this)" onfocusout="defocused(this)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-5">
                            <a href="{{ url('/list_shipments') }}" type="button" name="button" class="btn btn-light m-0">Cancel</a>
                            <button type="button" name="button" class="btn bg-gradient-dark m-0 ms-2">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection