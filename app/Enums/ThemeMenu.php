<?php


namespace App\Enums;

enum ThemeMenu: string
{
    case home = 'home';
    case service = 'service';
    case contracts = 'contracts';
    case turnOverRent = 'turnOverRent';
    case maintenance = 'maintenance';
    case community = 'community';

    public function label(): string
    {
        return match ($this) {
            self::home => 'Home',
            self::service => 'Service',
            self::contracts => 'Contracts',
            self::turnOverRent => 'Turn Over Rent',
            self::maintenance => 'Maintenance',
            self::community => 'Community',
        };
    }
}
