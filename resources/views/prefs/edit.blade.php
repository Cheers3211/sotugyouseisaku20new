
{{-- resources/views/prefs/edit.blade.php（抜粋） --}}
<x-site-layout>


              </div>
      <div>
        <label class="block text-sm">市区町村（任意）</label>
        <input name="home_city" value="{{ old('home_city', auth()->user()->home_city) }}"
               class="w-full border rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm">興味（複数可）</label>
        @php $opts=['美術・展覧会','博物館・歴史','伝統芸能（能・狂言・歌舞伎・落語）','クラシック・ジャズ','まち歩き・路地散策','生涯学習（講座・教養）']; @endphp
        <div class="flex flex-wrap gap-2 mt-2">
          @foreach($opts as $o)
            <label class="inline-flex items-center gap-1 border rounded-full px-3 py-1 bg-white">
              <input type="checkbox" name="interests[]" value="{{ $o }}"
                @checked(in_array($o, (array) (auth()->user()->interests ?? [])))>
              <span>{{ $o }}</span>
            </label>
          @endforeach
        </div>
      </div>
      <div class="text-right">
        <button class="px-4 py-2 rounded bg-indigo-600 text-white">保存</button>
      </div>
    </form>
  </div>
</x-site-layout>
