<?php
/**
 * Поля ACF для главной страницы.
 * Требуется бесплатный плагин "Advanced Custom Fields".
 *
 * Списки (несколько пунктов) редактируются в одном текстовом поле —
 * по формату, описанному в подсказке под каждым полем. Так не нужен
 * платный ACF PRO с повторителями.
 */

if (!function_exists('acf_add_local_field_group')) {
    return;
}

add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_dah_home',
        'title' => 'Главная страница — содержимое',
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'front-page.php',
                ],
            ],
        ],
        'fields' => [

            // Hero
            ['key' => 'f_hero_badge', 'label' => 'Бейдж над заголовком', 'name' => 'hero_badge', 'type' => 'text'],
            ['key' => 'f_hero_title', 'label' => 'Заголовок (H1)', 'name' => 'hero_title', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'f_hero_lead', 'label' => 'Подзаголовок', 'name' => 'hero_lead', 'type' => 'textarea'],
            ['key' => 'f_hero_primary_button', 'label' => 'Текст основной кнопки', 'name' => 'hero_primary_button', 'type' => 'text'],
            ['key' => 'f_hero_secondary_button', 'label' => 'Текст второй кнопки', 'name' => 'hero_secondary_button', 'type' => 'text'],
            [
                'key' => 'f_hero_stats', 'label' => 'Статистика', 'name' => 'hero_stats', 'type' => 'textarea',
                'instructions' => 'По одной строке на пункт. Формат: Значение :: Подпись',
            ],
            ['key' => 'f_hero_own_badge', 'label' => 'Текст плашки "свой стенд"', 'name' => 'hero_own_badge', 'type' => 'text'],
            ['key' => 'f_hero_media_badge_title', 'label' => 'Заголовок плашки на фото', 'name' => 'hero_media_badge_title', 'type' => 'text'],
            ['key' => 'f_hero_media_badge_text', 'label' => 'Текст плашки на фото', 'name' => 'hero_media_badge_text', 'type' => 'text'],

            // Features
            [
                'key' => 'f_features', 'label' => 'Преимущества (4 карточки)', 'name' => 'features', 'type' => 'textarea', 'rows' => 6,
                'instructions' => 'По одной строке на карточку. Формат: путь_к_иконке :: Заголовок :: Текст',
            ],

            // Services
            ['key' => 'f_services_eyebrow', 'label' => 'Услуги: надпись над заголовком', 'name' => 'services_eyebrow', 'type' => 'text'],
            ['key' => 'f_services_title', 'label' => 'Услуги: заголовок', 'name' => 'services_title', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'f_services_lead', 'label' => 'Услуги: подзаголовок', 'name' => 'services_lead', 'type' => 'textarea'],
            [
                'key' => 'f_services_groups', 'label' => 'Услуги: группы', 'name' => 'services_groups', 'type' => 'textarea', 'rows' => 14,
                'instructions' => 'Группы разделяйте пустой строкой. Первая строка группы — её название, остальные строки — пункты списка.',
            ],

            // Cooling issues
            ['key' => 'f_cooling_eyebrow', 'label' => 'Диагностика: надпись над заголовком', 'name' => 'cooling_eyebrow', 'type' => 'text'],
            ['key' => 'f_cooling_title', 'label' => 'Диагностика: заголовок', 'name' => 'cooling_title', 'type' => 'text'],
            ['key' => 'f_cooling_lead', 'label' => 'Диагностика: подзаголовок', 'name' => 'cooling_lead', 'type' => 'textarea'],
            ['key' => 'f_cooling_button', 'label' => 'Диагностика: текст кнопки', 'name' => 'cooling_button', 'type' => 'text'],
            [
                'key' => 'f_cooling_issues', 'label' => 'Диагностика: причины', 'name' => 'cooling_issues', 'type' => 'textarea', 'rows' => 8,
                'instructions' => 'По одной причине на строку.',
            ],

            // Refill steps
            ['key' => 'f_refill_eyebrow', 'label' => 'Заправка: надпись над заголовком', 'name' => 'refill_eyebrow', 'type' => 'text'],
            ['key' => 'f_refill_title', 'label' => 'Заправка: заголовок', 'name' => 'refill_title', 'type' => 'text'],
            ['key' => 'f_refill_lead', 'label' => 'Заправка: подзаголовок', 'name' => 'refill_lead', 'type' => 'textarea'],
            [
                'key' => 'f_refill_steps', 'label' => 'Заправка: шаги', 'name' => 'refill_steps', 'type' => 'textarea', 'rows' => 6,
                'instructions' => 'По одной строке на шаг. Формат: Заголовок :: Текст',
            ],

            // Heaters
            ['key' => 'f_heaters_eyebrow', 'label' => 'Отопители: надпись над заголовком', 'name' => 'heaters_eyebrow', 'type' => 'text'],
            ['key' => 'f_heaters_title', 'label' => 'Отопители: заголовок', 'name' => 'heaters_title', 'type' => 'text'],
            ['key' => 'f_heaters_lead', 'label' => 'Отопители: подзаголовок', 'name' => 'heaters_lead', 'type' => 'textarea'],
            [
                'key' => 'f_heaters_brands', 'label' => 'Отопители: бренды', 'name' => 'heaters_brands', 'type' => 'textarea', 'rows' => 6,
                'instructions' => 'По одному бренду на строку.',
            ],
            [
                'key' => 'f_heaters_details', 'label' => 'Отопители: подробные блоки', 'name' => 'heaters_details', 'type' => 'textarea', 'rows' => 6,
                'instructions' => 'По одной строке на блок. Формат: Заголовок :: Текст',
            ],

            // About
            ['key' => 'f_about_eyebrow', 'label' => 'О компании: надпись над заголовком', 'name' => 'about_eyebrow', 'type' => 'text'],
            ['key' => 'f_about_title', 'label' => 'О компании: заголовок', 'name' => 'about_title', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'f_about_lead', 'label' => 'О компании: подзаголовок', 'name' => 'about_lead', 'type' => 'textarea'],
            ['key' => 'f_about_badge_title', 'label' => 'О компании: заголовок плашки на фото', 'name' => 'about_badge_title', 'type' => 'text'],
            ['key' => 'f_about_badge_text', 'label' => 'О компании: текст плашки на фото', 'name' => 'about_badge_text', 'type' => 'text'],
            ['key' => 'f_about_own_badge', 'label' => 'О компании: текст плашки "свой стенд"', 'name' => 'about_own_badge', 'type' => 'text'],
            [
                'key' => 'f_about_stats', 'label' => 'О компании: статистика', 'name' => 'about_stats', 'type' => 'textarea',
                'instructions' => 'По одной строке на пункт. Формат: Значение :: Подпись',
            ],
            ['key' => 'f_about_paragraph1', 'label' => 'О компании: первый абзац', 'name' => 'about_paragraph1', 'type' => 'textarea'],
            [
                'key' => 'f_about_paragraph2_before', 'label' => 'О компании: второй абзац (до ссылки на адрес)', 'name' => 'about_paragraph2_before', 'type' => 'textarea',
                'instructions' => 'После этого текста автоматически добавляется ссылка "улицы Вавилова" на карту и точка.',
            ],

            // Facts
            ['key' => 'f_facts_eyebrow', 'label' => 'Факты: надпись над заголовком', 'name' => 'facts_eyebrow', 'type' => 'text'],
            ['key' => 'f_facts_title', 'label' => 'Факты: заголовок', 'name' => 'facts_title', 'type' => 'text'],
            [
                'key' => 'f_facts_items', 'label' => 'Факты: пункты', 'name' => 'facts_items', 'type' => 'textarea', 'rows' => 12,
                'instructions' => 'По одной строке на пункт. Формат: Заголовок :: Текст',
            ],

            ['key' => 'f_price_note', 'label' => 'Дисклеймер о ценах', 'name' => 'price_note', 'type' => 'textarea'],

            // Contacts
            ['key' => 'f_contacts_eyebrow', 'label' => 'Контакты: надпись над заголовком', 'name' => 'contacts_eyebrow', 'type' => 'text'],
            ['key' => 'f_contacts_title', 'label' => 'Контакты: заголовок', 'name' => 'contacts_title', 'type' => 'text'],
            ['key' => 'f_contacts_lead', 'label' => 'Контакты: подзаголовок', 'name' => 'contacts_lead', 'type' => 'textarea'],
            ['key' => 'f_contacts_address', 'label' => 'Контакты: адрес', 'name' => 'contacts_address', 'type' => 'text'],
            ['key' => 'f_contacts_map_url', 'label' => 'Контакты: ссылка на Яндекс.Карты', 'name' => 'contacts_map_url', 'type' => 'text'],
            ['key' => 'f_contacts_hours', 'label' => 'Контакты: режим работы', 'name' => 'contacts_hours', 'type' => 'text'],
            ['key' => 'f_contacts_email', 'label' => 'Контакты: email', 'name' => 'contacts_email', 'type' => 'text'],
            ['key' => 'f_contacts_phone1', 'label' => 'Контакты: телефон 1', 'name' => 'contacts_phone1', 'type' => 'text'],
            ['key' => 'f_contacts_phone2', 'label' => 'Контакты: телефон 2', 'name' => 'contacts_phone2', 'type' => 'text'],
            ['key' => 'f_contacts_question_button', 'label' => 'Контакты: текст кнопки "Задать вопрос"', 'name' => 'contacts_question_button', 'type' => 'text'],

            // FAQ
            ['key' => 'f_faq_eyebrow', 'label' => 'FAQ: надпись над заголовком', 'name' => 'faq_eyebrow', 'type' => 'text'],
            ['key' => 'f_faq_title', 'label' => 'FAQ: заголовок', 'name' => 'faq_title', 'type' => 'text'],
            ['key' => 'f_faq_form_title', 'label' => 'FAQ: заголовок формы', 'name' => 'faq_form_title', 'type' => 'text'],
            [
                'key' => 'f_faq_items', 'label' => 'FAQ: вопросы', 'name' => 'faq_items', 'type' => 'textarea', 'rows' => 12,
                'instructions' => 'По одной строке на вопрос. Формат: Вопрос :: Ответ',
            ],
        ],
    ]);
});

/**
 * Разбирает строку вида "часть1 :: часть2 :: часть3" в массив.
 */
function dah_split_line(string $line, int $limit = 0): array {
    $parts = array_map('trim', explode('::', $line));
    return $limit > 0 ? array_pad(array_slice($parts, 0, $limit), $limit, '') : $parts;
}

/**
 * Разбирает многострочное текстовое поле в массив непустых строк.
 */
function dah_split_lines(?string $text): array {
    if (empty($text)) {
        return [];
    }
    $lines = preg_split('/\r\n|\r|\n/', $text);
    return array_values(array_filter(array_map('trim', $lines), fn ($l) => $l !== ''));
}

/**
 * Разбирает поле "группы" (группы через пустую строку, первая строка — заголовок).
 */
function dah_split_groups(?string $text): array {
    if (empty($text)) {
        return [];
    }
    $blocks = preg_split('/\r\n\r\n|\n\n|\r\r/', trim($text));
    $groups = [];
    foreach ($blocks as $block) {
        $lines = dah_split_lines($block);
        if (empty($lines)) {
            continue;
        }
        $groups[] = [
            'title' => array_shift($lines),
            'items' => $lines,
        ];
    }
    return $groups;
}
