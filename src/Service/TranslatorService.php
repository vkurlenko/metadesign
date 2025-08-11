<?php

namespace App\Service;

class TranslatorService
{
    const array RU_EN_DICTIONARY = [
        "Коммерческое помещение" => "commerce",
        "Квартира" => "flat",
        "Дом" => "house",

        "Новостройка" => "new",
        "Вторичка" => "secondary",

        "Бизнес" => "business",
        "Комфорт" => "comfort",
        "Премиум" => "premium"
    ];
    const array EN_RU_DICTIONARY = [
        "commerce" => "Коммерческое помещение",
        "flat" => "Квартира",
        "house" => "Дом",

        "new" => "Новостройка",
        "secondary" => "Вторичка",

        "business" => "Бизнес",
        "comfort" => "Комфорт",
        "premium" => "Премиум"
    ];

    public function translate(string $word): string
    {
        return array_key_exists($word, self::RU_EN_DICTIONARY)
            ? self::RU_EN_DICTIONARY[$word]
            : self::EN_RU_DICTIONARY[$word];
    }
}