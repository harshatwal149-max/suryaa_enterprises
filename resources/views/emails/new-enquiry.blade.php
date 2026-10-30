@php
    $labelStyle = 'padding:12px 0;border-bottom:1px solid #e8ece9;color:#6b7c72;font-size:13px;width:90px;vertical-align:top;';
    $valueStyle = 'padding:12px 0;border-bottom:1px solid #e8ece9;color:#14241b;font-size:15px;font-weight:600;word-break:break-word;';
    $linkStyle = 'color:' . $primaryColor . ';text-decoration:none;';
    $buttonStyle = 'display:inline-block;margin:0 8px 8px 0;padding:12px 20px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>New enquiry from {{ $enquiry->name }}</title>
</head>
<body style="margin:0;padding:0;background:#eef2ef;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $enquiry->name }} · {{ $enquiry->phone }} · {{ $source }}</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2ef;">
        <tr>
            <td align="center" style="padding:28px 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="height:5px;line-height:5px;font-size:0;background:{{ $accentColor }};">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px 28px;">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" height="52" style="display:block;height:52px;width:auto;max-width:240px;border:0;">
                            @else
                                <span style="color:{{ $primaryColor }};font-size:20px;font-weight:700;">{{ $siteName }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:26px 28px;background:{{ $primaryColor }};">
                            <p style="margin:0 0 6px;color:#ffffff;opacity:.85;font-size:12px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;">New enquiry</p>
                            <h1 style="margin:0;color:#ffffff;font-size:24px;line-height:1.3;font-weight:700;word-break:break-word;">{{ $enquiry->name }}</h1>
                            <p style="margin:8px 0 0;color:#ffffff;opacity:.85;font-size:13px;">{{ $receivedAt }} · {{ $source }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 28px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="{{ $labelStyle }}">Phone</td>
                                    <td style="{{ $valueStyle }}"><a href="{{ $telUrl }}" style="{{ $linkStyle }}">{{ $enquiry->phone }}</a></td>
                                </tr>
                                <tr>
                                    <td style="{{ $labelStyle }}">Email</td>
                                    <td style="{{ $valueStyle }}">
                                        @if (filled($enquiry->email))
                                            <a href="mailto:{{ $enquiry->email }}" style="{{ $linkStyle }}">{{ $enquiry->email }}</a>
                                        @else
                                            <span style="color:#6b7c72;font-weight:400;">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 28px 0;">
                            <p style="margin:0 0 8px;color:#6b7c72;font-size:13px;">Message</p>
                            <div style="padding:16px 18px;background:#f6f8f6;border-left:4px solid {{ $accentColor }};border-radius:6px;color:#14241b;font-size:15px;line-height:1.6;word-break:break-word;">{!! nl2br(e($enquiry->message)) !!}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 28px 20px;">
                            <a href="{{ $telUrl }}" style="{{ $buttonStyle }}background:{{ $primaryColor }};color:#ffffff;">Call customer</a>
                            @if ($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" style="{{ $buttonStyle }}background:#25d366;color:#0b2e17;">WhatsApp</a>
                            @endif
                            <a href="{{ $adminUrl }}" style="{{ $buttonStyle }}background:#eef2ef;color:#14241b;">Open admin panel</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;background:#f6f8f6;color:#6b7c72;font-size:12px;line-height:1.6;text-align:center;">
                            @if (filled($enquiry->email))
                                Replying to this email goes straight to {{ $enquiry->name }}.<br>
                            @endif
                            {{ $siteName }} website enquiry notification
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
