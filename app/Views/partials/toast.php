<?php
$session    = session();
$flash_type = null;
$flash_msg  = null;

foreach (['success', 'error', 'warning', 'info'] as $type) {
    if ($session->getFlashdata($type)) {
        $flash_type = $type;
        $flash_msg  = $session->getFlashdata($type);
        break;
    }
}

if ($flash_msg):
    $messages = [];

    if (is_array($flash_msg)) {
        foreach ($flash_msg as $msg) {
            $clean = trim(strip_tags((string)$msg));
            if ($clean !== '') {
                $messages[] = $clean;
            }
        }
    } else {
        $clean = trim(strip_tags((string)$flash_msg));
        if ($clean !== '') {
            $messages[] = $clean;
        }
    }

    $messages    = array_values(array_unique($messages));
    $is_multiple = count($messages) > 1;
    $toast_class = 'custom-toast-' . ($flash_type ?? 'error');
?>

    <div id="custom-toast-wrapper" class="custom-toast-wrapper">
        <div id="custom-toast-container" class="custom-toast-card <?= $toast_class; ?>">
            <div class="custom-toast-body">
                <div class="custom-toast-icon">
                    <?php if ($flash_type === 'success'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <?php elseif ($flash_type === 'warning'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    <?php elseif ($flash_type === 'info'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <?php endif; ?>
                </div>

                <div class="custom-toast-content">
                    <?php if ($is_multiple): ?>
                        <ul class="custom-toast-list">
                            <?php foreach ($messages as $msg): ?>
                                <li><?= esc($msg); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="custom-toast-message"><?= esc($messages[0] ?? ''); ?></p>
                    <?php endif; ?>
                </div>

                <button type="button" class="custom-toast-close" onclick="closeCustomToast()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="custom-toast-progress-bg">
                <div class="custom-toast-progress-bar"></div>
            </div>
        </div>
    </div>

    <style>
        .custom-toast-wrapper {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            width: 100%;
            max-width: 420px;
            pointer-events: none;
            padding: 0 15px;
            box-sizing: border-box;
        }

        .custom-toast-card {
            pointer-events: auto;
            width: 100%;
            border-radius: 4px;
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
            animation: customToastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .custom-toast-success {
            background-color: #059669;
        }

        .custom-toast-warning {
            background-color: #d97706;
        }

        .custom-toast-info {
            background-color: #0284c7;
        }

        .custom-toast-error {
            background-color: #e11d48;
        }

        .custom-toast-body {
            padding: 15px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .custom-toast-icon {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            margin-top: 1px;
        }

        .custom-toast-icon svg {
            width: 100%;
            height: 100%;
            stroke: currentColor;
        }

        .custom-toast-content {
            flex: 1;
            min-width: 0;
            font-size: 13.5px;
            line-height: 1.5;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .custom-toast-message {
            margin: 0;
            font-weight: 500;
        }

        .custom-toast-list {
            margin: 0;
            padding-left: 18px;
            list-style-type: disc;
        }

        .custom-toast-list li {
            margin-bottom: 2px;
        }

        .custom-toast-list li:last-child {
            margin-bottom: 0;
        }

        .custom-toast-close {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
            padding: 2px;
            margin: -4px -4px 0 0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s, background-color 0.15s;
            flex-shrink: 0;
        }

        .custom-toast-close:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .custom-toast-close svg {
            width: 18px;
            height: 18px;
        }

        .custom-toast-progress-bg {
            width: 100%;
            height: 4px;
            background-color: rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .custom-toast-progress-bar {
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            width: 100%;
            animation: customToastCountdown 6s linear forwards;
        }

        .custom-toast-card.hide-toast {
            animation: customToastSlideOut 0.3s ease-in forwards;
        }

        @keyframes customToastSlideIn {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes customToastSlideOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-15px) scale(0.95);
            }
        }

        @keyframes customToastCountdown {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        @media (max-width: 576px) {
            .custom-toast-wrapper {
                top: 12px;
                right: 0;
                left: 0;
                max-width: 100%;
                padding: 0 12px;
            }

            .custom-toast-body {
                padding: 12px 14px;
            }

            .custom-toast-content {
                font-size: 12.5px;
            }
        }
    </style>

    <script>
        function closeCustomToast() {
            const toast = document.getElementById('custom-toast-container');
            const wrapper = document.getElementById('custom-toast-wrapper');

            if (toast) {
                toast.classList.add('hide-toast');
                setTimeout(() => {
                    if (wrapper) wrapper.remove();
                }, 300);
            }
        }

        setTimeout(() => {
            closeCustomToast();
        }, 6000);
    </script>
<?php endif; ?>