 document.addEventListener('DOMContentLoaded', function() {
            let serverNow = <?= (float)$serverNow ?>;
            let targetTimeMs = <?= (float)$targetTimeMs ?>;

            if (!targetTimeMs || serverNow >= targetTimeMs) {
                return;
            }

            let clientStartPerf = performance.now();
            let hasReloaded = false;

            let timer = setInterval(function() {
                let elapsedMs = performance.now() - clientStartPerf;
                let currentServerTime = serverNow + elapsedMs;
                let distance = targetTimeMs - currentServerTime;

                if (distance <= 0) {
                    clearInterval(timer);
                    if (!hasReloaded) {
                        hasReloaded = true;
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    return;
                }

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                if (document.getElementById("cd-days")) document.getElementById("cd-days").innerText = days < 10 ? "0" + days : days;
                if (document.getElementById("cd-hours")) document.getElementById("cd-hours").innerText = hours < 10 ? "0" + hours : hours;
                if (document.getElementById("cd-minutes")) document.getElementById("cd-minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
                if (document.getElementById("cd-seconds")) document.getElementById("cd-seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
            }, 1000);
        });