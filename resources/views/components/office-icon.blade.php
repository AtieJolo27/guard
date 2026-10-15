@props(['name' => 'folder'])
<svg {{ $attributes->merge(['class' => 'office-icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($name)
@case('home')<path d="m3 10 9-7 9 7M5 9v12h5v-7h4v7h5V9"/>@break
@case('clock')<circle cx="12" cy="12" r="9"/><path d="M12 7v6l4 2"/>@break
@case('check')<circle cx="12" cy="12" r="9"/><path d="m7 12 3 3 7-7"/>@break
@case('mail')<rect x="3" y="5" width="18" height="14" rx="1"/><path d="m3 6 9 7 9-7"/>@break
@case('alert')<path d="m12 3 10 18H2L12 3ZM12 9v5m0 3h.01"/>@break
@case('calendar')<rect x="4" y="5" width="16" height="16" rx="1"/><path d="M8 3v4m8-4v4M4 10h16m-11 4h1m4 0h1"/>@break
@case('user')<circle cx="12" cy="12" r="10"/><circle cx="12" cy="9" r="3"/><path d="M5 19c1-6 13-6 14 0"/>@break
@case('plus')<circle cx="12" cy="12" r="9"/><path d="M12 7v10M7 12h10"/>@break
@case('arrow')<path d="M4 12h16m-6-6 6 6-6 6"/>@break
@case('menu')<path d="M3 6h18M3 12h18M3 18h18"/>@break
@case('users')<circle cx="9" cy="7" r="3" fill="currentColor" stroke="none"/><path d="M2 20v-3c0-6 14-6 14 0v3Z" fill="currentColor" stroke="none"/><circle cx="18" cy="8" r="2.5" fill="currentColor" stroke="none"/><path d="M18 14c4 0 5 3 5 6h-5" fill="currentColor" stroke="none"/>@break
@case('list')<path d="M9 5h12M9 12h12M9 19h12"/><circle cx="3" cy="5" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="3" cy="19" r="1" fill="currentColor"/>@break
@case('chart')<rect x="2" y="12" width="5" height="10" rx="1" fill="currentColor" stroke="none"/><rect x="10" y="7" width="5" height="15" rx="1" fill="currentColor" stroke="none"/><rect x="18" y="2" width="5" height="20" rx="1" fill="currentColor" stroke="none"/>@break
@case('history')<path d="M3 11a9 9 0 1 1 1 6M3 4v7h7M12 7v6l4 2"/>@break
@case('settings')<path d="m10 2 4 0 1 4 3 1 3-1 2 4-3 3v2l2 3-3 3-3-2-3 1-2 3-4-1-1-4-3-1-3 1-2-4 3-3V9L3 6l3-3 3 2Z" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="4" fill="#25282B" stroke="none"/>@break
@default<path d="M3 7V5h6l3 3h9l-2 12H3V7Z"/>
@endswitch</svg>
