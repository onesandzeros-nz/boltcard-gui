<?php

use SilverStripe\Admin\ModelAdmin;


class CardsAdmin extends ModelAdmin {


    private static $managed_models = [
        cards::class,
        card_payments::class,
    ];

    private static $url_segment = 'cards';
    private static $menu_title = 'Cards';
    private static $menu_icon_class = 'font-icon-address-card';

}