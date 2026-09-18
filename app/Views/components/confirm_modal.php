<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-dialog-centered px-3" role="document" style="max-width: 480px; width: 100%; margin: 0 auto;">
        <div class="modal-content border-0 shadow-lg position-relative" style="border-radius: 4px; overflow: hidden; background: #ffffff;">

            <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="top: 14px; right: 18px; z-index: 10; opacity: 0.4; transition: all 0.2s; outline: none;">
                <span aria-hidden="true" style="font-size: 1.4rem; font-weight: 300;">&times;</span>
            </button>

            <div class="modal-body text-center px-4 pt-4 pb-4">

                <div id="confirmModalIconWrapper" class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background-color: #fef3c7; color: #d97706; transition: all 0.3s ease; border: 4px solid #ffffff;">
                    <i id="confirmModalIcon" class="fa fa-exclamation-triangle" style="font-size: 1.75rem;"></i>
                </div>

                <h4 class="font-weight-bold mb-2 text-dark" id="confirmModalTitle" style="font-size: 1.25rem; letter-spacing: -0.01em; color: #0f172a;">
                    Apakah Anda Yakin?
                </h4>

                <p class="text-muted mb-4 mx-auto" id="confirmModalText" style="font-size: 0.875rem; line-height: 1.6; color: #64748b; max-width: 380px;">
                    Data yang dihapus tidak dapat dikembalikan!
                </p>

                <div class="d-flex flex-column-reverse flex-sm-row justify-content-center align-items-center" style="gap: 10px;">
                    <button type="button" class="btn btn-modal-cancel w-100 py-2 px-4 font-weight-semibold" data-dismiss="modal" style="border-radius: 4px; color: #475569; background-color: #f1f5f9; border: 1px solid #e2e8f0; font-size: 0.875rem; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="button" id="confirmModalSubmitBtn" class="btn btn-danger btn-modal-confirm w-100 py-2 px-4 font-weight-bold shadow-sm" style="border-radius: 4px; font-size: 0.875rem; transition: all 0.2s;">
                        <span id="confirmModalBtnText">Ya, Hapus!</span>
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
    #confirmModal .modal-dialog {
        transform: scale(0.94);
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    #confirmModal.show .modal-dialog {
        transform: scale(1);
    }

    .close:hover {
        opacity: 0.8 !important;
        transform: rotate(90deg);
    }

    .btn-modal-cancel:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }

    .btn-modal-confirm:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25) !important;
    }
</style>

<script>
    function showConfirmModal(options = {}) {
        const config = {
            title: options.title || 'Apakah Anda Yakin?',
            text: options.text || 'Data yang dihapus tidak dapat dikembalikan!',
            confirmText: options.confirmText || 'Ya, Hapus!',
            confirmBtnClass: options.confirmBtnClass || 'btn-danger',
            iconClass: options.iconClass || 'fa-exclamation-triangle',
            iconBgColor: options.iconBgColor || '#fef3c7',
            iconColor: options.iconColor || '#d97706',
            actionUrl: options.actionUrl || null,
            method: (options.method || 'POST').toUpperCase(),
            formId: options.formId || null,
            onConfirm: typeof options.onConfirm === 'function' ? options.onConfirm : null
        };

        $('#confirmModalTitle').text(config.title);
        $('#confirmModalText').text(config.text);
        $('#confirmModalBtnText').text(config.confirmText);

        $('#confirmModalSubmitBtn')
            .attr('class', 'btn btn-modal-confirm w-100 py-2 px-4 font-weight-bold shadow-sm ' + config.confirmBtnClass)
            .prop('disabled', false);

        $('#confirmModalIcon').attr('class', 'fa ' + config.iconClass);
        $('#confirmModalIconWrapper').css({
            'background-color': config.iconBgColor,
            'color': config.iconColor,
            'box-shadow': '0 8px 20px -4px ' + config.iconColor + '40'
        });

        $('#confirmModalSubmitBtn').off('click').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true);
            $('#confirmModalBtnText').html('<i class="fa fa-spinner fa-spin mr-1"></i> Memproses...');

            if (config.onConfirm) {
                $('#confirmModal').modal('hide');
                config.onConfirm();
                return;
            }

            if (config.formId) {
                const targetForm = $('#' + config.formId);
                if (targetForm.length > 0) {
                    $('#confirmModal').modal('hide');
                    targetForm.submit();
                    return;
                }
            }

            if (config.actionUrl) {
                let dynamicForm = $('#dynamicConfirmForm');
                if (dynamicForm.length === 0) {
                    dynamicForm = $('<form>', {
                        id: 'dynamicConfirmForm',
                        method: 'POST',
                        style: 'display:none;'
                    }).appendTo('body');
                } else {
                    dynamicForm.empty();
                }

                dynamicForm.attr('action', config.actionUrl);

                $('<input>').attr({
                    type: 'hidden',
                    name: '<?= csrf_token() ?>',
                    value: '<?= csrf_hash() ?>'
                }).appendTo(dynamicForm);

                if (config.method !== 'POST') {
                    $('<input>').attr({
                        type: 'hidden',
                        name: '_method',
                        value: config.method
                    }).appendTo(dynamicForm);
                }

                $('#confirmModal').modal('hide');
                dynamicForm.submit();
            } else {
                $('#confirmModal').modal('hide');
            }
        });

        $('#confirmModal').modal('show');
    }
</script>