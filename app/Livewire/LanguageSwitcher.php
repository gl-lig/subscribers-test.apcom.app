<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
    }

    public function switchLocale(string $locale): void
    {
        if (in_array($locale, config('app.available_locales', ['fr']))) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
            $this->currentLocale = $locale;
            $this->redirect(request()->header('Referer', '/'));
        }
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
