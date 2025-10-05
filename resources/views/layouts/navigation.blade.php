{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open:false }" class="bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="h-16 flex items-center justify-between">

      {{-- 左：ロゴ + PCナビ --}}
      <div class="flex items-center">
        <a href="{{ route('/') }}" class="shrink-0 flex items-center gap-2">
          <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
          <span class="sr-only">{{ config('app.name') }}</span>
        </a>

        <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-8">
          <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
            イベント
          </x-nav-link>
          <x-nav-link :href="route('organizer.landing')" :active="request()->routeIs('organizer.landing')">
            主催者の方へ
          </x-nav-link>
        </div>
      </div>

      {{-- 右：CTA / ログイン・ログアウト（PC） --}}
      <div class="hidden sm:flex items-center gap-3">

        {{-- 常時：投稿CTA --}}
        <a href="{{ route('organizer.events.create') }}"
           class="inline-flex items-center rounded-full bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
          イベントを掲載
        </a>

        {{-- 一般ユーザー：未ログイン --}}
        @guest('web')
          <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">ログイン</a>
          <a href="{{ route('register') }}"
             class="inline-flex items-center rounded-full bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            アカウント作成
          </a>
        @endguest

        {{-- 一般ユーザー：ログイン中 --}}
        @auth('web')
          <a href="{{ route('organizer.dashboard') }}"
             class="mr-1 inline-flex items-center rounded-full bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            マイページ
          </a>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">ログアウト</button>
          </form>
        @endauth

        {{-- 管理ユーザー：ログイン中 --}}
        @auth('admin')
          <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900">審査</a>
          <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">運営ログアウト</button>
          </form>
        @endauth
      </div>

      {{-- ハンバーガー（SP） --}}
      <div class="sm:hidden -me-2">
        <button @click="open = !open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  {{-- SPメニュー --}}
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
        イベント
      </x-responsive-nav-link>
      <x-responsive-nav-link :href="route('organizer.landing')" :active="request()->routeIs('organizer.landing')">
        主催者の方へ
      </x-responsive-nav-link>

      <a href="{{ route('organizer.events.create') }}"
         class="block mx-3 mt-1 rounded-lg bg-orange-500 px-4 py-2 text-center font-semibold text-white hover:bg-orange-600">
        イベントを掲載
      </a>

      {{-- 一般ユーザー：SPのマイページ/ログアウト --}}
      @auth('web')
        <a href="{{ route('organizer.dashboard') }}" class="block mx-3 text-sm underline">マイページ</a>
        <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-2">
          @csrf
          <button class="w-full text-left text-gray-700">ログアウト</button>
        </form>
      @endauth

      @guest('web')
        <x-responsive-nav-link :href="route('login')">ログイン</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('register')">アカウント作成</x-responsive-nav-link>
      @endguest

      {{-- 管理ユーザー：SPの審査/ログアウト --}}
      @auth('admin')
        <a href="{{ route('admin.events.index') }}" class="block mx-3 text-sm underline">審査</a>
        <form id="admin-logout-form" method="POST" action="{{ route('admin.logout') }}" class="mx-3 mt-2">
          @csrf
          <button class="w-full text-left text-gray-700">運営ログアウト</button>
        </form>
      @endauth
    </div>
  </div>
</nav>