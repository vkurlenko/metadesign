<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[ORM\Table(name: 'orders')]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    const REALTY_TYPE_FLAT = 'flat';
    const REALTY_TYPE_HOUSE = 'house';
    const REALTY_TYPE_COMMERCE = 'commerce';
    const REPAIR_TYPE_COMFORT = 'comfort';
    const REPAIR_TYPE_BUSINESS = 'business';
    const REPAIR_TYPE_PREMIUM = 'premium';
    const ROOM_TYPE_SECONDARY = 'secondary';
    const ROOM_TYPE_NEW = 'new';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)] // TODO если !nullable, то private int $id, а не ?int $id;
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id',nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(targetEntity: RealtyType::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RealtyType $property_type = null;

    #[ORM\Column]
    #[Assert\Type('float')]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(3000)]
    private ?float $square = null;

    #[ORM\ManyToOne(targetEntity: RoomType::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RoomType $room_type = null;

    #[ORM\ManyToOne(targetEntity: RepairType::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepairType $repair_class = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $cost = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $done_at = null;

    public function __construct($data, EntityManagerInterface $entityManager)
    {
        /* TODO В Entity никакой логики быть не должно, все вычисления вынести в контроллер или сервис */

//        $roomType = $entityManager->getRepository(RoomType::class)->findOneBy(['name' => $data['room-type']])
//            ? $entityManager->getRepository(RoomType::class)->findOneBy(['name' => $data['room-type']])
//            : new RoomType();
//
//        if (!$roomType->getName()){
//            $roomType->setName($data['room-type']);
//        }
//
        $repairType = $entityManager->getRepository(RepairType::class)->findOneBy(['name' => $data['repair-type']])
            ? $entityManager->getRepository(RepairType::class)->findOneBy(['name' => $data['repair-type']])
            : new RepairType();

        if (!$repairType->getName()) {
            $repairType->setName($data['repair-type']);
        }

        $realtyType = $entityManager->getRepository(RealtyType::class)->findOneBy(['name' => $data['realty-type']])
            ? $entityManager->getRepository(RealtyType::class)->findOneBy(['name' => $data['realty-type']])
            : new RealtyType();

        if (!$realtyType->getName()) {
            $realtyType->setName($data['realty-type']);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['phone_number' => preg_replace('/[^0-9]/', '', $data['phone'])])
            ? $entityManager->getRepository(User::class)->findOneBy(['phone_number' => preg_replace('/[^0-9]/', '', $data['phone'])])
            : new User();

        if (!$user->getPhoneNumber()) {
            $user->setPhoneNumber(preg_replace('/[^0-9]/', '', $data['phone']));
        }

        $squareArea = $data['area-square'];

//        $this->setRoomType($roomType);
        $this->setRepairClass($repairType);
        $this->setPropertyType($realtyType);
        $this->setUserId($user);
        $this->setSquare($squareArea);
        $this->setCreatedAt(new \DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPropertyType(): ?RealtyType
    {
        return $this->property_type;
    }

    public function setPropertyType(?RealtyType $property_type): static
    {
        $this->property_type = $property_type;

        return $this;
    }

    public function getSquare(): ?float
    {
        return $this->square;
    }

    public function setSquare(float $square): static
    {
        $this->square = $square;

        return $this;
    }

    public function getRoomType(): ?RoomType
    {
        return $this->room_type;
    }

    public function setRoomType(?RoomType $room_type): static
    {
        $this->room_type = $room_type;

        return $this;
    }

    public function getRepairClass(): ?RepairType
    {
        return $this->repair_class;
    }

    public function setRepairClass(?RepairType $repair_class): static
    {
        $this->repair_class = $repair_class;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->done_at;
    }

    public function setDoneAt(?\DateTimeInterface $done_at): static
    {
        $this->done_at = $done_at;

        return $this;
    }

    public function validate(ValidatorInterface $validator): array
    {
        $errors = [];
        array_push($errors,
            $validator->validate($this->getRoomType()),
            $validator->validate($this->getRepairClass()),
            $validator->validate($this->getPropertyType()),
            $validator->validate($this->getUserId()),
            $validator->validate($this));
        return $errors;
    }

    /* TODO в контроллер или сервис */
    #[NoReturn]
    public function calculateCost(): void
    {
        $squareArea = $this->getSquare();
        $realtyType = $this->getPropertyType()->getName();
        $roomType = $this->getRoomType()->getName();

        if ($realtyType == self::REALTY_TYPE_FLAT) {
            if ($roomType == self::ROOM_TYPE_SECONDARY) {
                $this->setCost(150 * 1000);
            }
            if ($squareArea < 25) {
                $this->calculateCostByRepairType(120, 170);
            }elseif ($squareArea < 30) {
                $this->calculateCostByRepairType(110, 160);
            }elseif ($squareArea < 35) {
                $this->calculateCostByRepairType(100, 150);
            }elseif ($squareArea < 70) {
                $this->calculateCostByRepairType(95, 130);
            }elseif ($squareArea < 100) {
                $this->calculateCostByRepairType(90, 120);
            }else {
                $this->calculateCostByRepairType(85, 115);
            }
        }else{
            $this->calculateCostByRepairType(4, 6);
        }
    }

    /* TODO Вынести в контроллер или сервис */
    private function calculateCostByRepairType(int $multiplierComfort, int $multiplierBusiness) : void
    {
        if ($this->repair_class->getName() == self::REPAIR_TYPE_COMFORT) {
            $this->setCost($multiplierComfort * 1000 * $this->getSquare());
        } elseif ($this->repair_class->getName() == self::REPAIR_TYPE_BUSINESS) {
            $this->setCost($multiplierBusiness * 1000 * $this->getSquare());
        }

    }
}
