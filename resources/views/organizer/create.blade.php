<x-site-layout :title="'イベントを投稿する（主催者向け）'">
  <div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">イベントを投稿する</h1>
    <p class="mt-2 text-gray-600 text-sm">
      掲載は無料。審査後に公開されます。<a href="{{ route('submit.thanks') }}" class="underline hidden">詳細</a>
    </p>

    @if ($errors->any())
      <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
      </div>
    @endif
    <form id="event-form" method="POST" action="{{ route('submit.store') }}" enctype="multipart/form-data">
  @csrf


      {{-- ステップ 1: 基本情報 --}}
      <section class="rounded-2xl border bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">① 基本情報</h2>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium">タイトル <span class="text-red-500">*</span></label>
            <input name="title" value="{{ old('title') }}" required
                   class="mt-1 w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-sm font-medium">開始日時</label>
            <input type="datetime-local" name="date_start" value="{{ old('date_start') }}"
                   class="mt-1 w-full rounded-lg border px-3 py-2">
          </div>

          <div>
            <label class="block text-sm font-medium">エリア（区）</label>
            <select name="ward" class="mt-1 w-full rounded-lg border px-3 py-2">
              <option value="">未選択</option>
              @foreach(\App\Models\Event::WARDS as $w)
                <option value="{{ $w }}" @selected(old('ward')===$w)>{{ $w }}</option>
              @endforeach
            </select>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium">公式URL</label>
            <input name="url" value="{{ old('url') }}" placeholder="https://example.com"
                   class="mt-1 w-full rounded-lg border px-3 py-2">
          </div>
        </div>
      </section>

      {{-- ステップ 2: 画像と説明 --}}
      <section class="rounded-2xl border bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">② 画像と説明</h2>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium">サムネ画像（アップロード）</label>
            <input type="file" name="thumb" id="thumbInput"
                   class="mt-1 block w-full rounded-lg border px-3 py-2">
            <p class="mt-1 text-xs text-gray-500">jpg / png / webp、4MBまで</p>

            <div class="mt-3">
              <img id="thumbPreview" class="hidden w-full rounded-lg border object-cover" alt="選択画像プレビュー">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium">サムネ画像URL（任意）</label>
            <input name="thumb_url" value="{{ old('thumb_url') }}" placeholder="https://…"
                   class="mt-1 w-full rounded-lg border px-3 py-2">
            <p class="mt-1 text-xs text-gray-500">アップロードとURLの両方を指定した場合はアップロードが優先されます。</p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium">簡単な説明（最大500文字）</label>
            <textarea name="excerpt" rows="5" maxlength="500" id="excerpt"
                      class="mt-1 w-full rounded-lg border px-3 py-2">{{ old('excerpt') }}</textarea>
            <div class="mt-1 text-right text-xs text-gray-500"><span id="excerptCount">0</span>/500</div>
          </div>
        </div>
      </section>

      {{-- ステップ 3: タグ --}}
      <section class="rounded-2xl border bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">③ タグ（複数選択可）</h2>
        <p class="mt-1 text-xs text-gray-500">最大3つまで推奨。</p>
        @php $selected = collect(old('tags', [])); @endphp
        <div class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2">
          @foreach($tags as $tag)
            <label class="inline-flex items-center gap-2 rounded-full border px-3 py-2 hover:bg-gray-50">
              <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked($selected->contains($tag->id))>
              <span class="text-sm">{{ $tag->name }}</span>
            </label>
          @endforeach
        </div>
      </section>

      {{-- ステップ 4: 主催者情報 --}}
      <section class="rounded-2xl border bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">④ 主催者情報</h2>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">主催者名</label>
            <input name="organizer_name" value="{{ old('organizer_name') }}"
                   class="mt-1 w-full rounded-lg border px-3 py-2">
          </div>
          <div>
            <label class="block text-sm font-medium">主催者メール</label>
            <input type="email" name="organizer_email" value="{{ old('organizer_email') }}"
                   class="mt-1 w-full rounded-lg border px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium">主催者サイト</label>
            <input name="organizer_url" value="{{ old('organizer_url') }}" placeholder="https://…"
                   class="mt-1 w-full rounded-lg border px-3 py-2">
          </div>
        </div>


      </section>


      {{-- 規約同意 --}}
