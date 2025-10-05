<div {{ $attributes->merge(['class' => 'min-h-screen flex items-center justify-center bg-gray-50']) }}>
  <div class="w-full max-w-md mx-auto bg-white shadow rounded-xl p-6">
    @isset($logo)
      <div class="mb-4 text-center">
        {{ $logo }}
      </div>
    @endisset
    {{ $slot }}
  </div>
</div>
