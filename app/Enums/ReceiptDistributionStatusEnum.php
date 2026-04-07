<?php

namespace App\Enums;

enum ReceiptDistributionStatusEnum: string
{
    case processing = 'processing';
    case success = 'success';
    case failed = 'failed';
}