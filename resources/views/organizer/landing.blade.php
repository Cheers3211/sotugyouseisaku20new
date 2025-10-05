<x-site-layout :title="'主催者の方へ'">
  {{-- Hero --}}
  <section class="bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-16 md:py-24 text-center">
      <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">
        時彩（ときいろ）にイベントを掲載しませんか？
      </h1>



<p class="mt-5 text-base md:text-lg text-gray-600">
        50代以上の読者に届く“学び・文化・街歩き”の良質なイベントを募集しています。掲載はかんたん・無料。
      </p>

      {{-- 主催者ログイン（webガード） --}}

      <div class="mt-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-2">
  {{-- 主催者ログイン --}}
  <a href="{{ route('login') }}"
     class="inline-flex items-center rounded-full bg-gray-900 text-white px-4 py-2 text-sm hover:bg-gray-800">
    主催者ログイン
  </a>

  {{-- 新規登録 --}}
  <a href="{{ route('register') }}"
     class="inline-flex items-center rounded-full border px-4 py-2 text-sm hover:bg-gray-50">
    新規登録
  </a>

  {{-- パスワードをお忘れの方 --}}
  <a href="{{ route('password.request') }}" class="text-sm underline">
    パスワードをお忘れの方
  </a>
</div>
{{-- CTA：もっと大きく＆少し下へ --}}
<div class="mt-12 md:mt-16 text-center">
  <a href="{{ route('submit.create') }}"
     class="inline-flex items-center gap-2 rounded-full px-6 py-3 md:px-8 md:py-4
            text-base md:text-lg font-extrabold
            bg-orange-500 text-white
            shadow-lg shadow-orange-500/30 ring-2 ring-orange-400/60
            hover:bg-orange-600 hover:shadow-orange-600/30
            active:scale-[.98] transition">
    無料でイベントを掲載する
    {{-- 任意でアイコン風の > （消してもOK） --}}
    <span aria-hidden>›</span>
  </a>
  <p class="mt-3 text-xs text-gray-500">初期費用・月額ともに0円。掲載はかんたん。</p>
</div>

    </div>
  </section>


  {{-- Value / Benefits --}}
  <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
    <h2 class="text-2xl md:text-3xl font-bold">選ばれる理由</h2>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="rounded-2xl border bg-white p-6">
        <h3 class="font-semibold">ターゲット直撃</h3>
        <p class="mt-2 text-gray-600">50代以上の学び志向ユーザーに最適化。興味関心での検索・タグ導線で届きやすい。</p>
      </div>
      <div class="rounded-2xl border bg-white p-6">
        <h3 class="font-semibold">掲載は無料</h3>
        <p class="mt-2 text-gray-600">初期費用・月額ともに<strong>0円</strong>。公式申込ページへ直接送客します。</p>
      </div>
      <div class="rounded-2xl border bg-white p-6">
        <h3 class="font-semibold">運営レビュー</h3>
        <p class="mt-2 text-gray-600">掲載前に運営が内容をチェック。ユーザーが安心して選べる環境を提供します。</p>
      </div>
    </div>
  </section>

  {{-- Flow --}}
  <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
    <h2 class="text-2xl md:text-3xl font-bold">掲載までの流れ</h2>
    <ol class="mt-6 space-y-4">
      <li class="rounded-xl border bg-white p-5"><span class="font-semibold">① フォームから投稿</span>（タイトル・日時・区・公式URL・概要・タグ）</li>
      <li class="rounded-xl border bg-white p-5"><span class="font-semibold">② 運営で内容確認</span>（不備があればご連絡）</li>
      <li class="rounded-xl border bg-white p-5"><span class="font-semibold">③ サイトに公開</span>（新着やタグ一覧・検索に表示）</li>
      <li class="rounded-xl border bg-white p-5"><span class="font-semibold">④ 公式へ送客</span>（イベント詳細/一覧から公式ページへ直接リンク）</li>
    </ol>
    <div class="mt-8">
      <a href="{{ route('submit.create') }}"
         class="inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white hover:bg-orange-600">
        今すぐ投稿する
      </a>
    </div>
  </section>

  {{-- FAQ --}}
  <section class="max-w-7xl mx-auto px-6 md:px-12 py-12">
    <h2 class="text-2xl md:text-3xl font-bold">よくある質問</h2>
    <div class="mt-6 space-y-3">
      <details class="rounded-xl border bg-white p-5">
        <summary class="font-semibold cursor-pointer">掲載は本当に無料ですか？</summary>
        <p class="mt-2 text-gray-600">無料です。申込や決済は主催者様の公式ページで行っていただきます。</p>
      </details>
      <details class="rounded-xl border bg-white p-5">
        <summary class="font-semibold cursor-pointer">掲載までの目安は？</summary>
        <p class="mt-2 text-gray-600">通常1〜2営業日以内に審査・公開します。</p>
      </details>
      <details class="rounded-xl border bg-white p-5">
        <summary class="font-semibold cursor-pointer">対象ジャンルは？</summary>
        <p class="mt-2 text-gray-600">伝統芸能・文化体験・見学・講座・街歩きなど、50+の学び/好奇心にマッチする催しが対象です。</p>
      </details>
      <details class="rounded-xl border bg-white p-5">
        <summary class="font-semibold cursor-pointer">画像は必要？</summary>
        <p class="mt-2 text-gray-600">任意ですが、あるとクリック率が上がります。URL指定か画像アップロードに対応。</p>
      </details>
    </div>
  </section>

  {{-- Final CTA --}}
  <section class="max-w-7xl mx-auto px-6 md:px-12 py-16 text-center">
    <h2 class="text-2xl md:text-3xl font-bold">あなたのイベントを、必要な人に届けましょう。</h2>
    <a href="{{ route('submit.create') }}"
       class="mt-6 inline-flex items-center rounded-full bg-orange-500 px-6 py-3 font-semibold text-white hover:bg-orange-600">
      無料で掲載する
    </a>
  </section>
</x-site-layout>
