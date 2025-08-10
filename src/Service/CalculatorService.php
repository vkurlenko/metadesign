<?php

namespace App\Service;

use App\Dto\CalculateRequest;

class CalculatorService
{
    const REALTY_TYPE_FLAT = 'flat';
    const REPAIR_TYPE_COMFORT = 'comfort';
    const REPAIR_TYPE_BUSINESS = 'business';
    const REPAIR_TYPE_PREMIUM = 'premium';
    const REALTY_STATUS_TYPE_SECONDARY = 'secondary';


    public function calculateCost(CalculateRequest $data): float
    {
        $areaSquare = $data->areaSquare;
        $realtyType = $data->realtyType;
        $realtyStatusType = $data->realtyStatusType;
        $repairType = $data->repairType;

        if ($realtyType == self::REALTY_TYPE_FLAT) {
            if ($realtyStatusType == self::REALTY_STATUS_TYPE_SECONDARY) {
                $result = 150 * 1000;
            }
            if ($areaSquare < 25) {
                $result = $this->calculateCostByRepairType(
                    multiplierComfort: 120,
                    multiplierBusiness: 170,
                    areaSquare: $areaSquare,
                    repairType: $repairType
                );
            } elseif ($areaSquare < 30) {
                $result = $this->calculateCostByRepairType(
                    multiplierComfort: 110,
                    multiplierBusiness: 160,
                    areaSquare: $areaSquare,
                    repairType: $repairType
                );
            } elseif ($areaSquare < 35) {
                $result = $this->calculateCostByRepairType(
                    multiplierComfort: 100,
                    multiplierBusiness: 150,
                    areaSquare: $areaSquare,
                    repairType: $repairType
                );
            } elseif ($areaSquare < 70) {
                $result = $this->calculateCostByRepairType(
                    multiplierComfort: 95,
                    multiplierBusiness: 130,
                    areaSquare: $areaSquare,
                    repairType: $repairType
                );
            } elseif ($areaSquare < 100) {
                $result = $this->calculateCostByRepairType(
                    multiplierComfort: 90,
                    multiplierBusiness: 120,
                    areaSquare: $areaSquare,
                    repairType: $repairType
                );
            } else {
                $result = $this->calculateCostByRepairType(
                    multiplierComfort: 85,
                    multiplierBusiness: 115,
                    areaSquare: $areaSquare,
                    repairType: $repairType
                );
            }
        } else {
            $result = $this->calculateCostByRepairType(
                multiplierComfort: 4,
                multiplierBusiness: 6,
                areaSquare: $areaSquare,
                repairType: $repairType
            );
        }
        return $result;
    }

    private function calculateCostByRepairType(
        int $multiplierComfort,
        int $multiplierBusiness,
        float $areaSquare,
        string $repairType
    ): float {
        if ($repairType == self::REPAIR_TYPE_COMFORT) {
            $cost = $multiplierComfort * 1000 * $areaSquare;
        } elseif ($repairType == self::REPAIR_TYPE_BUSINESS) {
            $cost = $multiplierBusiness * 1000 * $areaSquare;
        }
        return $cost;
    }
}