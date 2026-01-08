<div class="dashboard-title">
    <h3>Account Information</h3>
</div>

<div class="row g-4">
    <div class="col-xxl-6">
        <div class="dashboard-content-title">
            <h4>Contact Information <a href="javascript:void(0)" data-bs-toggle="modal"
                    data-bs-target="#editProfile">Edit</a>
            </h4>
        </div>
        <div class="dashboard-detail">
            <h6 class="text-content">{{ optional(auth()->user()->profile)->fullName() ??
                auth()->user()->username }}</h6>
            <h6 class="text-content">{{ auth()->user()->email }}</h6>
            <a href="javascript:void(0)">Change Password</a>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="dashboard-content-title">
            <h4>Newsletters <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Edit</a>
            </h4>
        </div>
        <div class="dashboard-detail">
            <h6 class="text-content">You are currently not subscribed to any
                newsletter</h6>
        </div>
    </div>

    <div class="col-12">
        <div class="dashboard-content-title">
            <h4>Address Book <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Edit</a>
            </h4>
        </div>

        <div class="row g-4">
            <div class="col-xxl-6">
                <div class="dashboard-detail">
                    <h6 class="text-content">Default Billing Address</h6>
                    <h6 class="text-content">You have not set a default billing
                        address.</h6>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Edit Address</a>
                </div>
            </div>

            <div class="col-xxl-6">
                <div class="dashboard-detail">
                    <h6 class="text-content">Default Shipping Address</h6>
                    <h6 class="text-content">You have not set a default shipping
                        address.</h6>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Edit Address</a>
                </div>
            </div>
        </div>
    </div>
</div>