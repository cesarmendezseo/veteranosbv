<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('
Se enviará un enlace de restablecimiento si la cuenta existe.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('
Has olvidado tu contraseña')" :description="__('
Ingrese su correo electrónico para recibir un enlace de restablecimiento de contraseña')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Email ')" type="email" required autofocus
            placeholder="email@example.com" />

        <flux:button variant="primary" type="submit" class="w-full cursor-pointer">{{ __('Enviar enlace') }}
        </flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('
            O volver a') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Ingresar') }}</flux:link>
    </div>
</div>