<style>
    /* Ensure profile image fully covers the circular frame */
    .profile-box .profile-image {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        overflow: hidden;
        margin-inline: auto;
        padding: 0;
        position: relative;
        background-color: #f2f2f2;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Use background cover to avoid intrinsic img sizing conflicts */
    .profile-box .profile-image .avatar-cover {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: block;
    }

    .profile-box .profile-image .position-relative {
        width: 100%;
        height: 100%;
    }

    .profile-box .profile-image .position-relative .cover-icon {
        position: absolute;
        right: 6px;
        bottom: 6px;
        z-index: 2;
    }

    .profile-box .profile-image .position-relative .cover-icon input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Cropper modal sizing */
    .avatar-cropper-container {
        width: 100%;
        max-height: 60vh;
    }

    .avatar-cropper-container img {
        max-width: 100%;
        display: block;
    }

    @media (min-width: 992px) {
        .profile-box .profile-image {
            width: 160px;
            height: 160px;
        }
    }

    /* Background change icon pinned to circle's lower-right */
    .profile-box .profile-image .bg-cover-icon {
        position: absolute;
        right: 6px;
        bottom: 6px;
        z-index: 3;
    }

    .profile-box .profile-image .bg-cover-icon .bg-btn {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        cursor: pointer;
    }

    @media (min-width: 992px) {
        .profile-box .profile-image .bg-cover-icon .bg-btn {
            width: 30px;
            height: 30px;
        }
    }
</style>
<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-xxl-3 col-lg-4">
                <div class="dashboard-left-sidebar">
                    <div class="close-button d-flex d-lg-none">
                        <button class="close-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="profile-box">
                        <div class="cover-image">
                            <img src="{{ asset('assets/images/inner-page/cover-img.jpg') }}"
                                class="img-fluid blur-up lazyload" alt="">
                        </div>

                        <div class="profile-contain">
                            @php
                            $bgUrl = optional(auth()->user()->profile)->background_url;
                            @endphp
                            <div class="profile-image"
                                style="{{ $bgUrl ? 'background-image: url(' . $bgUrl . ');' : '' }}">
                                <div class="position-relative">
                                    @php
                                    $avatarUrl = auth()->user()->profile_photo_path
                                    ?
                                    \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->profile_photo_path)
                                    : asset('assets/images/inner-page/user/1.jpg');
                                    @endphp
                                    <span class="avatar-cover"
                                        style="background-image: url('{{ $avatarUrl }}');"></span>
                                    <div class="cover-icon">
                                        <form action="{{ route('profile.photo.store') }}" method="POST"
                                            enctype="multipart/form-data" onsubmit="return false;">
                                            @csrf
                                            <i class="fa-solid fa-pen">
                                                <input id="profilePhotoInput" name="profile_photo" type="file"
                                                    accept="image/*">
                                            </i>
                                        </form>
                                    </div>
                                    <div class="bg-cover-icon">
                                        <button type="button" class="bg-btn" title="Change avatar background"
                                            data-bs-toggle="modal" data-bs-target="#avatarBgModal">
                                            <i class="fa-solid fa-link"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-name">
                                <h3>{{ optional(auth()->user()->profile)->fullName() ?? auth()->user()->username }}</h3>
                                <h6 class="text-content">{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>

                    @include('user.user-profile-menu')
                </div>
            </div>

            @include('user.dashboard.right-dashboard')
        </div>
    </div>
</section>
<!-- Avatar Background Modal -->
<div class="modal fade" id="avatarBgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Avatar Background</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.background.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Background Image URL</label>
                        <input type="url" name="background_url" class="form-control"
                            placeholder="https://example.com/image.jpg" value="{{ $bgUrl ?? '' }}">
                    </div>
                    <div class="text-center my-2">or</div>
                    <div class="mb-3">
                        <label class="form-label">Upload Background Image</label>
                        <input type="file" name="background_image" class="form-control" accept="image/*">
                    </div>
                    <small class="text-muted">Providing a URL or uploading an image will set your avatar
                        background.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn theme-bg-color text-light">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cropper.js Modal -->
<link rel="stylesheet" href="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.css">
<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="avatar-cropper-container">
                    <img id="cropperImage" alt="Crop preview" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmCropBtn" class="btn theme-bg-color text-light">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
    (function() {
        const input = document.getElementById('profilePhotoInput');
        const imgEl = document.getElementById('cropperImage');
        const modalEl = document.getElementById('cropperModal');
        const confirmBtn = document.getElementById('confirmCropBtn');
        const avatarCover = document.querySelector('.profile-image .avatar-cover');
        const uploadUrl = "{{ route('profile.photo.store') }}";
        const csrf = "{{ csrf_token() }}";
        let cropper;
        let bsModal;

        function openModal() {
            bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
        }

        modalEl.addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            imgEl.src = '';
            if (input) input.value = '';
        });

        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    imgEl.src = ev.target.result;
                    openModal();
                    setTimeout(() => {
                        cropper = new Cropper(imgEl, {
                            aspectRatio: 1,
                            viewMode: 1,
                            background: false,
                            autoCropArea: 1,
                            responsive: true,
                        });
                    }, 50);
                };
                reader.readAsDataURL(file);
            });
        }

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({ width: 512, height: 512 });
                if (!canvas) return;
                canvas.toBlob(function(blob) {
                    const fd = new FormData();
                    fd.append('profile_photo', blob, 'avatar.jpg');
                    fetch(uploadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: fd
                    }).then(async (res) => {
                        try {
                            const data = await res.json();
                            if (data && data.url) {
                                if (avatarCover) avatarCover.style.backgroundImage = `url('${data.url}?t=${Date.now()}')`;
                            }
                        } catch (_) {}
                        bsModal && bsModal.hide();
                        // Soft refresh the avatar on fallback
                        setTimeout(() => window.location.reload(), 300);
                    }).catch(() => {
                        bsModal && bsModal.hide();
                        window.location.reload();
                    });
                }, 'image/jpeg', 0.92);
            });
        }
    })();
</script>