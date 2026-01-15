<x-filament-panels::page>
    <form wire:submit="save">
        {{-- Personal Information Section --}}
        <div class="mb-8">
            <x-filament::section>
                <x-slot name="heading">Personal Information</x-slot>
            <x-slot name="description">Your basic profile information</x-slot>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Full Name</x-slot>
                        <x-filament::input
                            type="text"
                            wire:model="name"
                            placeholder="Your full name"
                            required
                        />
                        @error('name')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </x-filament::fieldset>
                </div>

                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Email Address</x-slot>
                        <x-filament::input
                            type="email"
                            wire:model="email"
                            placeholder="your@email.com"
                            required
                        />
                        @error('email')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </x-filament::fieldset>
                </div>
            </div>
        </x-filament::section>
        </div>

        {{-- Contact Information Section --}}
        <div class="mb-8">
            <x-filament::section>
                <x-slot name="heading">Contact Information</x-slot>
            <x-slot name="description">How we can reach you</x-slot>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Phone Number</x-slot>
                        <x-filament::input
                            type="text"
                            wire:model="phone"
                            placeholder="+880 1xxx xxx xxx"
                        />
                        @error('phone')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </x-filament::fieldset>
                </div>

                <div class="md:col-span-2">
                    <x-filament::fieldset>
                        <x-slot name="label">Address</x-slot>
                        <x-filament::input
                            type="text"
                            wire:model="address"
                            placeholder="Your full address"
                        />
                        @error('address')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </x-filament::fieldset>
                </div>
            </div>
        </x-filament::section>
        </div>

        {{-- Security Section --}}
        <div class="mb-8">
            <x-filament::section>
                <x-slot name="heading">Security</x-slot>
            <x-slot name="description">Change your password (leave blank to keep current)</x-slot>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">New Password</x-slot>
                        <x-filament::input
                            type="password"
                            wire:model="password"
                            placeholder="Enter new password"
                        />
                        @error('password')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </x-filament::fieldset>
                </div>

                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Confirm Password</x-slot>
                        <x-filament::input
                            type="password"
                            wire:model="password_confirmation"
                            placeholder="Confirm new password"
                        />
                    </x-filament::fieldset>
                </div>
            </div>
        </x-filament::section>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end gap-3">
            <x-filament::button
                type="button"
                color="gray"
                outlined
                href="{{ route('filament.client.pages.dashboard') }}"
            >
                Cancel
            </x-filament::button>
            <x-filament::button type="submit" size="lg">
                Save All Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

