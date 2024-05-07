<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Chatroom</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css']);
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50" style="overflow: hidden;">
        <h3 class="text-center dark:text-white" id="sender">Welcome to Chatroom {{$sender['name']}}!</h3>
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            {{-- <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" /> --}}
                <div>
                    <div class="mt-4 text-center p-5">
                        @foreach($receiver as $key=>$value)
                            <button type="button" id="receiverList" class="user-list" value={{$value['id']}}>
                                {{$value['name']}}
                            </button>
                        @endforeach
                    </div>
                </div>
        </div>
        {{-- @include('chatbox') --}}
        <div id="chatApp">
            <div class="fixed transition-all duration-300 transform bottom-10 right-12 h-60 w-80" id="chatBox">
                <div class="mt-2">
                    <button id="toggleButton" type="button" class="w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm bg-red-600 text-white  hover:bg-red-400 dark:bg-indigo-600 dark:hover:bg-indigo-400">
                        Chat
                        <svg id="openIcon" class="ms-auto block size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"></path>
                        </svg>
                        <svg id="closeIcon" class="ms-auto block size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon" style="">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                        </svg>
                    </button>
                </div>
                <div class="w-full h-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 overflow-auto flex flex-col px-2 py-4">
                    <div id="messages" class="flex-1 p-4 text-sm flex flex-col gap-y-1">
                        <!-- Messages will be dynamically added here -->
                    </div>
                    <div>
                        <form id="msgForm" class="flex gap-2">
                            <input id="msgText" type="text" name="message" class="block w-full border px-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
        
    <script>
        $(document).ready(function(){
            let open = true;

            $('#toggleButton').on('click', function () {
                open = !open;
                $('#chatBox').toggleClass('-translate-y-0', open)
                    .toggleClass('translate-y-full', !open);
                $('#openIcon').toggle(!open);
                $('#closeIcon').toggle(open);
            });

            // Handle form submission
            $('#msgForm').on('submit', function (e) {
                e.preventDefault();
                let message = $('#msgText').val();
                var senderName = {!! json_encode($sender['name']) !!};
                $.post('/message-sent', {_token: '{{ csrf_token() }}',message: message, senderName: senderName}, (resp) => {
                    console.log(resp);
                    $('#msgText').val('');
                }).catch((err) => {
                    console.error(err);
                });
            });

            window.Echo.channel('chats')
                .listen('MessageSent', (e) => {
                    console.log(e);
                    $('#messages').append(`<div><span class="text-indigo-600">${e.name}:</span> <span class="dark:text-white">${e.text}</span></div>`);
                });

            // $('.user-list').on('click', function() {
            //     var userId = $(this).val();
            //     var parameter = {userId: userId };
            //     // alert(userId);
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         type: "POST",
            //         url: "{{ route('receiver') }}",
            //         data: parameter,
            //         success: function(response) {
            //             // Handle success response
            //             alert(response);
            //             console.log('Receiver selected successfully:', response);
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error response
            //             console.error('Error saving user data:', error);
            //         }
            //     });
            // });

            $('.user-list').on('click', function (e) {
                e.preventDefault();
                let userId = $(this).val();
                let receiverName = $(this).text();
                var parameter = {userId: userId };
                $.post('/receiver', {_token: '{{ csrf_token() }}',message: parameter}, (resp) => {
                    console.log('Receiver selected successfully:', resp);
                    $('.user-list').hide();
                    $(this).show();
                }).catch((err) => {
                    console.error(err);
                });
            });
        });
        
    </script>
</html>