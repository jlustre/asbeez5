<footer class="section-t-space footer-section-2 footer-section-4">
    <div class="container-fluid-lg">
        <div class="footer-newsletter section-b-space">
            <div class="newsletter-detail">
                <h2>Join our <span>Creative Community</span></h2>
                <h5>Join our mailing list to stay in the loop with our newest feature releases, NFT drops, and tips
                    and tricks</h5>
                <div class="input-box input-group">
                    <input type="email" class="form-control" id="exampleFormControlInput1"
                        placeholder="Enter Your Email">
                    <button class="sub-btn">
                        <span>Subscribe</span>
                        <i class="fa-solid fa-arrow-right icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="main-footer">
            <div class="row g-md-4 gy-sm-5">
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <a href="index.html" class="foot-logo theme-logo">
                        <img src="{{ asset('assets/images/logo/4.png') }}" class="img-fluid blur-up lazyload" alt="">
                    </a>
                    <p class="information-text information-text-2">it is a long established fact that a reader will
                        be distracted by the readable content.</p>
                    <ul class="social-icon">
                        <li class="light-bg">
                            <a href="https://www.facebook.com/" class="footer-link-color">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li class="light-bg">
                            <a href="https://accounts.google.com/signin/v2/identifier?flowName=GlifWebSignIn&flowEntry=ServiceLogin"
                                class="footer-link-color">
                                <i class="fab fa-google"></i>
                            </a>
                        </li>
                        <li class="light-bg">
                            <a href="https://twitter.com/i/flow/login" class="footer-link-color">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li class="light-bg">
                            <a href="https://www.instagram.com/" class="footer-link-color">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="light-bg">
                            <a href="https://in.pinterest.com/" class="footer-link-color">
                                <i class="fab fa-pinterest-p"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-xxl-2 col-xl-4 col-sm-6">
                    <div class="footer-title">
                        <h4 class="text-white">About {{ config('app.name') }}</h4>
                    </div>
                    <ul class="footer-list footer-contact footer-list-light">
                        <li>
                            <a href="about-us.html" class="light-text">About Us</a>
                        </li>
                        <li>
                            <a href="contact-us.html" class="light-text">Contact Us</a>
                        </li>
                        <li>
                            <a href="term_condition.html" class="light-text">Terms & Conditions</a>
                        </li>
                        <li>
                            <a href="careers.html" class="light-text">Careers</a>
                        </li>
                        <li>
                            <a href="blog-list.html" class="light-text">Latest Blog</a>
                        </li>
                    </ul>
                </div>

                <div class="col-xxl-2 col-xl-4 col-sm-6">
                    <div class="footer-title">
                        <h4 class="text-white">Useful Link</h4>
                    </div>
                    <ul class="footer-list footer-list-light footer-contact">
                        <li>
                            <a href="order-success.html" class="light-text">Your Order</a>
                        </li>
                        <li>
                            <a href="user-dashboard.html" class="light-text">Your Account</a>
                        </li>
                        <li>
                            <a href="order-tracking.html" class="light-text">Track Orders</a>
                        </li>
                        <li>
                            <a href="wishlist.html" class="light-text">Your Wishlist</a>
                        </li>
                        <li>
                            <a href="faq.html" class="light-text">FAQs</a>
                        </li>
                    </ul>
                </div>

                <div class="col-xxl-2 col-xl-4 col-sm-6">
                    <div class="footer-title">
                        <h4 class="text-white">Categories</h4>
                    </div>
                    <ul class="footer-list footer-list-light footer-contact">
                        <li>
                            <a href="vegetables-demo.html" class="light-text">Fresh Vegetables</a>
                        </li>
                        <li>
                            <a href="spice-demo.html" class="light-text">Hot Spice</a>
                        </li>
                        <li>
                            <a href="bags-demo.html" class="light-text">Brand New Bags</a>
                        </li>
                        <li>
                            <a href="bakery-demo.html" class="light-text">New Bakery</a>
                        </li>
                        <li>
                            <a href="grocery-demo.html" class="light-text">New Grocery</a>
                        </li>
                    </ul>
                </div>

                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="footer-title">
                        <h4 class="text-white">Store information</h4>
                    </div>
                    <ul class="footer-address footer-contact">
                        <li>
                            <a href="javascript:void(0)" class="light-text">
                                <div class="inform-box flex-start-box">
                                    <i data-feather="map-pin"></i>
                                    <p>{{ config('company.address') }}</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" class="light-text">
                                <div class="inform-box">
                                    <i data-feather="phone"></i>
                                    <p>Call us: {{ config('company.phone') }}</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" class="light-text">
                                <div class="inform-box">
                                    <i data-feather="mail"></i>
                                    <p>Email us: {{ config('company.email') }}</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" class="light-text">
                                <div class="inform-box">
                                    <i data-feather="printer"></i>
                                    <p>Fax: {{ config('company.fax') }}</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sub-footer sub-footer-lite py-3">
            <div class="left-footer">
                <p class="light-text">2022 Copyright By {{ config('app.name') }}</p>
            </div>

            <ul class="payment-box">
                <li>
                    <img src="{{ asset('assets/images/icon/paymant/visa.png') }}" class="blur-up lazyload" alt="">
                </li>
                <li>
                    <img src="{{ asset('assets/images/icon/paymant/discover.png') }}" class="blur-up lazyload" alt="">
                </li>
                <li>
                    <img src="{{ asset('assets/images/icon/paymant/american.png') }}" class="blur-up lazyload" alt="">
                </li>
                <li>
                    <img src="{{ asset('assets/images/icon/paymant/master-card.png') }}" class="blur-up lazyload"
                        alt="">
                </li>
                <li>
                    <img src="{{ asset('assets/images/icon/paymant/giro-pay.png') }}" class="blur-up lazyload" alt="">
                </li>
            </ul>
        </div>
    </div>
</footer>