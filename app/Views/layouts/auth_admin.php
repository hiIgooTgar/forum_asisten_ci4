<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Login') ?> - Forum Asisten Universitas Amikom Purwokerto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/font/font-style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind/output.css') ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://unpkg.com/split-type"></script>

    <style>
        .grid-pattern-bg {
            background-color: #0c1d61;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        @keyframes ultraSmoothShine {
            0% {
                transform: translateX(-160%) skewX(-20deg);
                opacity: 0;
            }

            3% {
                opacity: 0.8;
            }

            22% {
                opacity: 0.8;
            }

            25%,
            100% {
                transform: translateX(260%) skewX(-20deg);
                opacity: 0;
            }
        }

        .animate-smooth-shine {
            animation: ultraSmoothShine 8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        @keyframes ambientAura {

            0%,
            100% {
                opacity: 0.35;
                transform: scale(1);
            }

            50% {
                opacity: 0.65;
                transform: scale(1.1);
            }
        }

        .animate-ambient-aura {
            animation: ambientAura 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white antialiased overflow-x-hidden font-sans">

    <?= $this->include('partials/toast') ?>

    <section class="flex flex-col md:flex-row w-full min-h-screen relative overflow-x-hidden bg-white">
        <?= $this->renderSection('content') ?>
    </section>

    <script src="<?= base_url('assets/js/animation/animation-gsap.js'); ?>"></script>
    <?= $this->renderSection('script') ?>
</body>

</html>