<x-guest-layout>
  <div class="max-w-3xl mx-auto py-10 px-6">
    <div class="text-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold">投稿規約</h1>
      <p class="mt-2 text-sm text-gray-500">
        最終更新：{{ now()->format('Y/m/d') }}
      </p>
    </div>

    <div class="prose prose-sm md:prose-base max-w-none">
      <h2>1. 目的</h2>
      <p>
        本規約は、利用者（イベント主催者）が本サイトにイベント情報を投稿する際の条件を定めるものです。
        投稿をもって本規約に同意したものとみなします。
      </p>

      <h2>2. 投稿できる内容</h2>
      <ul>
        <li>50代以上向けの「学び・文化・街歩き」等の良質な体験に関するイベント情報</li>
        <li>公序良俗・法令に反しないもの、反社会的勢力と無関係なもの</li>
        <li>写真・説明文・外部リンクは、投稿者が正当な権利を有しているもの</li>
      </ul>

      <h2>3. 禁止事項</h2>
      <ul>
        <li>虚偽・誤認を与える情報の掲載</li>
        <li>誹謗中傷、差別、違法行為の助長、危険物の販売・勧誘</li>
        <li>著作権・商標権・肖像権等、第三者の権利侵害</li>
        <li>アフィリエイト等、イベント情報と無関係な広告のみの掲載</li>
      </ul>

      <h2>4. 画像・文章の取扱い</h2>
      <p>
        投稿者は、掲載・紹介のために必要な範囲で、当サイトが投稿内容（タイトル、説明、画像、タグ等）を
        編集・要約・トリミングして表示することに無償で同意します。
      </p>

      <h2>5. 掲載可否・修正・削除</h2>
      <ul>
        <li>投稿内容は運営による審査後に公開されます。</li>
        <li>審査基準や掲載可否の理由は開示しない場合があります。</li>
        <li>規約違反・不適切・情報の古さ等の理由で、予告なく修正・非公開・削除することがあります。</li>
      </ul>

      <h2>6. 責任</h2>
      <p>
        投稿内容に関する一切の責任は投稿者が負い、当サイトはこれに起因する損害について責任を負いません。
        参加申込・支払・中止対応など、イベント運営は主催者の責任で行ってください。
      </p>

      <h2>7. 個人情報</h2>
      <p>
        取得した連絡先は、掲載に関する確認・連絡の目的に限り利用します。プライバシーポリシーに従い適切に取り扱います。
      </p>

      <h2>8. 規約の変更</h2>
      <p>
        本規約は必要に応じて改定されます。改定後の規約は当ページに掲示した時点で効力を生じます。
      </p>

      <h2>お問い合わせ</h2>
      <p>
        掲載・修正・削除のご依頼は
        <a href="mailto:{{ config('mail.from.address') ?? 'no-reply@example.com' }}" class="underline">
          {{ config('mail.from.address') ?? 'no-reply@example.com' }}
        </a>
        までご連絡ください。
      </p>
    </div>

    @php
  $backUrl = request('return')
              ?? (url()->previous() ?: route('submit.create'));
@endphp

<div class="mt-10 flex items-center justify-center gap-3">
  <a href="{{ $backUrl }}"
     class="rounded-md border px-4 py-2 text-sm hover:bg-gray-50">
    前の画面に戻る
  </a>

  <a href="{{ route('submit.create') }}"
     class="rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
    投稿フォームへ
  </a>
</div>

</x-guest-layout>
