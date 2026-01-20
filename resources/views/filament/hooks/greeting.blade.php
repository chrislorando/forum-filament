<div class="text-xs px-3 text-gray-600 dark:text-gray-400 p-2">
    @auth
        <span class="text-gray-600 dark:text-gray-400">
            Welcome, <strong>{{ auth()->user()->name }}</strong>
        </span>
    @else
        <span class="text-gray-600 dark:text-gray-400">
            Welcome, Guest. Please 
            <a href="{{ Filament\Facades\Filament::getLoginUrl() }}" class="text-primary-600 hover:underline font-bold">Login</a> 
            or 
            <a href="{{ Filament\Facades\Filament::getRegistrationUrl() }}" class="text-primary-600 hover:underline font-bold">Register</a>.
        </span>
    @endauth
</div>