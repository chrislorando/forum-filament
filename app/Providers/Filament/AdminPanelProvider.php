<?php

namespace App\Providers\Filament;

use App\Filament\AvatarProvider\BoringAvatarsProvider;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Resources\Boards\BoardResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Topics\RelationManagers\PostsRelationManager;
use Filament\Auth\Pages\Login;
use Filament\Enums\UserMenuPosition;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use function Filament\Support\original_request;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->registration()
            ->profile(EditProfile::class, isSimple: false)
            ->colors([
                'primary' => Color::Slate,
            ])
            ->spa()
            ->topNavigation()
            ->userMenu(position: UserMenuPosition::Topbar)
            ->maxContentWidth(Width::Full)
            // ->simplePageMaxContentWidth(Width::Small)
            ->databaseNotifications()
            // ->defaultAvatarProvider(BoringAvatarsProvider::class)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
                // Authenticate::class,
            ])
            ->databaseTransactions()
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                     NavigationItem::make('Home')
                        ->icon('heroicon-o-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.boards.index'))
                        ->url(fn (): string => BoardResource::getUrl()),
                    // NavigationItem::make('Home')
                    //     ->icon('heroicon-o-home')
                    //     ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.pages.dashboard'))
                    //     ->url(fn (): string => \App\Filament\Pages\Dashboard::getUrl()),
                    // NavigationItem::make('Login')
                    //     ->icon('heroicon-o-arrow-left-on-rectangle')
                    //     ->url(fn (): string => Filament::getLoginUrl())
                    //     ->visible(fn (): bool => auth()->guest()),
                    // NavigationItem::make('Register')
                    //         ->icon('heroicon-o-user-plus')
                    //         ->url(fn (): string => Filament::getRegistrationUrl())
                    //         ->visible(fn (): bool => auth()->guest()),
                ]);
            })
      
            ->renderHook(
                PanelsRenderHook::TOPBAR_BEFORE, // Argumen 1: Nama Hook
                fn () => view('filament.hooks.greeting') // Argumen 2: Kontennya
            );
    }
}
