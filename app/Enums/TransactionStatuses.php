<?php

namespace App\Enums;

enum TransactionStatuses: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
