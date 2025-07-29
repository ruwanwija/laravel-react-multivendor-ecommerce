<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    case Draft = 'draft';
    case Published = 'published';
    
    public function label(): string
    {
        return match ($this) {
            self::Draft => __('Draft'),
            self::Published => __('Published'),
        };
    }

    public static function colors(): array
    {
        return [
            self::Draft->value => 'bg-gray-200 text-gray-800',
            self::Published->value => 'bg-green-200 text-green-800',
        ];
    }
}
