@props(['url'])
<tr>
<td class="header" align="center">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: auto;">
<tr>
<td style="padding-right: 10px; vertical-align: middle;">
<svg width="35" height="35" viewBox="0 0 50 50" fill="none" stroke="#0077bb" stroke-width="2" stroke-linecap="square" xmlns="http://www.w3.org/2000/svg">
<path d="M14 12 L14 28 L26 28" />
<path d="M20 18 L20 34 L32 34" />
<path d="M26 24 L26 40 L38 40" />
</svg>
</td>
<td style="vertical-align: middle; color: #0077bb; font-size: 24px; font-weight: bold; font-family: sans-serif;">
{!! $slot !!}
</td>
</tr>
</table>
</a>
</td>
</tr>
