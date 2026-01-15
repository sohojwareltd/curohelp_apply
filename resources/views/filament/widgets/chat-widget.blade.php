<x-filament-widgets::widget>
    <div style="background: white; border-radius: 12px; overflow: hidden; display: flex; height: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
        
        <!-- Left Sidebar -->
        <div style="width: 320px; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
            
            <!-- Header -->
            <div style="padding: 20px; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                <h3 style="margin: 0; font-size: 18px; font-weight: bold;">💬 Messages</h3>
            </div>
            
            <!-- User List -->
            <div style="flex: 1; overflow-y: auto; padding: 0;">
                <div>
                    @foreach($this->getAvailableUsers() as $user)
                        <button
                            wire:click="selectRecipient({{ $user->id }})"
                            style="width: 100%; padding: 12px 16px; text-align: left; border: none; cursor: pointer; 
                                   background: {{ $this->selectedRecipient === $user->id ? '#dbeafe' : 'transparent' }};
                                   border-left: 4px solid {{ $this->selectedRecipient === $user->id ? '#3b82f6' : 'transparent' }};
                                   transition: all 0.2s ease;
                                   border-bottom: 1px solid #f0f0f0;
                                   hover: background #f5f5f5;"
                            onmouseover="this.style.background='#f0f0f0'"
                            onmouseout="this.style.background='{{ $this->selectedRecipient === $user->id ? '#dbeafe' : 'transparent' }}'"
                        >
                            <div style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $user->name }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->email }}</div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Chat Area -->
        <div style="flex: 1; display: flex; flex-direction: column; background: white;">
            @if($this->selectedRecipient)
                @php
                    $recipient = \App\Models\User::find($this->selectedRecipient);
                @endphp
                
                <!-- Chat Header -->
                <div style="padding: 20px; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                    <h4 style="margin: 0; font-size: 16px; font-weight: 600; color: #1f2937;">{{ $recipient->name }}</h4>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #6b7280;">{{ $recipient->email }}</p>
                </div>

                <!-- Messages Container -->
                <div style="flex: 1; overflow-y: auto; padding: 20px; background: #f9fafb; display: flex; flex-direction: column; gap: 12px;" id="messages-container">
                    @foreach($this->getMessages() as $message)
                        @if($message->sender_id === auth()->id())
                            <!-- Sent Message -->
                            <div style="display: flex; justify-content: flex-end;">
                                <div style="max-width: 70%; display: flex; flex-direction: column; align-items: flex-end;">
                                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 10px 16px; 
                                               border-radius: 18px; word-wrap: break-word; font-size: 14px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);">
                                        {{ $message->body }}
                                    </div>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 6px;">{{ $message->created_at->format('h:i A') }}</div>
                                </div>
                            </div>
                        @else
                            <!-- Received Message -->
                            <div style="display: flex; justify-content: flex-start;">
                                <div style="max-width: 70%; display: flex; flex-direction: column; align-items: flex-start;">
                                    <div style="background: #e5e7eb; color: #1f2937; padding: 10px 16px; 
                                               border-radius: 18px; word-wrap: break-word; font-size: 14px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                                        {{ $message->body }}
                                    </div>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 6px;">{{ $message->created_at->format('h:i A') }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Input Area -->
                <div style="padding: 16px 20px; border-top: 1px solid #e5e7eb; background: white;">
                    <form wire:submit="sendMessage" style="display: flex; gap: 12px; align-items: flex-end;">
                        <textarea
                            wire:model="messageBody"
                            placeholder="Type your message..."
                            style="flex: 1; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 20px; 
                                   font-size: 14px; font-family: inherit; resize: none; max-height: 100px;
                                   background: #f3f4f6; color: #1f2937; outline: none;
                                   transition: all 0.2s ease;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                            onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                            rows="1"
                        ></textarea>
                        
                        <button
                            type="submit"
                            style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; 
                                   padding: 10px 14px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; 
                                   justify-content: center; width: 40px; height: 40px; flex-shrink: 0;
                                   transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);"
                            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'"
                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'"
                        >
                            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <!-- Empty State -->
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                    <div style="text-align: center;">
                        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p style="margin: 0; font-size: 16px; font-weight: 500;">Select a user to start messaging</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
        
        Livewire.hook('message.processed', (message, component) => {
            const container = document.getElementById('messages-container');
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 100);
            }
        });
    </script>
</x-filament-widgets::widget>



