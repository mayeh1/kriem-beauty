<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #241A2E; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #B02A66;">New Contact Message</h2>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr><td style="padding: 8px 0; font-weight: bold; width: 80px;">From:</td><td>{{ $data['name'] }}</td></tr>
        <tr><td style="padding: 8px 0; font-weight: bold;">Email:</td><td><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></td></tr>
        <tr><td style="padding: 8px 0; font-weight: bold;">Subject:</td><td>{{ $data['subject'] }}</td></tr>
    </table>
    <div style="background: #FBF6FA; border: 1px solid #ECE0EC; border-radius: 8px; padding: 16px;">
        {!! nl2br(e($data['message'])) !!}
    </div>
    <p style="color: #999; font-size: 12px; margin-top: 20px;">Sent from the KRIEM Beauty contact form.</p>
</body>
</html>
