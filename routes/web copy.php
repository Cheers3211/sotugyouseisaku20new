<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizerEventController;
use App\Http\Controllers\OrganizerDashboardController;

// Admin
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminReviewController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

// トップ
Route::get('/', [HomeController::class, 'index'])->name('home');

// イベント（一覧・詳細・外部公式へ）
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/go', [EventController::class, 'go'])->name('events.go');

// 主催者向けLP
Route::view('/for-organizers', 'organizer.landing')->name('organizer.landing');

// 投稿規約・各種静的ページ
Route::view('/policy', 'policy')->name('policy');
Route::view('/terms/posting', 'static.posting_terms')->name('terms.posting');

// 主催者の投稿フロー（公開）
Route::get('/submit-event',  [OrganizerEventController::class, 'create'])->name('submit.create');
Route::post('/submit-event', [OrganizerEventController::class, 'store'])->name('submit.store');
Route::view('/submit-event/thanks', 'organizer.thanks')->name('submit.thanks');

// 投稿メール確認（レート制限付き）
Route::get('/verify-event/{event}/{token}', [OrganizerEventController::class, 'verify'])
    ->name('events.verify')
    ->middleware('throttle:10,1');

// 投稿→マイページ紐づけ（トークンURL・ログイン状態はコントローラ側で判定）
Route::get('/organizer/claim/{event}/{token}', [OrganizerEventController::class, 'claim'])
    ->name('organizer.claim');

/*
|--------------------------------------------------------------------------
| User (web) auth
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // プロフィール（Breeze/Jetstream）
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // 主催者マイページ
    Route::prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/claim/{event}/{token}', [OrganizerDashboardController::class, 'claim'])->name('claim');
    });

    // 一般ログイン後の着地
    Route::get('/dashboard', function () {
        // 管理アカウントなら管理ダッシュボードへ
        if (Auth::check() && method_exists(Auth::user(), 'is_admin') && Auth::user()->is_admin) {
            return redirect()->route('admin.events.index');
        }
        // 主催者は投稿へ
        return redirect()->route('submit.create');
    })->name('dashboard');
});

// Breeze の標準認証ルート
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin auth (guest/admin, auth:admin)
|--------------------------------------------------------------------------
*/

// 未ログインの管理者
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('login',  [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
});

// ログイン済みの管理者
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

    // ログアウト
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // /admin → 審査ダッシュボード
    Route::get('/', fn () => redirect()->route('admin.events.index'))->name('home');

    // 審査ダッシュボード
    Route::get('events', [AdminEventController::class, 'index'])->name('events.index');

    // 公開/却下（POST）
    Route::post('events/{event}/publish', [AdminEventController::class, 'publish'])
        ->whereNumber('event')->name('events.publish');
    Route::post('events/{event}/reject',  [AdminEventController::class, 'reject'])
        ->whereNumber('event')->name('events.reject');

    // 運営レビュー（作成/保存）
    Route::get('events/{event_id}/reviews/create', [AdminReviewController::class, 'create'])
        ->whereNumber('event_id')->name('reviews.create');
    Route::post('events/{event_id}/reviews',        [AdminReviewController::class, 'store'])
        ->whereNumber('event_id')->name('reviews.store');

    // （任意）編集系を今後追加したいときの雛形
    // Route::get('events/{event}/reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
    // Route::put('events/{event}/reviews/{review}',       [AdminReviewController::class, 'update'])->name('reviews.update');
});
// routes/web.php の admin(auth:admin) グループ内に一時追加
Route::get('whoami', function () {
  return [
      'admin_guard' => auth('admin')->check(),
      'admin_email' => optional(auth('admin')->user())->email,
  ];
})->name('debug.whoami');

