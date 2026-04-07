<?php

namespace App\Enums;

enum ApiProgressLevelEnum: string
{
    case fetching_receipt_details = 'fetching_receipt_details';
    case fetching_receipt_details_completed = 'fetching_receipt_details_completed';
    case receipt_distribution = 'receipt_distribution';
    case receipt_distribution_completed = 'receipt_distribution_completed';
    case api_completed = 'api_completed';
}