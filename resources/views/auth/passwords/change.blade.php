<x-guest-layout>
  @if(session('ok')) <div class="mb-4 text-green-700">{{ session('ok') }}</div> @endif
  <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf @method('PUT')

    <div>
      <label class="block text-sm">現在のパスワード</label>
      <input type="password" name="current_password" class="mt-1 w-full rounded border p-2">
      @error('current_password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm">新しいパスワード</label>
      <input type="password" name="password" class="mt-1 w-full rounded border p-2">
      @error('password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="block text-sm">新しいパスワード（確認）</label>
      <input type="password" name="password_confirmation" class="mt-1 w-full rounded border p-2">
    </div>

    <button class="rounded bg-gray-900 px-4 py-2 text-white">更新する</button>
  </form>
</x-guest-layout>
