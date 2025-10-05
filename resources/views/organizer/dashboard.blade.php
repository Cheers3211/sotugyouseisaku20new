<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl">マイページ（ダッシュボード）</h2>
  </x-slot>

  <div class="max-w-5xl mx-auto p-6">
    @if(session('ok'))
      <div class="mb-4 rounded bg-emerald-50 p-3 text-emerald-800">{{ session('ok') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-4 rounded bg-rose-50 p-3 text-rose-800">{{ session('error') }}</div>
    @endif

    @forelse($events as $event)
      <div class="mb-3 rounded border p-4">
        <div class="font-bold">{{ $event->title }}</div>
        <div class="text-sm text-gray-500">{{ $event->date_start }}</div>
        @if($event->tags?->count())
          <div class="mt-2 text-xs text-gray-600">
            @foreach($event->tags as $t)
              <span class="mr-1 rounded bg-gray-100 px-2 py-0.5">{{ $t->name }}</span>
            @endforeach
          </div>
        @endif
      </div>
    @empty
      <p class="text-gray-600">まだ紐づいた投稿がありません。</p>
    @endforelse

    <div class="mt-6">{{ $events->links() }}</div>
  </div>
</x-app-layout>
