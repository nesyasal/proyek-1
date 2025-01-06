<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .chat-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .chat-header {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .chat-body {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background-color: #f1f1f1;
        }
        .chat-footer {
            padding: 10px;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }
        .message {
            padding: 10px 15px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .message-user {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }
        .message-other {
            background-color: #e9ecef;
            align-self: flex-start;
        }
    </style>
</head>
<body>
    <div id="app" class="container mt-5">
        <div class="chat-container mx-auto">
            <div class="chat-header">
                Chat Room
            </div>
            <div class="chat-body d-flex flex-column" id="message">
                <!-- Messages will be appended here dynamically -->
            </div>
            <div class="chat-footer">
                <form id="form" class="d-flex">
                    <input type="text" name="text" class="form-control me-2" placeholder="Type a message...">
                    <button class="btn btn-primary">Send</button>
                </form>
                <?php
                $user = Auth::user();
                ?>
                @auth
                    @if($user->tipe_pengguna === 'Pasien')
                        <a href="{{ route('review.create', ['konsultasiId' => $konsultasi->konsultasi_id]) }}" class="btn btn-danger">
                            Akhiri Chat dan Berikan Review
                        </a>
                    @else
                        <p class="text-muted">Hanya pasien yang dapat memberikan review.</p>
                    @endif
                @endauth
            </div>
            
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pusher Library -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Fetch chat messages
        const getChat = async () => {
            const response = await fetch('/chat/get/{{ $room->id }}');
            const data = await response.json();

            let chatsHTML = '';
            data.map(r => {
                chatsHTML += `
                    <div class="d-flex ${r.user_id == "{{ Auth::user()->id }}" ? 'justify-content-end' : 'justify-content-start'}">
                        <div class="message ${r.user_id == "{{ Auth::user()->id }}" ? 'message-user' : 'message-other'}">
                            <span class="d-block fw-bold">${r.user_name}</span>
                            <span>${r.message}</span>
                        </div>
                    </div>`;
            });
            document.getElementById('message').innerHTML = chatsHTML;
        };

        // Initialize chat on page load
        window.addEventListener('load', async () => {
            await getChat();

            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
            });

            const channel = pusher.subscribe('chat-channel');

            channel.bind('chat-send', async () => {
                await getChat();
            });

            document.getElementById('form').addEventListener('submit', async (ev) => {
                ev.preventDefault();

                const message = document.querySelector('input[name="text"]');
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message.value,
                        room: '{{ $room->id }}'
                    })
                });

                if (response.ok) {
                    await getChat();
                    message.value = '';
                }
            });
        });
    </script>
</body>
</html>
