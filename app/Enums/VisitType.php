<?php

namespace App\Enums;

enum VisitType: string
{
    case NEW = 'new';
    case FOLLOW_UP = 'follow_up';
    case REVIEW = 'review';
    case EMERGENCY = 'emergency';
}