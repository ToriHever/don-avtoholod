<?php
/**
 * Попап «Задать вопрос» — подключается в footer.php, доступен на любой странице.
 */

$home_id = (int) get_option('page_on_front');
$phone1 = $home_id ? get_field('contacts_phone1', $home_id) : '';
$question_sent = isset($_GET['question_sent']);
?>
<div id="dah-question-modal" class="dah-modal" hidden>
  <div class="dah-modal__overlay" data-modal-close></div>
  <div class="dah-modal__panel" role="dialog" aria-modal="true" aria-labelledby="dah-question-modal-title">
    <button type="button" class="dah-modal__close" data-modal-close aria-label="Закрыть">&times;</button>

    <h3 id="dah-question-modal-title">Задать вопрос</h3>

    <?php if ($question_sent): ?>
      <p class="question-form__lead">
        Спасибо! Ваш вопрос отправлен, мы ответим как можно скорее.
        <?php if ($phone1): ?>
          Если срочно — позвоните нам: <a href="tel:<?php echo esc_attr($phone1); ?>"><?php echo esc_html($phone1); ?></a>.
        <?php endif; ?>
      </p>
    <?php else: ?>
      <p class="question-form__lead">Ответим в ближайшее рабочее время по телефону или почте.</p>
      <form method="post" action="">
        <?php wp_nonce_field('dah_question_form', 'dah_question_nonce'); ?>
        <label class="question-form__field">
          <span>Имя</span>
          <input type="text" name="name" placeholder="Как к вам обращаться">
        </label>
        <label class="question-form__field">
          <span>Телефон *</span>
          <input type="tel" name="phone" required placeholder="+7 (___) ___-__-__">
        </label>
        <label class="question-form__field">
          <span>Email</span>
          <input type="email" name="email" placeholder="you@mail.ru">
        </label>
        <label class="question-form__field">
          <span>Вопрос *</span>
          <textarea name="question" required rows="4" placeholder="Опишите, что случилось с кондиционером"></textarea>
        </label>
        <button type="submit" class="btn btn--primary question-form__submit">Отправить вопрос</button>
      </form>
    <?php endif; ?>
  </div>
</div>
