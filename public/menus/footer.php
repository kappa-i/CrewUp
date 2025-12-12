<?php
// Charger les traductions si pas déjà fait
if (!isset($t)) {
    require_once __DIR__ . '/../../src/i18n/load-translation.php';

    if (!defined('COOKIE_NAME')) {
        define('COOKIE_NAME', 'lang');
        define('DEFAULT_LANG', 'fr');
    }

    $lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
    $t = loadTranslation($lang);
}

$currentLang = $lang ?? DEFAULT_LANG;
?>
<footer>
    <div class="footer-main">

        <div class="footer_col1">
            <div class="footer_title">CrewUp</div>
            <div class="footer_cta">
                <a href="/account/create.php" class="footer_btn">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg>
                    <span><?= htmlspecialchars($t['create_announcement_btn']) ?></span>
                </a>
            </div>

            <div class="footer_links">
                <a href="/"><?= htmlspecialchars($t['nav_home']) ?></a>
                <a href="/annonces.php"><?= htmlspecialchars($t['nav_announcements']) ?></a>
                <a href="/account/dashboard.php"><?= htmlspecialchars($t['nav_dashboard']) ?></a>
            </div>
            <div class="footer_col_right">
                <p class="footer_desc"><?= htmlspecialchars($t['footer_desc']) ?></p>
            </div>

        </div>

        <div class="footer_col2">
            <div class="copyright"><?= htmlspecialchars($t['rights']) ?></div>
            <div class="language_switcher">
                <a href="/change-language.php?lang=fr"
                    class="<?= $currentLang === 'fr' ? 'lang_selected' : '' ?>"
                    lang="fr">FR</a>
                <a href="/change-language.php?lang=en"
                    class="<?= $currentLang === 'en' ? 'lang_selected' : '' ?>"
                    lang="en">EN</a>
            </div>
            <div class="footer_social">
                <a href="https://github.com/kappa-i/CrewUp" target="_blank">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 .5a12 12 0 0 0-3.79 23.4c.6.11.82-.26.82-.58v-2.1c-3.34.73-4.04-1.61-4.04-1.61-.55-1.39-1.34-1.76-1.34-1.76-1.1-.75.08-.73.08-.73 1.22.09 1.86 1.26 1.86 1.26 1.08 1.86 2.83 1.32 3.52 1.01.11-.8.42-1.32.77-1.62-2.66-.3-5.46-1.33-5.46-5.93 0-1.31.47-2.38 1.24-3.22-.13-.3-.54-1.52.12-3.16 0 0 1.01-.32 3.3 1.23a11.47 11.47 0 0 1 6 0c2.29-1.55 3.3-1.23 3.3-1.23.66 1.64.25 2.86.12 3.16.77.84 1.24 1.91 1.24 3.22 0 4.61-2.8 5.63-5.47 5.92.43.37.82 1.1.82 2.22v3.29c0 .32.21.7.82.58A12 12 0 0 0 12 .5z" />
                    </svg>
                </a>
            </div>
        </div>

    </div>

</footer>