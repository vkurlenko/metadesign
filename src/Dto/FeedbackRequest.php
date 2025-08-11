<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class FeedbackRequest
{
    const SERVICE_TYPE_DESIGN_PROJECT = 'design_project';
    const SERVICE_TYPE_REPAIR = 'repair';
    const SERVICE_TYPE_WORKS = 'works';
    const SERVICE_TYPE_EQUIPMENT = 'equipment';
    const SERVICE_TYPE_SUPERVISION = 'supervision';
    const SERVICE_TYPE_CONSULTING = 'consulting';
    #[SerializedName('user_name')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $userName;
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^[\+\d\-\(\)\s]{7,20}$/',
        message: "Номер телефона '{{ value }}' имеет недопустимый формат."
    )]
    public string $phone;
    #[SerializedName('contact_method')]
    #[Assert\All([
        new Assert\Choice(['phone_call', 'telegram', 'whatsapp']),
        new Assert\Type('string')
    ])]
    #[Assert\Count(min: 1, max: 3)]
    public array $contactMethods;
    #[SerializedName('service_type')]
    #[Assert\Choice([
        self::SERVICE_TYPE_DESIGN_PROJECT,
        self::SERVICE_TYPE_REPAIR,
        self::SERVICE_TYPE_WORKS,
        self::SERVICE_TYPE_EQUIPMENT,
        self::SERVICE_TYPE_SUPERVISION,
        self::SERVICE_TYPE_CONSULTING,
    ])]
    public string $serviceType;
}