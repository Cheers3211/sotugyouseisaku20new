<x-site-layout>
  <div class="max-w-7xl mx-auto px-4 py-6">
    <h1 class="text-xl font-bold mb-4">最近{{ request('days',7) }}日で取り込んだイベント</h1>
    <table class="w-full text-sm">
      <thead><tr class="text-left border-b">
        <th>fetched_at</th><th>source</th><th>title</th><th>date</th><th>pref</th><th>image?</th>
      </tr></thead>
      <tbody>
      @foreach($events as $e)
        <tr class="border-b">
          <td>{{ optional($e->fetched_at)->format('Y-m-d H:i') }}</td>
          <td>{{ $e->source }}</td>
          <td class="truncate max-w-[28rem]"><a class="text-indigo-600 underline" href="{{ route('events.show',$e) }}">{{ $e->title }}</a></td>
          <td>{{ optional($e->date_start)->format('Y-m-d') }}</td>
          <td>{{ $e->prefecture }}</td>
          <td>{{ $e->has_image ? '✔︎' : '—' }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <div class="mt-4">{{ $events->withQueryString()->links() }}</div>
  </div>
</x-site-layout>
