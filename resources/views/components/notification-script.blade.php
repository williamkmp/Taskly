<script>
    let message = [];
    let temp = "";
    let delimiter = '\n';

    function parseMessage(message, delimiter) {
        if (!message || message === '') return;
        const [body, title] = message.split("\n").map(m => m.trim()).reverse()
        if (body == null || body === "") return;
        ToastView.notif(title || "Notification", body);
    }

    @if (session('notif'))
        @foreach (session('notif') as $msg)
            parseMessage(`{{ $msg }}`, delimiter);
        @endforeach
    @endif

    @isset($notif)
        @foreach ($notif as $msg)
            parseMessage(`{{ $msg }}`, delimiter);
        @endforeach
    @endisset
</script>
