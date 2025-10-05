<!doctype html>
<meta charset="utf-8">
<title>承認待ちイベント</title>
<style>
  body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;max-width:960px;margin:32px auto;padding:0 16px}
  table{width:100%;border-collapse:collapse}
  th,td{border-bottom:1px solid #eee;padding:8px 6px;text-align:left;vertical-align:top}
  .btn{padding:.45rem .8rem;border:0;border-radius:8px;color:#fff;cursor:pointer}
  .ok{background:#15803d}.ng{background:#b91c1c}.muted{color:#666}
</style>

<h1>承認待ちイベント</h1>

@if (session('ok'))
  <p style="color:green">{{ session('ok') }}</p>
@endif

@if ($pending->isEmpty())
  <p class="muted">現在、承認待ちはありません。</p>
@else
  <table>
    <thead>
      <tr>
        <th>タイトル</th>
        <th>主催者</th>
        <th>開始</th>
        <th>区</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($pending as $ev)
      <tr>
        <td>{{ $ev->title }}</td>
        <td>{{ $ev->organizer_name }}</td>
        <td>{{ $ev->date_start }}</td>
        <td>{{ $ev->ward }}</td>
        <td style="white-space:nowrap">
          <form method="post" action="{{ route('admin.events.publish', $ev) }}" style="display:inline">
            @csrf <button class="btn ok">承認</button>
          </form>
          <form method="post" action="{{ route('admin.events.reject', $ev) }}" style="display:inline;margin-left:6px">
            @csrf <button class="btn ng">却下</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <div style="margin-top:12px">
    {{ $pending->links() }}
  </div>
@endif