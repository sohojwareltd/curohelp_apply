<x-filament-widgets::widget>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 32px; color: white; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="flex: 1; min-width: 300px;">
                <h2 style="margin: 0 0 12px 0; font-size: 28px; font-weight: bold;">
                    👋 Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p style="margin: 0 0 20px 0; font-size: 16px; opacity: 0.95; line-height: 1.6;">
                    @if(auth()->user()->role?->slug === 'worker')
                        Manage your invoices, track submissions, and stay connected with clients through our messaging system.
                    @else
                        Connect with workers, review invoices, and manage your communications all in one place.
                    @endif
                </p>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    @if(auth()->user()->role?->slug === 'worker')
                        <a href="/worker/worker-invoices" style="background: white; color: #667eea; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            📄 Upload Invoice
                        </a>
                        <a href="/worker/worker-messages" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.3);">
                            💬 Messages
                        </a>
                    @else
                        <a href="/client/client-messages" style="background: white; color: #667eea; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            💬 View Messages
                        </a>
                    @endif
                </div>
            </div>
            <div style="text-align: center;">
                <svg style="width: 120px; height: 120px; opacity: 0.9;" fill="white" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 10c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0 6c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
