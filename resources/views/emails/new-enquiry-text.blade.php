New enquiry - {{ $siteName }}

Received: {{ $receivedAt }}
Source: {{ $source }}

Name: {{ $enquiry->name }}
Phone: {{ $enquiry->phone }}
Email: {{ $enquiry->email ?: 'Not provided' }}

Message:
{{ $enquiry->message }}

View all enquiries: {{ $adminUrl }}
