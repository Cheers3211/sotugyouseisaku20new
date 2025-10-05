<!doctype html>
<x-site-layout :title="'イベント登録'">
  <section class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-2xl font-bold mb-6">イベント登録</h1>

    @if (session('ok'))
      <div class="mb-6 rounded-xl bg-green-50 text-green-700 p-4">
        {{ session('ok') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-6 rounded-xl bg-red-50 text-red-700 p-4">
        <ul class="list-disc pl-5 space-y-1">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- ★ action は submit.events.store に統一 --}}
    <form action="{{ route('submit.events.store') }}" method="post" enctype="multipart/form-data"
          class="bg-white rounded-2xl ring-1 ring-black/5 shadow-sm p-6 space-y-6">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">タイトル <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title') }}"
               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">開始日時</label>
          <input type="datetime-local" name="date_start"
                 value="{{ old('date_start') }}"
                 class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">区（23区）</label>
          <select name="ward" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">— 選択なし —</option>
            @php($__wards = defined(\App\Models\Event::class.'::WARDS') ? \App\Models\Event::WARDS : [])
            @foreach($__wards as $w)
              <option value="{{ $w }}" @selected(old('ward')===$w || old('ward')===$w.'区')>
                {{ $w === 'その他(近郊)' ? $w : $w.'区' }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">イベント公式URL</label>
        <input type="url" name="url" value="{{ old('url') }}"
               placeholder="https://example.com"
               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">サムネ画像（ファイルアップロード）</label>
          <input type="file" name="thumb" accept="image/*"
                 class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
          <p class="mt-1 text-xs text-gray-500">jpg / png / webp ・最大4MB</p>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">サムネ画像URL（任意）</label>
          <input type="url" name="thumb_url" value="{{ old('thumb_url') }}"
                 placeholder="https://…/image.jpg"
                 class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
          <p class="mt-1 text-xs text-gray-500">※ファイルとURLの両方を入れた場合はファイルが優先</p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">簡単な説明（詳細はリンク先参照）</label>
        <textarea name="excerpt" rows="3"
          class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="例）初心者歓迎の体験イベント。道具は貸し出しあり。">{{ old('excerpt') }}</textarea>
        <p class="mt-1 text-xs text-gray-500">最大300文字、トップや一覧に短く表示されます</p>
      </div>
{{-- 主催者名（必須） --}}
<div>
  <label class="block text-sm font-medium mb-1">主催者名 <span class="text-red-500">*</span></label>
  <input id="organizer_name"
         name="organizer_name"
         type="text"
         value="{{ old('organizer_name') }}"
         class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
         required>
  @error('organizer_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
</div>

      {{-- タグ（存在する時だけ表示） --}}
      @isset($tags)
      <fieldset class="mt-2">
        <legend class="text-sm font-semibold text-gray-700">タグ（複数選択可）</legend>
        <p class="text-xs text-gray-500 mb-2">※3つまで推奨</p>
        @php($selected = collect(old('tags', [])))
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
          @foreach($tags as $tag)
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked($selected->contains($tag->id))>
              <span>{{ $tag->name }}</span>
            </label>
          @endforeach
        </div>
      </fieldset>
      @endisset

      {{-- 主催者名・投稿規約チェック（必須） --}}
      <div>
  <label class="inline-flex items-center gap-2">
    <input type="checkbox" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}>
    <span>
      投稿規約に同意します <span class="text-red-500">*</span>
      <a href="{{ route('terms.post') }}" target="_blank" class="underline text-indigo-600 hover:text-indigo-800">
        （規約を読む）
      </a>
    </span>
  </label>
  @error('agree_terms')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
</div>


 

      <div class="pt-2">
        <button type="submit"
                class="inline-flex items-center justify-center rounded-full bg-amber-500 px-6 py-3 font-semibold text-white shadow hover:bg-amber-600 transition">
          登録する
        </button>
        <a href="{{ route('events.index') }}" class="ml-3 text-sm text-gray-600 hover:text-gray-900">一覧に戻る</a>
      </div>
    </form>
  </section>
</x-site-layout>