{{-- Breeze 用の共通レイアウトを、サイト共通レイアウトに寄せる --}}
<x-site-layout :title="$title ?? (config('app.name').'｜マイページ')">
  <div class="max-w-5xl mx-auto px-6 py-8">
    {{ $slot }}
  </div>
  @if(session('ok'))
  <div class="mx-auto max-w-7xl px-4 py-3 my-3 rounded bg-green-50 text-green-800 ring-1 ring-green-200">
    {{ session('ok') }}
  </div>
@endif
@if(session('ng'))
  <div class="mx-auto max-w-7xl px-4 py-3 my-3 rounded bg-red-50 text-red-800 ring-1 ring-red-200">
    {{ session('ng') }}
  </div>
@endif

</x-site-layout>
