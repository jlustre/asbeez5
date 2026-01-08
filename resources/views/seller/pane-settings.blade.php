<div class="tab-pane fade" id="pills-security" role="tabpanel">
    <div class="dashboard-privacy">
        <div class="title">
            <h2>My Setting</h2>
            <span class="title-leaf">
                <svg class="icon-width bg-gray">
                    <use xlink:href="{{ asset('assets/svg/leaf.svg') }}#leaf"></use>
                </svg>
            </span>
        </div>

        <div class="dashboard-bg-box">
            <div class="dashboard-title mb-4">
                <h3>Notifications</h3>
            </div>

            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="desktop" name="desktop" checked>
                    <label class="form-check-label ms-2" for="desktop">Show
                        Desktop Notifications</label>
                </div>
            </div>

            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="enable" name="desktop">
                    <label class="form-check-label ms-2" for="enable">Enable
                        Notifications</label>
                </div>
            </div>

            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="activity" name="desktop">
                    <label class="form-check-label ms-2" for="activity">Get
                        notification for my own activity</label>
                </div>
            </div>

            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="dnd" name="desktop">
                    <label class="form-check-label ms-2" for="dnd">DND</label>
                </div>
            </div>

            <button class="btn theme-bg-color btn-md fw-bold mt-4 text-white">Save
                Changes</button>
        </div>

        <div class="dashboard-bg-box">
            <div class="dashboard-title mb-4">
                <h3>Deactivate Account</h3>
            </div>
            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="concern" name="concern">
                    <label class="form-check-label ms-2" for="concern">I have a privacy
                        concern</label>
                </div>
            </div>
            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="temporary" name="concern">
                    <label class="form-check-label ms-2" for="temporary">This is
                        temporary</label>
                </div>
            </div>
            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="other" name="concern">
                    <label class="form-check-label ms-2" for="other">other</label>
                </div>
            </div>

            <button class="btn theme-bg-color btn-md fw-bold mt-4 text-white">Deactivate
                Account</button>
        </div>

        <div class="dashboard-bg-box">
            <div class="dashboard-title mb-4">
                <h3>Delete Account</h3>
            </div>
            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="usable" name="usable">
                    <label class="form-check-label ms-2" for="usable">No longer
                        usable</label>
                </div>
            </div>
            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="account" name="usable">
                    <label class="form-check-label ms-2" for="account">Want to switch on
                        other
                        account</label>
                </div>
            </div>
            <div class="privacy-box">
                <div class="form-check custom-form-check custom-form-check-2 d-flex align-items-center">
                    <input class="form-check-input" type="radio" id="other-2" name="usable">
                    <label class="form-check-label ms-2" for="other-2">Other</label>
                </div>
            </div>

            <button class="btn theme-bg-color btn-md fw-bold mt-4 text-white">Delete My
                Account</button>
        </div>
    </div>
</div>