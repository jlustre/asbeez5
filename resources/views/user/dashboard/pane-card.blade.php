<div class="tab-pane fade" id="pills-card" role="tabpanel">
    <div class="dashboard-card">
        <div class="title title-flex">
            <div>
                <h2>My Card Details</h2>
                <span class="title-leaf">
                    <svg class="icon-width bg-gray">
                        <use xlink:href="{{ asset('assets/svg/leaf.svg') }}#leaf"></use>
                    </svg>
                </span>
            </div>

            <button class="btn theme-bg-color text-white btn-sm fw-bold mt-lg-0 mt-3" data-bs-toggle="modal"
                data-bs-target="#editCard"><i data-feather="plus" class="me-2"></i> Add New Card</button>
        </div>

        <div class="row g-4">
            <div class="col-xxl-4 col-xl-6 col-lg-12 col-sm-6">
                <div class="payment-card-detail">
                    <div class="card-details">
                        <div class="card-number">
                            <h4>XXXX - XXXX - XXXX - 2548</h4>
                        </div>

                        <div class="valid-detail">
                            <div class="title">
                                <span>valid</span>
                                <span>thru</span>
                            </div>
                            <div class="date">
                                <h3>08/05</h3>
                            </div>
                            <div class="primary">
                                <span class="badge bg-pill badge-light">primary</span>
                            </div>
                        </div>

                        <div class="name-detail">
                            <div class="name">
                                <h5>Audrey Carol</h5>
                            </div>
                            <div class="card-img">
                                <img src="{{ asset('assets/images/payment-icon/1.jpg') }}"
                                    class="img-fluid blur-up lazyloaded" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="edit-card">
                        <a data-bs-toggle="modal" data-bs-target="#editCard" href="javascript:void(0)"><i
                                class="far fa-edit"></i> edit</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#removeProfile"><i
                                class="far fa-minus-square"></i>
                            delete</a>
                    </div>
                </div>

                <div class="edit-card-mobile">
                    <a data-bs-toggle="modal" data-bs-target="#editCard" href="javascript:void(0)"><i
                            class="far fa-edit"></i> edit</a>
                    <a href="javascript:void(0)"><i class="far fa-minus-square"></i>
                        delete</a>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-6 col-lg-12 col-sm-6">
                <div class="payment-card-detail">
                    <div class="card-details card-visa">
                        <div class="card-number">
                            <h4>XXXX - XXXX - XXXX - 1536</h4>
                        </div>

                        <div class="valid-detail">
                            <div class="title">
                                <span>valid</span>
                                <span>thru</span>
                            </div>
                            <div class="date">
                                <h3>12/23</h3>
                            </div>
                            <div class="primary">
                                <span class="badge bg-pill badge-light">primary</span>
                            </div>
                        </div>

                        <div class="name-detail">
                            <div class="name">
                                <h5>Leah Heather</h5>
                            </div>
                            <div class="card-img">
                                <img src="{{ asset('assets/images/payment-icon/2.jpg') }}"
                                    class="img-fluid blur-up lazyloaded" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="edit-card">
                        <a data-bs-toggle="modal" data-bs-target="#editCard" href="javascript:void(0)"><i
                                class="far fa-edit"></i> edit</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#removeProfile"><i
                                class="far fa-minus-square"></i>
                            delete</a>
                    </div>
                </div>

                <div class="edit-card-mobile">
                    <a data-bs-toggle="modal" data-bs-target="#editCard" href="javascript:void(0)"><i
                            class="far fa-edit"></i> edit</a>
                    <a href="javascript:void(0)"><i class="far fa-minus-square"></i>
                        delete</a>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-6 col-lg-12 col-sm-6">
                <div class="payment-card-detail">
                    <div class="card-details debit-card">
                        <div class="card-number">
                            <h4>XXXX - XXXX - XXXX - 1366</h4>
                        </div>

                        <div class="valid-detail">
                            <div class="title">
                                <span>valid</span>
                                <span>thru</span>
                            </div>
                            <div class="date">
                                <h3>05/21</h3>
                            </div>
                            <div class="primary">
                                <span class="badge bg-pill badge-light">primary</span>
                            </div>
                        </div>

                        <div class="name-detail">
                            <div class="name">
                                <h5>mark jecno</h5>
                            </div>
                            <div class="card-img">
                                <img src="{{ asset('assets/images/payment-icon/3.jpg') }}"
                                    class="img-fluid blur-up lazyloaded" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="edit-card">
                        <a data-bs-toggle="modal" data-bs-target="#editCard" href="javascript:void(0)"><i
                                class="far fa-edit"></i> edit</a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#removeProfile"><i
                                class="far fa-minus-square"></i>
                            delete</a>
                    </div>
                </div>

                <div class="edit-card-mobile">
                    <a data-bs-toggle="modal" data-bs-target="#editCard" href="javascript:void(0)"><i
                            class="far fa-edit"></i> edit</a>
                    <a href="javascript:void(0)"><i class="far fa-minus-square"></i>
                        delete</a>
                </div>
            </div>
        </div>
    </div>
</div>