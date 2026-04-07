<?php

namespace App\Enums;

enum ReceiptProcessStatusEnum: string
{
    case processing = 'processing';
    case success = 'success';
    case failed = 'failed';
}