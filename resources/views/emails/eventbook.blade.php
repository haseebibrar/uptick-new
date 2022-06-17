Hey there {{ $data['first_name'] }},<br /><br />
Your lesson with {{ $data['teacher'] }} is booked successfully. Are you ready?? Because I definitely am!<br /><br />
<a href="{{ $data['zoom_link'] }}" target="_blank">Click here to join the lesson</a>.<br /><br />
Looking forward to seeing you soon!<br /><br />
Much love,<br />
{{ $data['teacher'] }}

<a href="{{ $data['icslink']}}" download="Cal.ics">Download ICS file</a>