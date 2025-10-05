{{-- resources/views/events/show.blade.php --}}
<x-site-layout :title="$event->title">
  <div class="max-w-4xl mx-auto px-6 py-10">
    <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:underline">← 一覧へ戻る</a>

    <h1 class="mt-2 text-2xl md:text-3xl font-extrabold">{{ $event->title }}</h1>

    <div class="mt-2 text-sm text-gray-500">
      @if(!empty($event->date_start)) {{ \Illuminate\Support\Carbon::parse($event->date_start)->format('Y/m/d') }} @endif
      @if(!empty($event->ward)) ・{{ $event->ward }} @endif
    </div>

    {{-- タグ --}}
    @if($event->tags->isNotEmpty())
      <div class="mt-3 flex flex-wrap gap-1">
        @foreach($event->tags as $t)
          <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">#{{ $t->name }}</span>
        @endforeach
      </div>
    @endif

    {{-- サムネ --}}
    @if(!empty($event->thumb_url))
      <img src="{{ $event->thumb_url }}" alt="{{ $event->title }}" class="mt-6 w-full rounded-lg border">
    @endif

    {{-- 概要/説明 --}}
    @if(!empty($event->excerpt))
      <p class="mt-6 text-gray-700 whitespace-pre-wrap">{{ $event->excerpt }}</p>
    @endif

    {{-- 外部申し込みへ（追跡付き go ルートを使う想定） --}}
    <div class="mt-6">
      @if(!empty($event->url))
        <a href="{{ route('events.go', $event) }}"
           class="inline-flex items-center rounded-full bg-orange-500 text-white px-5 py-2 text-sm font-semibold hover:bg-orange-600">
          申し込みページへ
        </a>
      @endif
    </div>

    {{-- ▼▼ ここが「運営レビュー」表示ブロック ▼▼ --}}
    {{-- 運営レビュー（最新1件） --}}
    @php $rv = $event->reviews->first(); @endphp
@if($rv)
  <section class="mt-10 rounded-xl border bg-white p-5">
    <h2 class="mb-3 text-lg font-bold">運営レビュー</h2>

    @if(!empty($rv->rating))
      <div class="mb-2 text-amber-500 text-sm">
        {{ str_repeat('★', (int)$rv->rating) }}{{ str_repeat('☆', max(0,5-(int)$rv->rating)) }}
      </div>
    @endif

    @if(!empty($rv->title)) <div class="font-semibold">{{ $rv->title }}</div> @endif
    @if(!empty($rv->body))  <p class="mt-2 whitespace-pre-wrap text-gray-700">{{ $rv->body }}</p> @endif

    <div class="mt-3 text-xs text-gray-500">
      {{ $rv->author ?? '運営より' }}
      @if(!empty($rv->published_at))
        ・{{ \Illuminate\Support\Carbon::parse($rv->published_at)->format('Y/m/d') }}
      @endif
    </div>
  </section>
@endif


    {{-- ▲▲ ここまで「運営レビュー」 ▲▲ --}}
  </div>
</x-site-layout>
