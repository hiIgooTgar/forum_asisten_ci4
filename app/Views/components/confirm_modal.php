<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-3" role="document" style="max-width: 520px; width: 100%; margin: 0 auto;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px; overflow: hidden; background: #ffffff;">

            <div class="modal-body text-center px-3 px-sm-5 py-4 py-sm-5">
                <div id="confirmModalIconWrapper" class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: #fef3c7; color: #d97706; box-shadow: 0 10px 20px -5px rgba(217, 119, 6, 0.25); transition: all 0.3s ease;">
                    <i id="confirmModalIcon" class="fa fa-exclamation-triangle fa-2x fa-sm-3x"></i>
                </div>

                <h3 class="font-weight-bold mb-2" id="confirmModalTitle" style="color: var(--color-primary-combine-v2, #0c1d61); font-size: 1.3rem; letter-spacing: -0.02em;">
                    Apakah Anda Yakin?
                </h3>
                <p class="text-muted mb-4 mx-auto" id="confirmModalText" style="font-size: 0.9rem; line-height: 1.6; color: #64748b; max-width: 380px;">
                    Data yang dihapus tidak dapat dikembalikan!
                </p>

                <div class="d-flex flex-column-reverse flex-sm-row justify-content-center align-items-center" style="gap: 10px;">
                    <button type="button" class="btn btn-light w-100 w-sm-auto px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 4px; min-width: 130px; color: #475569; background-color: #f1f5f9; border: none; font-size: 0.9rem;">
                        Batal
                    </button>
                    <button type="button" id="confirmModalSubmitBtn" class="btn btn-danger w-100 w-sm-auto px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 4px; min-width: 130px; font-size: 0.9rem;">
                        Ya, Hapus!
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmModal(options = {}) {
        const title = options.title || 'Apakah Anda Yakin?';
        const text = options.text || 'Data yang dihapus tidak dapat dikembalikan!';
        const confirmText = options.confirmText || 'Ya, Hapus!';
        const confirmBtnClass = options.confirmBtnClass || 'btn-danger';
        const iconClass = options.iconClass || 'fa-exclamation-triangle';
        const iconBgColor = options.iconBgColor || '#fef3c7';
        const iconColor = options.iconColor || '#d97706';
        const actionUrl = options.actionUrl || null;
        const method = options.method || 'POST';

        $('#confirmModalTitle').text(title);
        $('#confirmModalText').text(text);
        $('#confirmModalSubmitBtn')
            .text(confirmText)
            .attr('class', 'btn w-100 w-sm-auto px-4 py-2 font-weight-bold shadow-sm ' + confirmBtnClass);

        $('#confirmModalIcon').attr('class', 'fa ' + iconClass + ' fa-2x fa-sm-3x');
        $('#confirmModalIconWrapper').css({
            'background-color': iconBgColor,
            'color': iconColor,
            'box-shadow': '0 10px 20px -5px ' + iconColor + '40'
        });

        $('#confirmModalSubmitBtn').off('click').on('click', function() {
            if (typeof options.onConfirm === 'function') {
                $('#confirmModal').modal('hide');
                options.onConfirm();
                return;
            }

            if (actionUrl) {
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

                dynamicForm.attr('action', actionUrl);

                $('<input>').attr({
                    type: 'hidden',
                    name: '<?= csrf_token() ?>',
                    value: '<?= csrf_hash() ?>'
                }).appendTo(dynamicForm);

                if (method.toUpperCase() !== 'POST') {
                    $('<input>').attr({
                        type: 'hidden',
                        name: '_method',
                        value: method.toUpperCase()
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