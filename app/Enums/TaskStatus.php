<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = "pending";
    case INPROGRESS = "in-progress";
    case COMPLETED = "completed";
}
