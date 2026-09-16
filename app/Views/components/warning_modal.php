<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-3" role="document" style="max-width: 420px; width: 100%; margin: 0 auto;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden; background-color: #ffffff;">
            <div class="modal-body text-center p-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background-color: #fee2e2; color: #dc2626;">
                    <i class="fa fa-exclamation-circle fa-2x"></i>
                </div>

                <h4 class="font-weight-bold mb-2" id="warningModalTitle" style="color: var(--color-primary-combine-v2, #0c1d61);">Peringatan</h4>
                <p class="text-muted mb-4" id="warningModalText" style="font-size: 14px; line-height: 1.5; color: #64748b;">
                    Pesan peringatan akan muncul di sini.
                </p>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 4px; background-color: var(--primary, #0a2481); border-color: var(--primary, #0a2481);">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showWarningModal(options = {}) {
        const title = options.title || 'Peringatan!';
        const text = options.text || 'Terjadi kesalahan, silakan periksa kembali inputan Anda.';

        $('#warningModalTitle').text(title);
        $('#warningModalText').text(text);
        $('#warningModal').modal('show');
    }
</script>