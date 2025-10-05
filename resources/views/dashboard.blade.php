<x-app-layout>
  @php($title = 'マイページ')
  <div class="rounded-xl border bg-white p-6">
    <p>ログインしました。</p>
    <div class="mt-4 flex gap-3">
      <a href="{{ route('admin.events.index') }}" class="rounded-full bg-gray-900 text-white px-4 py-2 text-sm font-semibold">審査ダッシュボードへ</a>
      <a href="{{ route('organizer.events.create') }}" class="rounded-full bg-orange-500 text-white px-4 py-2 text-sm font-semibold hover:bg-orange-600">イベントを掲載する</a>
    </div>
  </div>
</x-app-layout>
