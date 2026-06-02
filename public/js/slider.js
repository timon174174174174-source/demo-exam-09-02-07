/* Слайдер: авто-переключение каждые 3 секунды, кнопки «вперёд/назад», точки */
(function () {
    const slider = document.querySelector('[data-slider]');
    if (!slider) return;

    const track = slider.querySelector('.slider__track');
    const slides = Array.from(slider.querySelectorAll('.slider__slide'));
    const dots = Array.from(slider.querySelectorAll('.slider__dot'));
    const total = slides.length;
    let index = 0;
    let timer = null;

    function go(i) {
        index = (i + total) % total;
        track.style.transform = 'translateX(-' + index * 100 + '%)';
        dots.forEach((d, k) => d.classList.toggle('is-active', k === index));
    }

    const next = () => go(index + 1);
    const prev = () => go(index - 1);
    const start = () => { stop(); timer = setInterval(next, 3000); };
    function stop() { if (timer) { clearInterval(timer); timer = null; } }

    slider.querySelector('.slider__btn--next').addEventListener('click', () => { next(); start(); });
    slider.querySelector('.slider__btn--prev').addEventListener('click', () => { prev(); start(); });
    dots.forEach((d, k) => d.addEventListener('click', () => { go(k); start(); }));

    // пауза при наведении курсора
    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);

    go(0);
    start();
})();
