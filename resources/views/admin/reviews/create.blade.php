<x-site-layout :title="'運営レビュー作成'">
  <div class="max-w-2xl mx-auto px-6 py-10">
    <h1 class="text-xl font-bold mb-4">運営レビュー作成</h1>

    <p class="text-sm text-gray-600 mb-6">
      対象イベント：
      <a href="{{ route('events.show', $event) }}" class="underline">
        {{ $event->title }}
      </a>
    </p>

    @if ($errors->any())
      <div class="mb-4 rounded bg-red-50 text-red-700 p-3">
        <ul class="list-disc ml-5">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- ★ここだけ修正：event_id で渡す --}}
    <form method="POST" action="{{ route('admin.reviews.store', ['event_id' => $event->id]) }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium">タイトル（任意）</label>
        <input name="title" value="{{ old('title') }}" class="mt-1 w-full rounded border px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium">本文（必須）</label>
        <textarea name="body" rows="8" class="mt-1 w-full rounded border px-3 py-2">{{ old('body') }}</textarea>
      </div>

      <div>
        <label class="block text-sm font-medium">評価（1〜5・任意）</label>
        <input type="number" name="rating" min="1" max="5" value="{{ old('rating') }}"
               class="mt-1 w-24 rounded border px-3 py-2">
      </div>

      <div class="flex items-center gap-2">
        <input id="publish" type="checkbox" name="publish" value="1" class="rounded" {{ old('publish') ? 'checked' : '' }}>
        <label for="publish" class="text-sm">公開する</label>
      </div>

      <div class="pt-4">
        <button class="rounded bg-gray-900 text-white px-4 py-2">保存</button>
        <a href="{{ route('events.show', $event) }}" class="ml-3 text-sm underline">イベント詳細へ戻る</a>
      </div>
    </form>
  </div>
</x-site-layout>
