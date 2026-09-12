<?php
$session    = session();
$flash_type = null;
$flash_msg  = null;

if ($session->getFlashdata('success')) {
    $flash_type = 'success';
    $flash_msg  = $session->getFlashdata('success');
} elseif ($session->getFlashdata('error')) {
    $flash_type = 'error';
    $flash_msg  = $session->getFlashdata('error');
} elseif ($session->getFlashdata('warning')) {
    $flash_type = 'warning';
    $flash_msg  = $session->getFlashdata('warning');
} elseif ($session->getFlashdata('info')) {
    $flash_type = 'info';
    $flash_msg  = $session->getFlashdata('info');
}

if ($flash_msg):
    $messages = [];
    if (is_array($flash_msg)) {
        $messages = array_filter(array_map('trim', $flash_msg));
    } else {
        $cleaned_msg = trim(strip_tags((string) $flash_msg, '<p><li><br>'));
        if (strpos($cleaned_msg, '<p>') !== false || strpos($cleaned_msg, '<li>') !== false) {
            $dom = new DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8"?>' . $cleaned_msg);
            foreach ($dom->getElementsByTagName('*') as $element) {
                if (in_array($element->tagName, ['p', 'li'])) {
                    $text = trim($element->textContent);
                    if (!empty($text)) $messages[] = $text;
                }
            }
        } elseif (strpos($cleaned_msg, '<br>') !== false) {
            $messages = array_filter(array_map('trim', explode('<br>', $cleaned_msg)));
        } else {
            $messages = [trim(strip_tags((string) $flash_msg))];
        }
    }

    $messages    = array_values(array_unique($messages));
    $is_multiple = count($messages) > 1;

    $bg_color = 'bg-rose-600';
    if ($flash_type === 'success') $bg_color = 'bg-emerald-600';
    elseif ($flash_type === 'warning') $bg_color = 'bg-amber-500';
    elseif ($flash_type === 'info') $bg_color = 'bg-sky-600';
?>

    <div class="fixed top-3 right-3 left-3 sm:left-auto sm:top-5 sm:right-5 z-[9999] flex justify-end pointer-events-none">
        <div id="toast-container"
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-[-10px] sm:translate-y-0 sm:translate-x-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-init="setTimeout(() => { show = false; setTimeout(() => document.getElementById('toast-container')?.remove(), 300); }, 6000)"
            class="pointer-events-auto w-full max-w-full sm:max-w-md rounded-lg sm:rounded-xl shadow-2xl text-white transition-all duration-300 border border-white/20 backdrop-blur-md overflow-hidden <?= $bg_color; ?>">

            <div class="p-3.5 sm:p-4 flex items-start justify-between gap-2.5 sm:gap-3">
                <div class="flex items-start gap-2.5 sm:gap-3 min-w-0 flex-1">
                    <div class="shrink-0 mt-0.5">
                        <?php if ($flash_type === 'success'): ?>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        <?php elseif ($flash_type === 'warning'): ?>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        <?php elseif ($flash_type === 'info'): ?>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        <?php else: ?>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        <?php endif; ?>
                    </div>

                    <div class="text-xs sm:text-sm font-medium leading-relaxed min-w-0 flex-1 break-words">
                        <?php if ($is_multiple): ?>
                            <ul class="list-disc list-inside space-y-1">
                                <?php foreach ($messages as $msg): ?>
                                    <li class="break-words"><?= esc($msg); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="break-words"><?= esc($messages[0] ?? ''); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="button"
                    @click="show = false; setTimeout(() => document.getElementById('toast-container')?.remove(), 300)"
                    class="text-white/70 hover:text-white p-1 rounded-lg transition-colors shrink-0 cursor-pointer -mr-1 -mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="w-full bg-black/20 h-1 overflow-hidden">
                <div id="toast-progress-bar" class="bg-white/80 h-full w-full"></div>
            </div>
        </div>
    </div>

    <style>
        #toast-progress-bar {
            animation: toastCountdown 6s linear forwards;
        }

        @keyframes toastCountdown {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
<?php endif; ?>