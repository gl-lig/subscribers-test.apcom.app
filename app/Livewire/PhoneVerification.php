<?php

namespace App\Livewire;

use App\Contracts\BatIdServiceInterface;
use Livewire\Component;

class PhoneVerification extends Component
{
    public string $phone = '';
    public bool $isVerifying = false;
    public string $error = '';
    public bool $notFound = false;

    public function verifyPhone(): void
    {
        $this->error = '';
        $this->notFound = false;
        $this->isVerifying = true;

        if (empty($this->phone) || strlen($this->phone) < 8) {
            $this->error = __('Numéro de téléphone invalide');
            $this->isVerifying = false;
            return;
        }

        $batIdService = app(BatIdServiceInterface::class);
        $result = $batIdService->verifyPhone($this->phone);

        if ($result) {
            session()->put('bat_id', $result['bat_id']);
            session()->put('bat_phone', $result['phone']);
            $this->redirect(route('cart'));
        } else {
            $this->notFound = true;
        }

        $this->isVerifying = false;
    }

    public function render()
    {
        return view('livewire.phone-verification');
    }
}
