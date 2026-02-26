<?php

namespace SmsAlert\Enum;

enum MessageStatusCodeEnum: string
{
    case SMS_DELIVERED = 'delivered';

    case SMS_TERMINAL_ACCEPTED = 'terminal_accepted';

    case SMS_FAILED = 'failed';

    case SMS_PENDING  = 'pending';

    case SMS_SCHEDULED = 'scheduled';

    case REROUTED = 'rerouted';
}
