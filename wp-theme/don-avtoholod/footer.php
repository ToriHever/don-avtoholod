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

<script>
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
</script>

<?php wp_footer(); ?>
</body>
</html>
