{{-- resources/views/mypage/index.blade.php --}}
<x-site-layout :title="'マイページ'">
  <section class="max-w-3xl mx-auto px-4 py-10 space-y-6">
    <h1 class="text-2xl font-bold">マイページ</h1>

    <p class="text-gray-600">ようこそ！ここから主催者メニューに進めます。</p>

    <div class="grid gap-3 md:grid-cols-2">
      <a href="{{ route('submit.events') }}" class="rounded-lg px-4 py-3 bg-blue-600 text-white text-center">
        イベントを登録する
      </a>
      <a href="{{ route('events.index') }}" class="rounded-lg px-4 py-3 border text-center">
        公開イベント一覧を見る
      </a>
    </div>

    <form method="post" action="{{ route('logout') }}">
      @csrf
      <button class="mt-6 underline text-sm text-gray-600">ログアウト</button>
    </form>
  </section>
</x-site-layout>