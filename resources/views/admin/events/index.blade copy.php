<x-app-layout>
  <x-slot name="header">
    <div class="max-w-7xl mx-auto px-6 md:px-10">
      <h1 class="text-2xl font-bold">イベント審査</h1>
    </div>
  </x-slot>

  <div class="max-w-7xl mx-auto px-6 md:px-10 py-8 space-y-12">

    {{-- フラッシュメッセージ --}}
    @if(session('ok'))
      <div class="rounded-lg bg-emerald-50 text-emerald-800 px-4 py-3 ring-1 ring-emerald-100">
        {{ session('ok') }}
      </div>
    @endif

    {{-- 検索フォーム（ここで閉じる！） --}}
<form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-wrap gap-3 items-center mb-6">
  <input type="text" name="q" value="{{ request('q') }}" placeholder="タイトルで検索"
         class="rounded-md border px-3 py-2 text-sm shadow-sm">
  <button class="rounded-md bg-gray-900 text-white px-4 py-2 text-sm">検索</button>
  @if(request('q')) <a href="{{ route('admin.events.index') }}" class="text-sm underline text-gray-600">クリア</a> @endif
</form>

    {{-- ① 審査待ち --}}


@foreach($pending as $e)
  <div class="rounded-xl border bg-white p-4 flex flex-col gap-3">
    {{-- 省略：タイトル・タグ表示 --}}

    {{-- 送信用のミニフォーム（入れ子にせず、hiddenでOK） --}}
    <form id="pub-{{ $e->id }}" method="POST" action="{{ route('admin.events.publish', $e) }}" class="hidden">
      @csrf
    </form>
    <form id="rej-{{ $e->id }}" method="POST" action="{{ route('admin.events.reject', $e) }}" class="hidden">
      @csrf
    </form>

    <div class="flex gap-2">
      {{-- 承認（自分のフォームへ送信） --}}
      <button type="submit" form="pub-{{ $e->id }}"
              class="rounded-md bg-indigo-600 text-white px-3 py-2 text-sm hover:bg-indigo-700">
        公開
      </button>

      {{-- 却下 --}}
      <button type="submit" form="rej-{{ $e->id }}"
              class="rounded-md bg-gray-200 text-gray-800 px-3 py-2 text-sm hover:bg-gray-300">
        却下
      </button>

      {{-- レビューを書く（GET遷移は a で。form は使わない） --}}
      <a href="{{ route('admin.reviews.create', ['event_id' => $e->id]) }}"
         class="rounded-md border px-3 py-2 text-sm hover:bg-gray-50">
        レビューを書く
      </a>
    </div>
  </div>
@endforeach



    {{-- ② 公開中 --}}
    <section>
      <div class="mb-3 flex items-baseline justify-between">
        <h2 class="text-xl font-semibold">公開中</h2>
        <span class="text-sm text-gray-500">{{ $published->total() }}件</span>
      </div>

      @if($published->isEmpty())
        <p class="text-gray-500">公開中のイベントはありません。</p>
      @else
        <div class="space-y-3">
        @foreach($pending as $e)
  <div class="rounded-xl border bg-white p-4 flex flex-col gap-3">
    {{-- 省略：タイトル・タグ表示 --}}

    {{-- 送信用のミニフォーム（入れ子にせず、hiddenでOK） --}}
    <form id="pub-{{ $e->id }}" method="POST" action="{{ route('admin.events.publish', $e) }}" class="hidden">
      @csrf
    </form>
    <form id="rej-{{ $e->id }}" method="POST" action="{{ route('admin.events.reject', $e) }}" class="hidden">
      @csrf
    </form>

    <div class="flex gap-2">
      {{-- 承認（自分のフォームへ送信） --}}
      <button type="submit" form="pub-{{ $e->id }}"
              class="rounded-md bg-indigo-600 text-white px-3 py-2 text-sm hover:bg-indigo-700">
        公開
      </button>

      {{-- 却下 --}}
      <button type="submit" form="rej-{{ $e->id }}"
              class="rounded-md bg-gray-200 text-gray-800 px-3 py-2 text-sm hover:bg-gray-300">
        却下
      </button>

      {{-- レビューを書く（GET遷移は a で。form は使わない） --}}
      <a href="{{ route('admin.reviews.create', ['event_id' => $e->id]) }}"
         class="rounded-md border px-3 py-2 text-sm hover:bg-gray-50">
        レビューを書く
      </a>
    </div>
  </div>
@endforeach

        <div class="mt-4">
          {{ $published->withQueryString()->links('pagination::tailwind') }}
        </div>
      @endif
    </section>

    {{-- ③ 却下 --}}
    <section>
      <div class="mb-3 flex items-baseline justify-between">
        <h2 class="text-xl font-semibold">却下</h2>
        <span class="text-sm text-gray-500">{{ $rejected->total() }}件</span>
      </div>

      @if($rejected->isEmpty())
        <p class="text-gray-500">却下されたイベントはありません。</p>
      @else
        <div class="space-y-3">
        @foreach($pending as $e)
  <div class="rounded-xl border bg-white p-4 flex flex-col gap-3">
    {{-- 省略：タイトル・タグ表示 --}}

    {{-- 送信用のミニフォーム（入れ子にせず、hiddenでOK） --}}
    <form id="pub-{{ $e->id }}" method="POST" action="{{ route('admin.events.publish', $e) }}" class="hidden">
      @csrf
    </form>
    <form id="rej-{{ $e->id }}" method="POST" action="{{ route('admin.events.reject', $e) }}" class="hidden">
      @csrf
    </form>

    <div class="flex gap-2">
      {{-- 承認（自分のフォームへ送信） --}}
      <button type="submit" form="pub-{{ $e->id }}"
              class="rounded-md bg-indigo-600 text-white px-3 py-2 text-sm hover:bg-indigo-700">
        公開
      </button>

      {{-- 却下 --}}
      <button type="submit" form="rej-{{ $e->id }}"
              class="rounded-md bg-gray-200 text-gray-800 px-3 py-2 text-sm hover:bg-gray-300">
        却下
      </button>

      {{-- レビューを書く（GET遷移は a で。form は使わない） --}}
      <a href="{{ route('admin.reviews.create', ['event_id' => $e->id]) }}"
         class="rounded-md border px-3 py-2 text-sm hover:bg-gray-50">
        レビューを書く
      </a>
    </div>
  </div>
@endforeach
        </div>

        <div class="mt-4">
          {{ $rejected->withQueryString()->links('pagination::tailwind') }}
        </div>
      @endif
    </section>

  </div>
</x-app-layout>

<small class="text-[10px] text-gray-400">
  {{ url('/admin/events/'.$e->id.'/reviews/create') }}
</small>
