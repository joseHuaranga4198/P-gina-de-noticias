/**
 * Portal de Noticias - Funcionalidad JavaScript y Accesibilidad WCAG 2.2 AA
 * Desarrollado para garantizar usabilidad universal, lectura en voz alta y modo adaptable.
 */

document.addEventListener('DOMContentLoaded', function () {
    // ========================================================
    // 1. GESTIÓN DE ACCESIBILIDAD: TAMAÑO DE TEXTO
    // ========================================================
    const htmlElement = document.documentElement;
    const btnFontInc = document.getElementById('btn-font-increase');
    const btnFontDec = document.getElementById('btn-font-decrease');
    const btnFontReset = document.getElementById('btn-font-reset');

    const fontSizes = ['small', 'normal', 'large', 'xlarge'];
    let currentFontIndex = 1; // 'normal'

    // Cargar preferencia previa
    const savedFontSize = localStorage.getItem('a11y-font-size');
    if (savedFontSize && fontSizes.includes(savedFontSize)) {
        currentFontIndex = fontSizes.indexOf(savedFontSize);
        applyFontSize(savedFontSize);
    }

    function applyFontSize(size) {
        htmlElement.setAttribute('data-font-size', size);
        localStorage.setItem('a11y-font-size', size);
    }

    if (btnFontInc) {
        btnFontInc.addEventListener('click', function () {
            if (currentFontIndex < fontSizes.length - 1) {
                currentFontIndex++;
                applyFontSize(fontSizes[currentFontIndex]);
            }
        });
    }

    if (btnFontDec) {
        btnFontDec.addEventListener('click', function () {
            if (currentFontIndex > 0) {
                currentFontIndex--;
                applyFontSize(fontSizes[currentFontIndex]);
            }
        });
    }

    if (btnFontReset) {
        btnFontReset.addEventListener('click', function () {
            currentFontIndex = 1;
            applyFontSize('normal');
        });
    }

    // ========================================================
    // 2. GESTIÓN DE ACCESIBILIDAD: ALTO CONTRASTE
    // ========================================================
    const btnHighContrast = document.getElementById('btn-high-contrast');
    const savedContrast = localStorage.getItem('a11y-contrast');

    if (savedContrast === 'high') {
        htmlElement.setAttribute('data-theme', 'high-contrast');
    }

    if (btnHighContrast) {
        btnHighContrast.addEventListener('click', function () {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'high-contrast' ? 'light' : 'high-contrast';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('a11y-contrast', newTheme === 'high-contrast' ? 'high' : 'normal');
            btnHighContrast.setAttribute('aria-pressed', newTheme === 'high-contrast');
        });
    }

    // ========================================================
    // 3. GESTIÓN DE ACCESIBILIDAD: MODO LECTURA SIMPLIFICADA
    // ========================================================
    const btnReadingMode = document.getElementById('btn-reading-mode');
    const savedReadingMode = localStorage.getItem('a11y-reading-mode');

    if (savedReadingMode === 'active') {
        document.body.classList.add('reading-mode');
    }

    if (btnReadingMode) {
        btnReadingMode.addEventListener('click', function () {
            const isActive = document.body.classList.toggle('reading-mode');
            localStorage.setItem('a11y-reading-mode', isActive ? 'active' : 'inactive');
            btnReadingMode.setAttribute('aria-pressed', isActive);
        });
    }

    // ========================================================
    // 4. LECTURA EN VOZ ALTA (Web Speech API) - WCAG 2.2
    // ========================================================
    const ttsPlay = document.getElementById('tts-play');
    const ttsPause = document.getElementById('tts-pause');
    const ttsStop = document.getElementById('tts-stop');
    const ttsStatus = document.getElementById('tts-status');

    if (ttsPlay && ('speechSynthesis' in window)) {
        let utterance = null;
        let isPaused = false;

        function getArticleText() {
            const titleElem = document.querySelector('.article-title');
            const excerptElem = document.querySelector('.article-excerpt');
            const contentElem = document.getElementById('article-content');

            let text = '';
            if (titleElem) text += titleElem.innerText + '. ';
            if (excerptElem) text += excerptElem.innerText + '. ';
            if (contentElem) text += contentElem.innerText;
            return text.trim();
        }

        function setButtonStates(playing, paused) {
            ttsPlay.disabled = playing && !paused;
            ttsPlay.style.cursor = (playing && !paused) ? 'not-allowed' : 'pointer';
            ttsPlay.style.background = (playing && !paused) ? '#e2e8f0' : '';

            ttsPause.disabled = !playing;
            ttsPause.style.cursor = !playing ? 'not-allowed' : 'pointer';
            ttsPause.style.background = !playing ? '#e2e8f0' : 'var(--color-bg-subtle, #f1f5f9)';

            ttsStop.disabled = !playing && !paused;
            ttsStop.style.cursor = (!playing && !paused) ? 'not-allowed' : 'pointer';
            ttsStop.style.background = (!playing && !paused) ? '#e2e8f0' : 'var(--color-bg-subtle, #f1f5f9)';
        }

        ttsPlay.addEventListener('click', function () {
            if (isPaused) {
                window.speechSynthesis.resume();
                isPaused = false;
                setButtonStates(true, false);
                if (ttsStatus) ttsStatus.textContent = 'Leyendo en voz alta...';
                return;
            }

            window.speechSynthesis.cancel();
            const textToRead = getArticleText();
            if (!textToRead) return;

            utterance = new SpeechSynthesisUtterance(textToRead);
            utterance.lang = 'es-ES';
            utterance.rate = 0.95; // Velocidad óptima de inteligibilidad

            // Buscar voz en español disponible
            const voices = window.speechSynthesis.getVoices();
            const spanishVoice = voices.find(v => v.lang.startsWith('es'));
            if (spanishVoice) {
                utterance.voice = spanishVoice;
            }

            utterance.onstart = function () {
                setButtonStates(true, false);
                if (ttsStatus) ttsStatus.textContent = 'Leyendo artículo...';
            };

            utterance.onend = function () {
                setButtonStates(false, false);
                isPaused = false;
                if (ttsStatus) ttsStatus.textContent = 'Lectura finalizada.';
            };

            utterance.onerror = function (event) {
                setButtonStates(false, false);
                isPaused = false;
                if (ttsStatus) ttsStatus.textContent = 'Error en la síntesis de voz.';
            };

            window.speechSynthesis.speak(utterance);
        });

        if (ttsPause) {
            ttsPause.addEventListener('click', function () {
                if (window.speechSynthesis.speaking && !isPaused) {
                    window.speechSynthesis.pause();
                    isPaused = true;
                    setButtonStates(true, true);
                    if (ttsStatus) ttsStatus.textContent = 'Lectura pausada.';
                }
            });
        }

        if (ttsStop) {
            ttsStop.addEventListener('click', function () {
                window.speechSynthesis.cancel();
                isPaused = false;
                setButtonStates(false, false);
                if (ttsStatus) ttsStatus.textContent = 'Lectura detenida.';
            });
        }
    } else if (ttsPlay) {
        // Fallback si el navegador no soporta Web Speech API
        if (ttsStatus) {
            ttsStatus.textContent = 'Síntesis de voz no disponible en este navegador.';
        }
        ttsPlay.disabled = true;
    }

    // ========================================================
    // 5. MENÚ MÓVIL RESPONSIVO
    // ========================================================
    const menuToggle = document.getElementById('menu-toggle');
    const mainNav = document.getElementById('main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            mainNav.classList.toggle('nav-open');
        });
    }

    // ========================================================
    // 6. NOTIFICACIONES FLASH TEMPORIZADAS
    // ========================================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 500);
        }, 5000);
    });
});
