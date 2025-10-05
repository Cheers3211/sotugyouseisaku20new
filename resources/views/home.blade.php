<x-site-layout :title="'ホーム'">
  {{-- ===== Hero ===== --}}
@php
    $soon      = ($soon      ?? collect([]));
    $recent    = ($recent    ?? collect([]));
    $featured  = ($featured  ?? collect([]));
    $popular   = ($popular   ?? collect([]));
    $picks     = ($picks     ?? collect([]));
    $recommend = ($recommend ?? collect([]));
@endphp


  <section class="relative text-white hero">
    <div class="absolute inset-0">
      <img src="/images/pexels-alexander-bobrov-390088-3698780.jpeg" alt="" class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-black/50"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 md:px-12 py-20 md:py-32">
      <div class="mx-auto text-center w-full max-w-3xl md:max-w-4xl lg:max-w-5xl xl:max-w-6xl">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight tracking-wide" style="text-wrap: balance;">
          50代からの<br>新しい好奇心をひらく時間。
        </h1>
        <p class="mt-6 mx-auto w-full max-w-2xl md:max-w-3xl lg:max-w-4xl text-base md:text-lg leading-8 text-white/90">
          伝統芸能に触れられる上質な体験から、路地裏散策や工場見学といったニッチな楽しみまで。<br>
          若さに流されるより、自分らしいスタイルで、新しい好奇心を楽しみたい。<br>
          50代以上の世代が、安心して、無理なく、自分らしく。学び直し、遊び、つながる。<br>
          そんな時間を集めたサイトです。
        </p>

        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
          <a href="{{ route('events.index') }}"
             class="inline-flex items-center justify-center rounded-full px-6 py-3
                    text-base font-semibold bg-orange-500 text-white
                    hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-white/70">
            イベントを探す
          </a>
          <a href="{{ route('submit.events') }}"
             class="inline-flex items-center justify-center rounded-full px-6 py-3
                    text-base font-semibold bg-white/95 text-gray-900 ring-1 ring-white/70 shadow
                    hover:bg-white focus:outline-none focus:ring-2 focus:ring-white/70">
            主催者向け：イベントを掲載
          </a>
          <a href="{{ route('organizer.landing') }}"
             class="inline-flex items-center justify-center rounded-full px-4 py-3
                    text-base font-medium text-white/90 underline decoration-white/40 underline-offset-4
                    hover:decoration-white">
            掲載の流れ・料金を見る
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- ① 近日開催（本日〜7日以内） --}}
  <section class="bg-neutral-50/60 border-t border-neutral-200">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-12">
      <div class="flex items-baseline justify-between">
        <h2 class="text-2xl md:text-3xl font-bold tracking-tight">近日開催（1週間以内）</h2>
        <a href="{{ route('events.index', ['from'=>now()->toDateString(),'to'=>now()->addDays(7)->toDateString()]) }}"
           class="text-sm text-gray-600 hover:text-gray-900 underline underline-offset-4">
          すべて見る
        </a>
      </div>

      @if($soon->isEmpty())
        <p class="text-gray-500 mt-4">直近1週間の開催予定はまだありません。</p>
      @else
        {{-- グリッド：カード共通 --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          @foreach($soon as $e)
            @include('events._card', ['event' => $e])
          @endforeach
        </div>
      @endif
    </div>
  </section>

  {{-- ② 新着イベント（直近追加） --}}
  <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
    <div class="flex items-baseline justify-between">
      <h2 class="text-2xl md:text-3xl font-bold">新着イベント</h2>
      <a href="{{ route('events.index') }}" class="text-sm underline">一覧へ</a>
    </div>

    @if($recent->isEmpty())
      <p class="text-gray-500 mt-4">新着イベントはまだありません。</p>
    @else
      <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recent as $e)
          @include('events._card', ['event' => $e])
        @endforeach
      </div>
    @endif
  </section>

  {{-- ③ 人気タグ別（上位3タグ × 各6件） --}}
  @if(empty($byTag) || collect($byTag)->flatten()->isEmpty())
    <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
      <h2 class="text-2xl md:text-3xl font-bold mb-2">人気タグ別</h2>
      <p class="text-gray-500">タグ付きのイベントがまだありません。イベント登録画面でタグを選ぶと、ここに表示されます。</p>
    </section>
  @else
    @foreach($byTag as $tagName => $list)
      @php $tagId = optional($topTags->firstWhere('name',$tagName))->id; @endphp
      <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
        <div class="flex items-baseline justify-between">
          <h2 class="text-2xl md:text-3xl font-bold">#{{ $tagName }} のイベント</h2>
          @if($tagId)
            <a href="{{ route('events.index', ['tag' => $tagId]) }}" class="text-sm underline">すべて見る</a>
          @endif
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($list as $e)
            @include('events._card', ['event' => $e])
          @endforeach
        </div>
      </section>
    @endforeach
  @endif
</x-site-layout>