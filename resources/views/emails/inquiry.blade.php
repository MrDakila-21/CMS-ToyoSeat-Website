<div style="font-family: Arial, Helvetica, sans-serif; color: #333;">
    <h2>New Inquiry Received</h2>

    <p><strong>Name:</strong> {{ $data['name'] ?? '—' }}</p>
    <p><strong>Email:</strong> {{ $data['email'] ?? '—' }}</p>
    <p><strong>Subject:</strong> {{ $data['subject'] ?? '—' }}</p>
    @if (!empty($data['attachment_original_name']))
        <p><strong>Attachment:</strong> {{ $data['attachment_original_name'] }}</p>
    @endif

    <h3>Message</h3>
    <p>{{ nl2br(e($data['message'] ?? '')) }}</p>

    <hr>
    <p style="font-size:12px;color:#666;">This message was sent from the website inquiry form.</p>
</div>
