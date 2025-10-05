<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\SubmitEventController;

/* 1) トップ：正式に name('home') を付与 */
Route::get('/', function () {
    // まずは簡易表示。落ち着いたら view('home') に差し替え可
    return view('home');  // ← ここは home.blade.php が無いなら一時的に簡易HTMLでもOK
})->name('home');

/* 2) 主催者向けランディング（公開）
   実体: resources/views/organizer/landing.blade.php */
Route::view('/organizers', 'organizer.landing')->name('organizers.landing');
// 単数で呼ばれているBlade互換（任意）
Route::get('/organizer', fn () => redirect()->route('organizers.landing'))
    ->name('organizer.landing');
// register → そのまま投稿画面へ（ログイン不要運用）
// register → そのまま投稿画面へ（ログイン不要運用）
Route::get('/register', fn() => redirect()->route('submit.events'))->name('register');
// パスワード再設定まわりを “使わない” 想定のダミー（安全着地）
Route::get('/forgot-password', fn () => redirect()->route('organizers.landing'))
    ->name('password.request');

Route::post('/forgot-password', fn () => back()->with('status', 'メール送信(ダミー)'))
    ->name('password.email');

Route::get('/reset-password/{token}', fn () => redirect()->route('organizers.landing'))
    ->name('password.reset');

Route::post('/reset-password', fn () => back())
    ->name('password.update');



/* 3) 主催者：イベント登録（公開＝ログイン不要） */
Route::prefix('submit')->group(function () {
    Route::get('events',  [SubmitEventController::class, 'create'])->name('submit.events');
    Route::post('events', [SubmitEventController::class, 'store'])->name('submit.events.store');
// 規約ページ（投稿規約） - 誰でも閲覧可
Route::view('/terms/post', 'terms.post')->name('terms.post');

});
// 旧テンプレ互換：/submit/create → /submit/events
Route::get('/submit/create', fn () => redirect()->route('submit.events'))->name('submit.create');


// 既存：一覧本体
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// 既存：詳細
Route::get('/events/{event}', [EventController::class, 'show'])
    ->whereNumber('event')->name('events.show');

// 追加：互換エイリアス（events.go → events.index に寄せる）
Route::get('/events-go', fn () => redirect()->route('events.index'))->name('events.go');

/* 4) 公開イベント一覧（誰でも閲覧可） */
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])
    ->whereNumber('event')->name('events.show');

/* 5) 管理：承認フロー（要管理権限） */

Route::prefix('admin')->name('admin.')->middleware(['auth','can:admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.events.index'))->name('home');
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');

    // 後で復活させる用のメモ
    // Route::post('/events/{event}/publish', [AdminEventController::class, 'publish'])
    //     ->whereNumber('event')->name('events.publish');
    // Route::post('/events/{event}/reject',  [AdminEventController::class, 'reject'])
    //     ->whereNumber('event')->name('events.reject');
}); // ★ここを必ず閉じる（以前は //}); でコメントアウトされてた）

// ここからは管理グループの外
Route::post('/login', function (Request $r) {
    $cred = $r->validate([
        'email'    => ['required','email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($cred, $r->boolean('remember'))) {
        $r->session()->regenerate();

        $u = Auth::user();
        $isAdmin = (int)($u->is_admin ?? 0) === 1 || ($u->role ?? '') === 'admin';

        // ★ 行き先をまず決める → intended で実行（あれば優先、なければ既定に飛ぶ）
        $target = $isAdmin ? route('admin.events.index') : route('mypage.home');
        return redirect()->intended($target);
    }

    return back()->setContent('Login failed')->withInput(['email' => $cred['email']]);
});




/* 6) マイページ（ログイン時のみ） */
Route::middleware('auth')->group(function () {
    Route::view('/mypage', 'mypage.index')->name('mypage.home');
});

/* 7) （任意）簡易ログイン/ログアウトが必要なら残す


/* ---- Login (GET) ---- */
Route::get('/login', function () {
    // 仮の超シンプル画面（後で正式ビューに差し替えOK）
    $t = csrf_token();
    return response('<!doctype html><meta charset="utf-8"><title>Login</title>
    <div style="max-width:360px;margin:48px auto;font:16px/1.6 -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial">
      <h2>ログイン</h2>
      <form method="post" action="/login">
        <input type="hidden" name="_token" value="'.$t.'">
        <div><label>Email</label><br><input name="email" type="email" required style="width:100%;padding:8px"></div>
        <div><label>Password</label><br><input name="password" type="password" required style="width:100%;padding:8px"></div>
        <label><input type="checkbox" name="remember">ログイン状態を保持</label>
        <div style="margin-top:12px"><button style="padding:8px 16px">Login</button></div>
      </form>
    </div>', 200)->header('Content-Type','text/html; charset=utf-8');
})->name('login');

/* ---- Login (POST) ---- */
Route::post('/login', function (Request $r) {
    $cred = $r->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);
    if (Auth::attempt($cred, $r->boolean('remember'))) {
        $r->session()->regenerate();
        // 成功時：直前に行きたかった場所 or マイページへ
        return redirect()->intended('/mypage');
    }
    return back()->setContent('Login failed')->withInput(['email' => $r->input('email')]);
});

/* ---- Logout ---- */
Route::post('/logout', function (Request $r) {
    Auth::logout();
    $r->session()->invalidate();
    $r->session()->regenerateToken();
    return redirect('/'); // トップへ
})->name('logout');

/* 8) 最後にフォールバック：未定義URLはホームへ */
Route::fallback(fn () => redirect()->route('home'));

/* 9) 一時デバッグ（確認したら削除OK）*/
Route::get('/_debug/routes', function () {
    $rows = collect(\Illuminate\Support\Facades\Route::getRoutes())
        ->map(fn($r) => str_pad(($r->getName() ?: '(no-name)'), 22) . ' | ' . $r->uri())
        ->implode("\n");
    return response('<pre>'.e($rows).'</pre>', 200)
        ->header('Content-Type', 'text/html; charset=utf-8');
});