<div class="mt-6">
  <label class="inline-flex items-center gap-2">
    <input type="checkbox" name="agree_terms" value="1" class="rounded" required>
    <span>
      投稿規約に同意します
      <a href="{{ route('policy', ['return' => url()->current()]) }}"
   target="_blank" rel="noopener" class="underline">
  （規約を読む）
</a>

    </span>
  </label>
  @error('agree_terms')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>




      <div class="flex items-center gap-3">
        <button class="rounded-full bg-indigo-600 text-white px-6 py-3 font-semibold hover:bg-indigo-700">
          投稿する
        </button>
        <a href="{{ route('home') }}" class="text-sm underline text-gray-600">ホームに戻る</a>
      </div>

      {{-- ボット避け（非表示） --}}
      <input type="text" name="hp" class="hidden" tabindex="-1" autocomplete="off">
    </form>
  </div>

  {{-- ちょい足しスクリプト（プレビュー＆文字数） --}}
  <script>
    const fileInput = document.getElementById('thumbInput');
    const preview   = document.getElementById('thumbPreview');
    if (fileInput) {
      fileInput.addEventListener('change', e => {
        const f = e.target.files?.[0];
        if (!f) { preview.classList.add('hidden'); return; }
        const url = URL.createObjectURL(f);
        preview.src = url; preview.classList.remove('hidden');
      });
    }
    const ex = document.getElementById('excerpt'), cnt = document.getElementById('excerptCount');
    if (ex && cnt) {
      const upd = () => cnt.textContent = (ex.value || '').length;
      ex.addEventListener('input', upd); upd();
    }
  </script>


@push('scripts')
<script>
(function(){
  const form = document.querySelector('#event-form');
  const KEY  = 'submit-event-draft-v1';
  if (!form) return;

  // --- 復元 ---
  try {
    const raw = localStorage.getItem(KEY);
    if (raw) {
      const data = JSON.parse(raw);
      for (const [name, val] of Object.entries(data)) {
        const els = form.querySelectorAll(`[name="${name}"]`);
        if (!els.length) continue;

        // checkbox（配列/単体）
        if (els[0].type === 'checkbox') {
          if (name.endsWith('[]') && Array.isArray(val)) {
            els.forEach(chk => chk.checked = val.includes(chk.value));
          } else {
            els[0].checked = !!val;
          }
          continue;
        }
        // radio
        if (els[0].type === 'radio') {
          els.forEach(r => r.checked = (r.value == val));
          continue;
        }
        // file は復元不可
        if (els[0].type === 'file') continue;

        // text/textarea/select など
        els[0].value = (val ?? '');
      }

      // 復元通知（任意）
      const note = document.createElement('div');
      note.className = 'mb-4 rounded bg-amber-50 text-amber-900 ring-1 ring-amber-200 px-3 py-2 text-sm';
      note.innerHTML = `前回の入力を復元しました。<button id="clear-draft" class="ml-2 underline">下書きをクリア</button>`;
      form.insertAdjacentElement('beforebegin', note);
      document.getElementById('clear-draft')?.addEventListener('click', (e)=>{
        e.preventDefault();
        localStorage.removeItem(KEY);
        location.reload();
      });
    }
  } catch(e) { console.warn('draft restore failed', e); }

  // --- 保存 ---
  const save = () => {
    const data = {};
    Array.from(form.elements).forEach(el => {
      if (!el.name) return;
      if (el.disabled) return;
      if (el.type === 'file') return; // 画像は保存不可

      if (el.type === 'checkbox') {
        if (el.name.endsWith('[]')) {
          data[el.name] = Array.from(form.querySelectorAll(`[name="${el.name}"]:checked`)).map(x=>x.value);
        } else {
          data[el.name] = el.checked;
        }
      } else if (el.type === 'radio') {
        if (el.checked) data[el.name] = el.value;
        else if (!(el.name in data)) data[el.name] = null;
      } else {
        data[el.name] = el.value ?? '';
      }
    });
    localStorage.setItem(KEY, JSON.stringify(data));
  };

  form.addEventListener('input', save);
  form.addEventListener('change', save);

  // 送信時は下書きをクリア（重複送信防止）
  form.addEventListener('submit', () => localStorage.removeItem(KEY));
})();
</script>
@endpush

</x-site-layout>
