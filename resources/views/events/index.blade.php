<x-site-layout :title="'イベント一覧'">
  <div class="max-w-7xl mx-auto px-6 md:px-12 py-10">

    <div class="mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <h1 class="text-2xl font-bold">イベント一覧</h1>

      <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-2 w-full md:w-auto">
        <input type="text" name="keyword" value="{{ request('keyword') }}"
               placeholder="タイトル / タグ名で検索" class="rounded border px-3 py-2 text-sm md:col-span-2">

        <select name="tag" class="rounded border px-3 py-2 text-sm">
          <option value="">タグ指定なし</option>
          @foreach($allTags as $t)
            <option value="{{ $t->id }}" @selected(request('tag')==$t->id)>{{ $t->name }}</option>
          @endforeach
        </select>

        <select name="ward" class="rounded border px-3 py-2 text-sm">
          <option value="">区 指定なし</option>
          @foreach($wards as $w)
            <option value="{{ $w }}" @selected(request('ward')===$w)>{{ $w }}</option>
          @endforeach
        </select>

        <input type="date" name="from" value="{{ request('from') }}" class="rounded border px-3 py-2 text-sm">


        <div class="flex items-center gap-2 md:col-span-6">
          <select name="sort" class="rounded border px-3 py-2 text-sm">
            <option value="date_asc"  @selected(request('sort','date_asc')==='date_asc')>日付昇順</option>
            <option value="date_desc" @selected(request('sort')==='date_desc')>日付降順</option>
            <option value="title"     @selected(request('sort')==='title')>タイトル</option>
          </select>

          <select name="per_page" class="rounded border px-3 py-2 text-sm">
            @foreach($allowed as $n)
              <option value="{{ $n }}" @selected((int)request('per_page',$perPage)===$n)>{{ $n }}件</option>
            @endforeach
          </select>

          <button class="rounded bg-gray-800 text-white px-4 py-2 text-sm">検索</button>
        </div>
      </form>
    </div>

    @if($events->isEmpty())
      <p class="text-gray-500">該当するイベントはありませんでした。</p>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $e)
          @php $host = $e->url ? parse_url($e->url, PHP_URL_HOST) : null; @endphp

          <div class="rounded-xl border bg-white p-4 hover:shadow-sm transition">
            <div class="flex items-start gap-3">
              <div class="shrink-0 w-12 h-12 rounded-lg bg-neutral-100 flex items-center justify-center text-neutral-500">🗓</div>

              <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                  <span>{{ optional($e->date_start)->format('Y/m/d') ?? '日時未定' }}</span>
                  @if($e->ward) <span>・{{ $e->ward }}</span> @endif
                </div>

                {{-- タイトル＝自サイト詳細 --}}
                <h3 class="mt-1 font-semibold leading-snug line-clamp-2">
                  <a href="{{ route('events.show', $e) }}" class="hover:text-indigo-600 underline-offset-2">
                    {{ $e->title }}
                  </a>
                </h3>

                @if(!empty($e->overview ?? $e->excerpt))
                  <p class="mt-2 text-sm text-gray-700 line-clamp-3">{{ $e->overview ?? $e->excerpt }}</p>
                @endif

                {{-- CTA群 --}}
                <div class="mt-3 flex flex-wrap gap-3">
                  @if($e->url)
                    <a href="{{ route('events.go', $e) }}" target="_blank" rel="noopener nofollow ugc"
                       class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium bg-orange-500 text-white hover:bg-orange-600">
                      公式ページへ <span class="opacity-80">({{ $host }})</span>
                    </a>
                  @endif

                  <a href="{{ route('events.show', $e) }}"
                     class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm underline">
                    詳細を見る
                  </a>
                </div>

                {{-- タグ（null安全＆表示はカード下部） --}}
                @if(($e->tags?->count() ?? 0) > 0)
                  <div class="mt-2 flex flex-wrap gap-1">
                    @foreach($e->tags as $t)
                      <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">#{{ $t->name }}</span>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-8">
        {{ $events->withQueryString()->links() }}
      </div>
    @endif

  </div>
</x-site-layout>
