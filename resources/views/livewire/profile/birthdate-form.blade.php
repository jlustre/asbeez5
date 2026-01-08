<div>
    <div class="d-inline-flex align-items-center gap-2">
        @if(!$editing)
        <span>{{ $formatted }}</span>
        <a href="#" wire:click.prevent="edit">Edit</a>
        @else
        <input type="date" class="form-control" style="max-width: 180px;" wire:model.lazy="birthdate">
        <button type="button" class="btn btn-sm btn-primary" wire:click="save">Save</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="cancel">Cancel</button>
        @error('birthdate') <div class="text-danger small">{{ $message }}</div> @enderror
        @endif
    </div>

    <!-- Toast for success notifications -->
    <div class="position-fixed" style="z-index: 1080; right: 16px; bottom: 16px;" wire:ignore>
        <div id="profile-birthdate-toast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" data-toast-body>Saved</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (payload = {}) => {
                const toastEl = document.getElementById('profile-birthdate-toast');
                if (!toastEl) return;
                const bodyEl = toastEl.querySelector('[data-toast-body]');
                if (bodyEl) bodyEl.textContent = payload.message || 'Saved';
                let toast;
                try { toast = bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 2500 }); }
                catch { /* bootstrap not present */ return; }
                toast.show();
            });
        });
    </script>
</div>