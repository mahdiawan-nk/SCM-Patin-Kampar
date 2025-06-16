<?php

namespace App\Providers\Filament;

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
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\ManageBudidaya\MonitoringKolam;
use App\Filament\Pages\ManageBudidaya\PenebaranBenih;
use App\Filament\Pages\ManageBudidaya\PemberianPakan;
use App\Filament\Pages\ManageBudidaya\PertumbuhanBenih;
use App\Filament\Pages\ManageBudidaya\MonitoringHealthBenih;
use App\Filament\Pages\ManageBudidaya\SchedulBudidaya;
use App\Filament\Pages\ManageBudidaya\HarvestEstimation;
use App\Filament\Pages\ManageBudidaya\HarvestRealization;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;

class PanelsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        return $panel
            ->default()
            ->id('panels')
            ->path('panels')
            ->login()
            ->breadcrumbs(false)
            ->viteTheme('resources/css/filament/panels/theme.css')
            ->favicon(asset('static-file/favicon.ico'))
            ->maxContentWidth('full')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Monitoring Kolam')
                    ->url(fn(): string => MonitoringKolam::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.monitoring-kolam'))
                    ->icon('heroicon-o-computer-desktop')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(3),
                NavigationItem::make('Siklus Budidaya')
                    ->url(fn(): string => PenebaranBenih::getUrl())
                    ->isActiveWhen(fn(): bool => in_array(request()->route()->getName(), [
                        'filament.panels.pages.penebaran-benih',
                        'filament.panels.pages.pemberian-pakan',
                        'filament.panels.pages.pertumbuhan-benih',
                        'filament.panels.pages.monitoring-health-benih',
                    ]))
                    ->icon('heroicon-o-computer-desktop')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(4),
                NavigationItem::make('Penebaran Benih')
                    ->url(fn(): string => PenebaranBenih::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.penebaran-benih'))
                    ->icon('heroicon-o-computer-desktop')
                    ->parentItem('Siklus Budidaya')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(1),
                NavigationItem::make('Pemberian Pakan')
                    ->url(fn(): string => PemberianPakan::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.pemberian-pakan'))
                    ->icon('heroicon-o-computer-desktop')
                    ->parentItem('Siklus Budidaya')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(2),
                NavigationItem::make('Sampling Pertumbuhan Benih')
                    ->url(fn(): string => PertumbuhanBenih::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.pertumbuhan-benih'))
                    ->icon('heroicon-o-computer-desktop')
                    ->parentItem('Siklus Budidaya')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(3),
                NavigationItem::make('Monitoring Kesehatan Benih')
                    ->url(fn(): string => MonitoringHealthBenih::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.monitoring-health-benih'))
                    ->icon('heroicon-o-computer-desktop')
                    ->parentItem('Siklus Budidaya')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(4),
                NavigationItem::make('Jadwal Budidaya')
                    ->url(fn(): string => SchedulBudidaya::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.schedul-budidaya'))
                    ->icon('heroicon-o-calendar-days')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(5),
                NavigationItem::make('Perkiraan Panen')
                    ->url(fn(): string => HarvestEstimation::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.harvest-estimation'))
                    ->icon('heroicon-o-calendar-days')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(5),
                NavigationItem::make('Aktual Panen')
                    ->url(fn(): string => HarvestRealization::getUrl())
                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.panels.pages.harvest-realization'))
                    ->icon('heroicon-o-archive-box')
                    ->group('Management Budidaya (Hulu)')
                    ->sort(5),

                // NavigationItem::make('Rencana Produksi')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-calendar-days')
                //     ->group('Manajemen Produksi')
                //     ->sort(2),
                // NavigationItem::make('Jenis Produk Olahan')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-tag')
                //     ->group('Manajemen Produksi')
                //     ->sort(2),
                // NavigationItem::make('Data Produksi Harian')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-building-storefront')
                //     ->group('Manajemen Produksi')
                //     ->sort(2),
                // NavigationItem::make('Pengemasan & Labeling')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-archive-box')
                //     ->group('Manajemen Produksi')
                //     ->sort(2),
                // NavigationItem::make('Quality Control')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-magnifying-glass-circle')
                //     ->group('Manajemen Produksi')
                //     ->sort(2),

                // NavigationItem::make('Daftar Pesanan')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-document-text')
                //     ->group('Manajemen Distribusi')
                //     ->sort(3),
                // NavigationItem::make('Penjadwalan Pengiriman')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-calendar-days')
                //     ->group('Manajemen Distribusi')
                //     ->sort(3),
                // NavigationItem::make('Tracking Logistik')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-truck')
                //     ->group('Manajemen Distribusi')
                //     ->sort(3),
                // NavigationItem::make('Data Pelanggan')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-user-group')
                //     ->group('Manajemen Pemasaran')
                //     ->sort(4),
                // NavigationItem::make('Feedback Pelanggan')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-chat-bubble-left-right')
                //     ->group('Manajemen Pemasaran')
                //     ->sort(4),
                // NavigationItem::make('Stok Ikan Segar/Olahan')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-chat-bubble-left-right')
                //     ->group('Managemen Stok')
                //     ->sort(5),
                // NavigationItem::make('Bahan Pendukung')
                //     ->url('https://filament.pirsch.io')
                //     ->icon('heroicon-o-chat-bubble-left-right')
                //     ->group('Managemen Stok')
                //     ->sort(5),
            ])
            ->plugins([
                AuthUIEnhancerPlugin::make()
                    ->showEmptyPanelOnMobile(false)
                    ->formPanelPosition('right')
                    ->formPanelWidth('40%')
                    ->formPanelBackgroundColor(Color::Slate)
                    ->emptyPanelWidth('60%')
                    ->emptyPanelBackgroundImageUrl(asset('static-file/auth-bg-1.jpg'))
                    ->emptyPanelBackgroundImageSize('cover')
                    ->emptyPanelBackgroundColor(Color::Slate)
            ])
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
