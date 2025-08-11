<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CalculateRequest
{
    const string REALTY_STATUS_TYPE_NEW = "new";
    const string REALTY_STATUS_TYPE_SECONDARY = "secondary";

    const string REALTY_TYPE_FLAT = "flat";
    const string REALTY_TYPE_COMMERCE = "commerce";
    const string REALTY_TYPE_HOUSE = "house";

    const string REPAIR_TYPE_COMFORT = "comfort";
    const string REPAIR_TYPE_BUSINESS = "business";
    const string REPAIR_TYPE_PREMIUM = "premium";
    #[SerializedName('room-type')]
    #[Assert\NotBlank(
        message: "Поле не может быть пустым."
    )]
    #[Assert\Choice(
        [self::REALTY_STATUS_TYPE_NEW, self::REALTY_STATUS_TYPE_SECONDARY],
        message: "Выберите вариант из списка."
    )]
    public string $realtyStatusType;
    #[SerializedName('realty-type')]
    #[Assert\NotBlank(
        message: "Поле не может быть пустым."
    )]
    #[Assert\Choice(
        [self::REALTY_TYPE_FLAT, self::REALTY_TYPE_COMMERCE, self::REALTY_TYPE_HOUSE],
        message: "Выберите вариант из списка."
    )]
    public string $realtyType;
    #[SerializedName('repair-type')]
    #[Assert\NotBlank(
        message: "Поле не может быть пустым."
    )]
    #[Assert\Choice(
        [self::REPAIR_TYPE_COMFORT, self::REPAIR_TYPE_BUSINESS, self::REPAIR_TYPE_PREMIUM],
        message: "Выберите вариант из списка."
    )]
    public string $repairType;
    #[SerializedName('area-square')]
    #[Assert\NotBlank(
        message: "Поле не может быть пустым."
    )]
    #[Assert\Type(
        type: 'numeric',
        message: "Площадь должна быть числом (например, 50 или 75.5)."
    )]
    #[Assert\Range(
        notInRangeMessage: "Площадь должна быть от {{ min }} до {{ max }} м².",
        min: 1,
        max: 3000,
    )]
    public float $areaSquare;
    #[SerializedName('phone')]
    #[Assert\NotBlank(
        message: "Поле не может быть пустым."
    )]
    #[Assert\Regex(
        pattern: '/^[\+\d\-\(\)\s]{7,20}$/',
        message: "Номер телефона '{{ value }}' имеет недопустимый формат."
    )]
    public string $phone;
}
