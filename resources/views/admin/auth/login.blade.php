<x-guest-layout>
  <div class="mx-auto max-w-md w-full bg-white rounded-xl shadow p-6">
    <h1 class="text-xl font-semibold mb-4">運営ログイン</h1>

    @if ($errors->any())
      <div class="mb-4 rounded bg-red-50 text-red-700 px-3 py-2 text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm text-gray-700 mb-1">メールアドレス</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full rounded border px-3 py-2">
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">パスワード</label>
        <input type="password" name="password" required class="w-full rounded border px-3 py-2">
      </div>

      <label class="inline-flex items-center gap-2 text-sm text-gray-600">
        <input type="checkbox" name="remember" class="rounded border">
        ログイン状態を保持する
      </label>

      <button class="w-full rounded bg-gray-900 text-white py-2 font-semibold hover:bg-gray-800">
        ログイン
      </button>
    </form>
  </div>
</x-guest-layout>

