<footer class="footer">
  <div class="container footer__grid">
    <div>
      <div class="footer__logo">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/favicon-light.ico'); ?>" alt="" class="footer__logo-icon">
        Дон Авто Холод
      </div>
      <p class="footer__text">
        Центр по ремонту и установке автокондиционеров и рефрижераторов в Ростове-на-Дону.
        Компания основана в 2006 году.
      </p>
    </div>

    <div>
      <h4>Разделы</h4>
      <ul class="footer__links">
        <li><a href="<?php echo esc_url(home_url('/#services')); ?>">Ремонт и заправка</a></li>
        <li><a href="<?php echo esc_url(home_url('/#heaters')); ?>">Автономные отопители</a></li>
        <li><a href="<?php echo esc_url(home_url('/#about')); ?>">О нас</a></li>
        <li><a href="<?php echo esc_url(home_url('/#contacts')); ?>">Контакты</a></li>
      </ul>
    </div>

    <div>
      <h4>Информация</h4>
      <ul class="footer__links">
        <li><a href="/dostavka-i-oplata/">Доставка и оплата</a></li>
        <li><a href="#">Автопредприятиям</a></li>
        <li><a href="#">Вакансии</a></li>
        <li><a href="#">Соглашение на обработку персональных данных</a></li>
      </ul>
    </div>

    <div>
      <h4>Контакты</h4>
      <ul class="footer__links">
        <li>
          <a href="https://yandex.ru/maps/-/CTxSIHkb" target="_blank" rel="noopener">
            г. Ростов-на-Дону, ул. Вавилова, 58, АТП-3
          </a>
        </li>
        <li><a href="tel:+79287753852">8-928-775-38-52</a></li>
        <li><a href="tel:+79282265846">8-928-226-58-46</a></li>
        <li><a href="mailto:info@donavtoholod.ru">info&#64;donavtoholod.ru</a></li>
        <li>Пн–Пт: 8:00–17:00, Сб: 9:00–15:00, Вс: выходной</li>
      </ul>
    </div>
  </div>

  <div class="footer__bottom container">
    <span>&copy; <?php echo esc_html(date('Y')); ?> Дон Авто Холод</span>
    <span class="footer__price-note">
      Сведения о ценах на сайте носят информационный характер и не являются публичной офертой.
    </span>
  </div>
</footer>

<?php get_template_part('template-parts/question-modal'); ?>

<script>
  (function () {
    var header = document.querySelector('.header');
    if (!header) return;
    var setHeaderHeight = function () {
      document.documentElement.style.setProperty('--dah-header-h', header.offsetHeight + 'px');
    };
    setHeaderHeight();
    window.addEventListener('resize', setHeaderHeight);
    window.addEventListener('load', setHeaderHeight);
  })();

  (function () {
    var burger = document.getElementById('dah-burger');
    var nav = document.getElementById('dah-nav');
    if (!burger || !nav) return;
    burger.addEventListener('click', function () {
      nav.classList.toggle('header__nav--open');
    });
    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('header__nav--open');
      });
    });
  })();

  (function () {
    var modal = document.getElementById('dah-question-modal');
    if (!modal) return;
    var openModal = function () {
      modal.hidden = false;
      document.body.style.overflow = 'hidden';
    };
    var closeModal = function () {
      modal.hidden = true;
      document.body.style.overflow = '';
    };
    document.querySelectorAll('[data-modal-open="dah-question-modal"]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        openModal();
      });
    });
    modal.querySelectorAll('[data-modal-close]').forEach(function (btn) {
      btn.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeModal();
    });
    if (window.location.search.indexOf('question_sent=1') !== -1) {
      openModal();
    }
  })();

  document.querySelectorAll('.quantity').forEach(function (wrap) {
    var input = wrap.querySelector('input.qty');
    if (!input || wrap.classList.contains('dah-qty-ready')) return;
    wrap.classList.add('dah-qty-ready');

    var minus = document.createElement('button');
    minus.type = 'button';
    minus.className = 'dah-qty-btn dah-qty-btn--minus';
    minus.textContent = '−';

    var plus = document.createElement('button');
    plus.type = 'button';
    plus.className = 'dah-qty-btn dah-qty-btn--plus';
    plus.textContent = '+';

    wrap.insertBefore(minus, input);
    wrap.appendChild(plus);

    var step = parseFloat(input.step) || 1;
    var min = parseFloat(input.min) || 1;

    minus.addEventListener('click', function () {
      var value = Math.max(min, (parseFloat(input.value) || min) - step);
      input.value = value;
      input.dispatchEvent(new Event('change'));
    });
    plus.addEventListener('click', function () {
      var value = (parseFloat(input.value) || min) + step;
      input.value = value;
      input.dispatchEvent(new Event('change'));
    });
  });

  (function () {
    var toggle = document.getElementById('dah-toggle-review-form');
    var respond = document.getElementById('respond');
    if (!toggle || !respond) return;
    toggle.addEventListener('click', function () {
      respond.classList.toggle('dah-open');
    });
  })();

  document.querySelectorAll('.shop-sidebar__toggle').forEach(function (button) {
    button.addEventListener('click', function () {
      var item = button.closest('.shop-sidebar__item');
      if (!item) return;
      var isOpen = item.classList.toggle('is-open');
      button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  });

  (function () {
    var noReviews = document.querySelector('.woocommerce-noreviews');
    if (!noReviews) return;
    var wrapper = document.createElement('div');
    wrapper.className = 'dah-no-reviews';
    wrapper.innerHTML = '<p class="dah-no-reviews__title">Отзывов пока нет</p>' +
      '<p class="dah-no-reviews__text">Будьте первым, кто поделится мнением об этом товаре — это поможет другим покупателям.</p>';
    noReviews.replaceWith(wrapper);

    var toggle = document.getElementById('dah-toggle-review-form');
    if (toggle) {
      wrapper.appendChild(toggle);
    }
  })();

  document.querySelectorAll('.dah-carousel').forEach(function (carousel) {
    var track = carousel.querySelector('.dah-carousel__track');
    var prev = carousel.querySelector('.dah-carousel__nav--prev');
    var next = carousel.querySelector('.dah-carousel__nav--next');
    if (!track || !prev || !next) return;
    var scrollByAmount = function () {
      var card = track.querySelector('li');
      return card ? card.getBoundingClientRect().width + 20 : 260;
    };
    prev.addEventListener('click', function () {
      track.scrollBy({ left: -scrollByAmount(), behavior: 'smooth' });
    });
    next.addEventListener('click', function () {
      track.scrollBy({ left: scrollByAmount(), behavior: 'smooth' });
    });
  });
</script>

<?php wp_footer(); ?>
</body>
</html>
