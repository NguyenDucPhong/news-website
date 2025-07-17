<?php
namespace App\Enums;
enum PostStatus: int
{
    case Pending = 0;
    case Draft = 1;
    case Published = 2;
    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Pending => 'Pending',
            self::Published => 'Published',
        };
    }
}
