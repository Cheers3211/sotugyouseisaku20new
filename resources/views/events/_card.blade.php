
@php
  /** @var \App\Models\Event $event */
  $e = $event;

  // サムネ確定（絶対URL / storage相対 / プレースホルダ）
  $src = $e->thumb_url
      ? (filter_var($e->thumb_url, FILTER_VALIDATE_URL) ? $e->thumb_url : asset(ltrim($e->thumb_url, '/')))
      : asset('images/placeholder.jpg');

  // クリック先（外部URLがあれば go、なければ詳細）
  $href   = $e->url ? route('events.go', $e) : route('events.show', $e);
  $target = $e->url ? '_blank' : '_self';
  $host   = $e->url ? parse_url($e->url, PHP_URL_HOST) : null;
@endphp

<div class="group relative rounded-2xl border bg-white overflow-hidden ring-1 ring-gray-200/70
            hover:shadow-md hover:ring-gray-300 transition">

  {{-- 全体クリック（ボタンより下の層に配置） --}}
  <a href="{{ $href }}" target="{{ $target }}" rel="noopener noreferrer"
     class="absolute inset-0 z-10" aria-label="イベントへ"></a>

  {{-- 画像 --}}
  <div class="relative">
    <img src="{{ $src }}" alt="{{ $e->title }}"
         class="w-full aspect-[16/9] object-cover transition group-hover:scale-[1.02] duration-300 ease-out">
  </div>

  {{-- 本文 --}}
  <div class="relative z-20 p-4">
    @if($e->ward)
      <div class="mb-1">
        <span class="inline-flex items-center rounded-full bg-gray-800/70 text-white
                     px-2 py-0.5 text-[11px] leading-none">
          {{ $e->ward }}
        </span>
      </div>
    @endif

    <div class="flex items-center gap-2 text-xs text-gray-500">
      <svg class="w-4 h-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V4m8 3V4M4 9h16"/>
      </svg>
      <span>{{ optional($e->date_start)->format('Y/m/d') ?? '日時未定' }}</span>
    </div>

    <h3 class="mt-2 text-base md:text-lg font-semibold leading-snug line-clamp-2 group-hover:text-indigo-600">
      {{ $e->title }}
    </h3>

    @if(!empty($e->overview ?? $e->excerpt))
      <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $e->overview ?? $e->excerpt }}</p>
    @endif

    <div class="mt-3 flex flex-wrap gap-2">
      {{-- 詳細ページ（ボタンを前面に） --}}
      <a href="{{ route('events.show', $e) }}"
         class="relative z-30 inline-flex items-center gap-1 rounded-full px-3 py-1.5
                text-xs font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
        詳細を見る
      </a>

      @if($e->url)
        <a href="{{ route('events.go', $e) }}" target="_blank" rel="noopener nofollow ugc"
           class="relative z-30 inline-flex items-center gap-1 rounded-full px-3 py-1.5
                  text-xs font-medium bg-orange-50 text-orange-700 ring-1 ring-orange-200 hover:bg-orange-100">
          公式ページ @if($host)<span class="opacity-80">({{ $host }})</span>@endif
        </a>
      @endif
    </div>

    @if(!empty($e->organizer_name))
      <div class="mt-2 text-[11px] text-gray-500">主催：{{ $e->organizer_name }}</div>
    @endif
  </div>
</div>
