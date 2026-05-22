<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Customer Inquiry Notification - Toyo Seat Philippines</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:
'Inter',
-apple-system,
BlinkMacSystemFont,
'Segoe UI',
Roboto,
Arial,
sans-serif;

background:#f3f6f9;

padding:32px 18px;

color:#1e293b;

line-height:1.6;
}

.email-wrapper{

max-width:760px;

margin:auto;
}

.container{

background:#ffffff;

border:1px solid #d8e0e8;

border-radius:8px;

overflow:hidden;
}

/* Header */

.header{

padding:28px 34px;

border-bottom:3px solid #0f2b3d;

background:#ffffff;
}

.company{

font-size:12px;

font-weight:700;

letter-spacing:.12em;

color:#0f2b3d;

margin-bottom:8px;
}

.title{

font-size:28px;

font-weight:700;

color:#111827;

margin-bottom:12px;
}

.meta{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(180px,1fr));

gap:8px 24px;

font-size:13px;

color:#64748b;

margin-top:10px;
}

.meta-item{

white-space:nowrap;
}

/* Content */

.content{

padding:34px;
}

/* Operational Alert */

.alert{

background:#f8fafc;

border-left:4px solid #0f2b3d;

padding:18px 20px;

margin-bottom:34px;
}

.alert-label{

font-size:11px;

font-weight:700;

letter-spacing:.08em;

color:#0f2b3d;

margin-bottom:6px;
}

.alert-text{

font-size:14px;

color:#334155;
}

/* Section */

.section{

margin-bottom:34px;
}

.section-title{

font-size:12px;

font-weight:700;

letter-spacing:.1em;

text-transform:uppercase;

color:#64748b;

padding-bottom:10px;

border-bottom:1px solid #e2e8f0;

margin-bottom:6px;
}

/* Detail Rows */

.detail-row{

display:grid;

grid-template-columns:
170px 1fr;

padding:14px 0;

border-bottom:
1px solid #edf2f7;

gap:20px;
}

.detail-label{

font-size:12px;

font-weight:700;

letter-spacing:.05em;

text-transform:uppercase;

color:#64748b;
}

.detail-value{

font-size:14px;

color:#111827;

word-break:break-word;
}

.detail-value a{

color:#0f2b3d;

text-decoration:none;
}

.detail-value a:hover{

text-decoration:underline;
}

/* Message */

.message-box{

background:#fafafa;

border:1px solid #e2e8f0;

border-radius:6px;

padding:24px;

margin-top:14px;

font-size:14px;

line-height:1.9;

color:#334155;

white-space:pre-wrap;

word-break:break-word;

overflow-wrap:break-word;

text-align:left;
}

/* Attachment */

.attachment{

background:#f8fafc;

border:1px solid #e2e8f0;

padding:18px;

border-radius:6px;

margin-top:18px;
}

.attachment-name{

font-size:14px;

font-weight:700;

color:#0f172a;

margin-bottom:4px;
}

.attachment-meta{

font-size:12px;

color:#64748b;
}

/* Operational Footer */

.system-footer{

background:#f8fafc;

border-top:
1px solid #e2e8f0;

padding:28px 34px;
}

.system-title{

font-size:11px;

font-weight:700;

letter-spacing:.08em;

text-transform:uppercase;

color:#64748b;

margin-bottom:14px;
}

.system-grid{

display:grid;

grid-template-columns:
repeat(2,1fr);

gap:10px 30px;

font-size:13px;

color:#475569;

margin-bottom:22px;
}

.footer-company{

padding-top:18px;

border-top:
1px solid #e2e8f0;

font-size:12px;

line-height:1.7;

color:#64748b;
}

.footer-company strong{

display:block;

font-size:13px;

margin-bottom:6px;

color:#0f172a;
}

/* Mobile */

