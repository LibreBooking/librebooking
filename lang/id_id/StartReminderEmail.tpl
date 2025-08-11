Reservasi Anda segera dimulai.<br/>
Rincian Reservasi:
	<br/>
	<br/>
	Mulai: {formatdate date=$StartDate key=reservation_email}<br/>
	Akhir: {formatdate date=$EndDate key=reservation_email}<br/>
	Resource: {$ResourceName}<br/>
	Judul: {$Title}<br/>
	Penjelasan: {$Description|nl2br}<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Lihat reservasi ini</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Tambah ke kalender</a> |
<a href="{$ScriptUrl}">Masuk LibreBooking</a>

