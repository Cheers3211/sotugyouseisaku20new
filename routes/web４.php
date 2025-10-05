<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\SubmitEventController;
use App\Http\Controllers\Organizer\OrganizerEventController;


// ★最優先で登録（他のルートより前に置く）
Route::get('/', function () {
    return response('<!doctype html><meta charset="utf-8"><title>HOME</title><h1>HOME OK</h1>', 200)
           ->header('Content-Type','text/html; charset=utf-8');
})->name('home');

// /home に来た古いリンクは / へ寄せる（別名でOK）
Route::any('/home', fn() => redirect()->route('home'))->name('home.alias');

// ルート一覧を見れる一時デバッグ（後で消してOK）
Route::get('/_debug/routes', function () {
    $rows = collect(\Illuminate\Support\Facades\Route::getRoutes())
      ->map(fn($r) => str_pad(($r->getName() ?: '(no-name)'), 25) . ' | ' . $r->uri())
      ->implode("\n");
    return response('<pre>'.e($rows).'</pre>', 200)->header('Content-Type', 'text/html; charset=utf-8');
});

/* =============================
   0) TOP（まずは確実に200を返す）
   ============================= */
// ======= SAFE TOP =======
// 旧テンプレ互換：route('home') を呼ばれても TOP へ寄せる
if (!Route::has('home')) {
    Route::get('/home', fn () => redirect()->route('top'))->name('home');
}

Route::any('/', function () {
    return response('<!doctype html><meta charset="utf-8">
<title>TOP</title><div style="max-width:640px;margin:48px auto;font:16px/1.6 -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif">
<h1 style="margin:0 0 12px">TOP READY</h1>
<p>ここまで来ればOK。後で <code>view("home")</code> に差し替えよう。</p>
</div>', 200)->header('Content-Type','text/html; charset=utf-8');
})->name('z_top_safe');

// home → TOPへ
Route::get('/home', fn() => redirect()->route('z_top_safe'))->name('home');

// 主催者ランディング（ログイン不要）
Route::view('/organizer/landing', 'organizer.landing')->name('organizer.landing');

// TOP：本番ビューへ（安全に空コレクションを渡す）
Route::get('/', function () {
    $soon      = collect([]);
    $recent    = collect([]);
    $featured  = collect([]);
    $popular   = collect([]);
    $picks     = collect([]);
    $recommend = collect([]);
    return view('home', compact('soon','recent','featured','popular','picks','recommend'));
})->name('top');


// 旧テンプレ互換：route('home') が呼ばれてもTOPへ
//Route::get('/home', fn () => redirect()->route('top'))->name('home');
Route::get('/home', fn() => redirect()->route('z_top_safe'))->name('home');


/* =============================
   1) 主催者向けランディング（公開）
   ============================= */
// 実体は resources/views/organizer/landing.blade.php
// 単数名で呼ばれても動くようにエイリアス
Route::get('/organizer', fn () => redirect()->route('organizers.landing'))
    ->name('organizer.landing');
//Route::view('/organizers', 'organizer.landing')->name('organizers.landing');
// 主催者：ランディングページ（ログイン不要）
Route::view('/organizer/landing', 'organizer.landing')->name('organizer.landing');


/* =============================
   2) 主催者：イベント登録（公開＝ログイン不要）
   ============================= */
Route::prefix('submit')->group(function () {
    Route::get('events',  [SubmitEventController::class, 'create'])->name('submit.events');
    Route::post('events', [SubmitEventController::class, 'store'])->name('submit.events.store');
});
// 旧テンプレ互換：route('submit.create')
Route::get('/submit/create', fn () => redirect()->route('submit.events'))->name('submit.create');


/* =============================
   3) 公開イベント一覧
   ============================= */
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])
    ->whereNumber('event')->name('events.show');


/* =============================
   4) 管理：承認フロー（要管理権限）
   ============================= */
Route::prefix('admin')->name('admin.')->middleware(['auth','can:admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.events.index'))->name('home');
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/events/{event}/publish', [AdminEventController::class, 'publish'])
        ->whereNumber('event')->name('events.publish');
    Route::post('/events/{event}/reject',  [AdminEventController::class, 'reject'])
        ->whereNumber('event')->name('events.reject');
});


/* =============================
   5) マイページ（要ログイン）
   ============================= */
Route::middleware('auth')->group(function () {
    Route::view('/mypage', 'mypage.index')->name('mypage.home');
    // 誤って /home に来た場合の寄せ
    Route::get('/home', fn () => redirect()->route('mypage.home'));
});


/* =============================
   6) デバッグ用（任意：簡易ログイン/ログアウト）
   ============================= */
Route::get('/_debug/me', function () {
    if (!Auth::check()) return '<a href="/login">login</a>';
    $t = csrf_token();
    return response(
        '<pre>'.htmlspecialchars(json_encode([
            'id'=>Auth::user()->id ?? null,
            'email'=>Auth::user()->email ?? null,
            'is_admin'=>Auth::user()->is_admin ?? null
        ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)).'</pre>'.
        '<form method="post" action="/logout"><input type="hidden" name="_token" value="'.$t.'"><button>Logout (POST)</button></form>',
        200
    )->header('Content-Type','text/html; charset=utf-8');
})->middleware('web');

Route::get('/login', function () {
    $t = csrf_token();
    $html = '<!doctype html><meta charset="utf-8"><title>Login</title>'
          . '<form method="post" action="/login" style="max-width:360px;margin:48px auto;font:16px/1.6 -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial">'
          . '<h2>Login (temporary)</h2>'
          . '<input type="hidden" name="_token" value="'.$t.'">'
          . '<div><label>Email</label><br><input name="email" type="email" required style="width:100%;padding:8px"></div>'
          . '<div><label>Password</label><br><input name="password" type="password" required style="width:100%;padding:8px"></div>'
          . '<label><input type="checkbox" name="remember"> remember</label>'
          . '<div style="margin-top:12px"><button type="submit" style="padding:8px 16px">Login</button></div>'
          . '</form>';
    return response($html, 200)->header('Content-Type','text/html; charset=utf-8');
})->name('login');

Route::post('/login', function (Request $r) {
    $cred = $r->validate(['email'=>['required','email'],'password'=>['required']]);
    if (Auth::attempt($cred, $r->boolean('remember'))) {
        $r->session()->regenerate();
        $u = Auth::user();
        $isAdmin = (int)($u->is_admin ?? 0) === 1 || ($u->role ?? null) === 'admin';
        return $isAdmin ? redirect()->route('admin.events.index')
                        : redirect()->route('submit.events');
    }
    return back()->setContent('Login failed')->withInput(['email'=>$cred['email']]);
});

Route::post('/logout', function (Request $r) {
    Auth::logout();
    $r->session()->invalidate();
    $r->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');


/* =============================
   7) フォールバック（未定義はTOPへ）
   ============================= */
Route::fallback(fn () => redirect()->route('top'));