@media(max-width:640px){

body{

padding:16px 10px;
}

.header{

padding:24px;
}

.content{

padding:24px;
}

.system-footer{

padding:24px;
}

.title{

font-size:24px;
}

.meta{

flex-direction:column;

gap:6px;
}

.detail-row{

grid-template-columns:1fr;

gap:6px;
}

.system-grid{

grid-template-columns:1fr;
}

}

@media print{

body{

padding:0;

background:white;
}

.container{

border:none;
}

}

</style>

</head>

<body>

<div class="email-wrapper">

<div class="container">

<!-- HEADER -->

<div class="header">

<div class="company">

TOYO SEAT PHILIPPINES CORPORATION

</div>

<div class="title">

Customer Inquiry Notification

</div>

<div class="meta">

<div class="meta-item">
Received:
{{ now()->timezone('Asia/Manila')->format('F j, Y • g:i A') }}
</div>

<div class="meta-item">
Inquiry ID:
TSP-{{ now()->timezone('Asia/Manila')->format('Ymd') }}-{{ rand(100,999) }}
</div>

<div class="meta-item">
Source:
Website Form
</div>

</div>

</div>

<!-- BODY -->

<div class="content">

<div class="alert">

<div class="alert-label">

ACTION REQUIRED

</div>

<div class="alert-text">

Please review and respond within 24 hours to maintain customer service standards.

</div>

</div>

<!-- CUSTOMER DETAILS -->

<div class="section">

<div class="section-title">

Customer Details

</div>

<div class="detail-row">

<div class="detail-label">

Full Name

</div>

<div class="detail-value">

{{ $data['name'] ?? '—' }}

</div>

</div>

<div class="detail-row">

<div class="detail-label">

Email Address

</div>

<div class="detail-value">

@if(!empty($data['email']))
<a href="mailto:{{ $data['email'] }}">
{{ $data['email'] }}
</a>
@else
—
@endif

</div>

</div>

<div class="detail-row">

<div class="detail-label">

Contact Number

</div>

<div class="detail-value">

@if(!empty($data['contact_number']))
<a href="tel:{{ $data['contact_number'] }}">
{{ $data['contact_number'] }}
</a>
@else
—
@endif

</div>

</div>

@if(!empty($data['company_name']))

<div class="detail-row">

<div class="detail-label">

Company

</div>

<div class="detail-value">

{{ $data['company_name'] }}

</div>

</div>

@endif

<div class="detail-row">

<div class="detail-label">

Subject

</div>

<div class="detail-value">

{{ $data['subject'] ?? 'General Inquiry' }}

</div>

</div>

</div>

<!-- MESSAGE -->

<div class="section">

<div class="section-title">

Inquiry Message

</div>

<div class="message-box">

{!! nl2br(e($data['message'] ?? '')) !!}

</div>

</div>

@if(!empty($data['attachment_original_name']))

<div class="section">

<div class="section-title">

Attachment

</div>

<div class="attachment">

<div class="attachment-name">

{{ $data['attachment_original_name'] }}

</div>

<div class="attachment-meta">

@if(!empty($data['attachment_mime']))
{{ $data['attachment_mime'] }}
•
@endif

File attached for reference

</div>

</div>

</div>

@endif

</div>

<!-- FOOTER -->

<div class="system-footer">

<div class="system-title">

System Information

</div>

<div class="system-grid">

<div>

Generated:
{{ now()->format('M d, Y • g:i A') }}

</div>

<div>

Response Deadline:
{{ now()->addHours(24)->format('M d, g:i A') }}

</div>

<div>

Notification:
Automated

</div>

</div>

<div class="footer-company">

<strong>
TOYO SEAT PHILIPPINES CORPORATION
</strong>

Lot 7-A, Greenfield Automotive Park,
Don Jose, City of Santa Rosa,
Laguna 4026, Philippines

<br><br>

This notification was generated automatically by the website inquiry management system.
Please do not reply directly to this email.

</div>

</div>

</div>

</div>

</body>

</html>