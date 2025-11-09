<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SalesReportTable;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Sales Dashboard') // ✅ Tambahkan nama brand di sidebar atas
            ->colors([
                'primary' => Color::Amber,
            ])

            // Resource, Page, dan Widget autodiscovery
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')

            // Daftarkan Dashboard utama
            ->pages([
                Pages\Dashboard::class,
            ])

            // ✅ Tambahkan widget custom kamu di dashboard bawaan
            ->widgets([
                StatsOverview::class,
                SalesReportTable::class,
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])

            // Middleware Laravel dan Filament
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            // Proteksi autentikasi
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
