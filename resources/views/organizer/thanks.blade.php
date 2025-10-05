<x-guest-layout>
  <div class="max-w-2xl mx-auto py-12">

    @if(session('ok'))
      <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-emerald-800 ring-1 ring-emerald-100">
        {{ session('ok') }}
      </div>
    @endif
    @if(session('ng'))
      <div class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-red-800 ring-1 ring-red-100">
        {{ session('ng') }}
      </div>
    @endif

    <h1 class="text-2xl font-bold">投稿を受け付けました</h1>
    <p class="mt-3 text-gray-600">審査後に公開されます。ありがとうございました。</p>

    @php $claimUrl = session('claim_url'); @endphp

    @if($claimUrl)
      <div class="mt-8 rounded-lg border bg-white p-4">
        <h2 class="font-semibold">マイページに投稿を紐づける（任意）</h2>

        @auth
          {{-- ログイン済み：ワンボタンで紐づけ --}}
          <form method="POST" action="{{ $claimUrl }}">
            @csrf
            <button class="mt-4 inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
              マイページに紐づける
            </button>
          </form>

          <a href="{{ route('organizer.dashboard') }}"
             class="mt-3 inline-block text-sm underline text-gray-700">
            ダッシュボードを開く
          </a>
        @else
          {{-- 未ログイン：ログイン→同URLアクセスで紐づく --}}
          <a href="{{ route('login') }}"
             class="mt-4 inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            ログイン／新規登録して紐づける
          </a>
          <p class="mt-3 text-xs text-gray-500 break-all">
            ログイン後に以下へアクセス：<br>
            {{ $claimUrl }}
          </p>
        @endauth
      </div>
    @endif

    <div class="mt-8">
      <a href="{{ route('home') }}" class="text-sm text-gray-600 underline">トップへ戻る</a>
    </div>
  </div>
</x-guest-layout>
