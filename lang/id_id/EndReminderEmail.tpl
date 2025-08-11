Reservasi Anda akan segera berakhir.<br/>
Rincian Reservasi:
	<br/>
	<br/>
	Mulai: {formatdate date=$StartDate key=reservation_email}<br/>
	Akhir: {formatdate date=$EndDate key=reservation_email}<br/>
	Resource: {$ResourceName}<br/>
	Judul: {$Title}<br/>
	Keterangan: {$Description|nl2br}<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Lihat reservasi ini</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Tambah ke Kalender</a> |
<a href="{$ScriptUrl}">Masuk ke LibreBooking</a>

