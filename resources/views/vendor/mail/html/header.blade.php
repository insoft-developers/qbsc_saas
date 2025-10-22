@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/satpam-trans.png') }}" class="logo" alt="QBSC" width="150">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
