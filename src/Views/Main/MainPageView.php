<?php

namespace DupChallenge\Views\Main ;

class MainPageView
{
    /**
     * Render main page
     *
     * @return void
     */
    public static function renderMainPage()
    {
        ?>
        <div class="wrap">
            <h1>
                <?php echo esc_html(get_admin_page_title()); ?>
            </h1>

            <p>
                <?php _e('Here is the main page', 'dup-challenge'); ?>
            </p>

            <button class="button action" id="btn-directory-and-files-scan">Scan</button>
        </div>
        <?php
    }
}