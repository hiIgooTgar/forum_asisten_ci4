<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-dialog-centered px-3" role="document" style="max-width: 480px; width: 100%; margin: 0 auto;">
        <div class="modal-content border-0 shadow-lg position-relative" style="border-radius: 4px; overflow: hidden; background: #ffffff;">

            <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 14px; right: 18px; z-index: 10; opacity: 0.4; transition: all 0.2s; outline: none;">
                <span aria-hidden="true" style="font-size: 1.4rem; font-weight: 300;">&times;</span>
            </button>

            <div class="modal-body text-center px-4 pt-4 pb-4">

                <div id="warningModalIconWrapper" class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background-color: #fee2e2; color: #dc2626; transition: all 0.3s ease; border: 4px solid #ffffff;">
                    <i id="warningModalIcon" class="fa fa-exclamation-circle" style="font-size: 1.75rem;"></i>
                </div>

                <h4 class="font-weight-bold mb-2 text-dark" id="warningModalTitle" style="font-size: 1.25rem; letter-spacing: -0.01em; color: #0f172a;">
                    Peringatan!
                </h4>

                <p class="text-muted mb-4 mx-auto" id="warningModalText" style="font-size: 0.875rem; line-height: 1.6; color: #64748b; max-width: 360px;">
                    Terjadi kesalahan, silakan periksa kembali data Anda.
                </p>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-danger w-100 py-2 px-4 font-weight-bold text-white shadow-sm" data-dismiss="modal" style="border-radius: 4px; font-size: 0.875rem;  border: none; transition: all 0.2s;">
                        <span id="warningModalBtnText">Mengerti</span>
                    </button>
                </div>

            </div>

            <div class="modal-footer justify-content-center py-2 px-3 border-0" style="background-color: #f8fafc; border-top: 1px solid #f1f5f9 !important;">
                <span class="text-uppercase font-weight-bold text-secondary-c" style="font-size: 0.7rem; letter-spacing: 0.08em;">
                    Forum Asisten
                </span>
            </div>

        </div>
    </div>
</div>

<style>
    #warningModal .modal-dialog {
        transform: scale(0.94);
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    #warningModal.show .modal-dialog {
        transform: scale(1);
    }

    #warningModal .close:hover {
        opacity: 0.8 !important;
        transform: rotate(90deg);
    }

    .btn-warning-modal-ok:hover {
        background-color: var(--color-hover-primary, #081750) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px var(--color-transparent-primary, rgba(10, 36, 129, 0.25)) !important;
    }
</style>

<script>
    function showWarningModal(options = {}) {
        const config = {
            title: options.title || 'Peringatan!',
            text: options.text || 'Terjadi kesalahan, silakan periksa kembali inputan Anda.',
            btnText: options.btnText || 'Mengerti',
            iconClass: options.iconClass || 'fa-exclamation-circle',
            iconBgColor: options.iconBgColor || '#fee2e2',
            iconColor: options.iconColor || '#dc2626'
        };

        $('#warningModalTitle').text(config.title);
        $('#warningModalText').text(config.text);
        $('#warningModalBtnText').text(config.btnText);

        $('#warningModalIcon').attr('class', 'fa ' + config.iconClass);
        $('#warningModalIconWrapper').css({
            'background-color': config.iconBgColor,
            'color': config.iconColor,
            'box-shadow': '0 8px 20px -4px ' + config.iconColor + '40'
        });

        $('#warningModal').modal('show');
    }
</script>