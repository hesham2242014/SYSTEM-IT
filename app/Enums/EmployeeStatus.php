<?php

namespace App\Enums;

enum EmployeeStatus: string
{
    case Active = 'active';
    case OnLeave = 'on_leave';
    case Suspended = 'suspended';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'على رأس العمل',
            self::OnLeave => 'في إجازة',
            self::Suspended => 'موقوف',
            self::Terminated => 'انتهت الخدمة',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Active => 'badge-success',
            self::OnLeave => 'badge-info',
            self::Suspended => 'badge-warning',
            self::Terminated => 'badge-muted',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->all();
    }
}
