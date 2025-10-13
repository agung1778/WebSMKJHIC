@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('assets/logo/amaliah.png') }}" class="logo" alt="Logo SMK Amaliah">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
