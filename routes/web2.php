<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminReviewController;

/*
|--------------------------------------------------------------------------
| Public (最小・安全)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // ここで用意がある順に探します（好きな順に並び替えてOK）
    foreach (['home', 'welcome', 'top', 'front.index'] as $view) {
        if (view()->exists($view)) {
            return view($view);
        }
    }
    return response('TOP: 一時トップページ（後で本テンプレに差し替えます）', 200);
})->name('top');


Route::get('/events', function () {
    if (view()->exists('events.index')) {
        return view('events.index', ['events' => []]); // 未定義変数回避
    }
    return response('Events: 一時ページ（後で本実装に戻します）', 200);
})->name('events.index');

Route::get('/events/{event}', function (int $event) {
    if (view()->exists('events.show')) {
        return view('events.show', ['eventId' => $event]);
    }
    return response("Event #{$event}: 一時ページ", 200);
})->whereNumber('event')->name('events.show');

/*
|--------------------------------------------------------------------------
| Auth routes (Breeze/Fortify想定)
|--------------------------------------------------------------------------
| Laravel 11 なら routes/auth.php を取り込むのが正道。
| 無ければ何もしないので安全。
*/
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}

/*
|--------------------------------------------------------------------------
| Admin routes (将来自動復活)
|--------------------------------------------------------------------------
| コントローラが存在する時だけ有効化。
| 今は無ければ /admin 配下は 404 に退避。将来クラスを置けば自動で復活。
*/
if (class_exists(AdminEventController::class) && class_exists(AdminReviewController::class)) {
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['auth', 'can:admin'])
        ->group(function () {
            Route::get('/', fn () => redirect()->route('admin.events.index'))->name('home');

            Route::get('events', [AdminEventController::class, 'index'])->name('events.index');

            // 必要になったらここに他のadminルート（publish/reject等）を戻す
            // Route::post('events/{event}/publish', [AdminEventController::class, 'publish'])->whereNumber('event')->name('events.publish');
            // Route::post('events/{event}/reject',  [AdminEventController::class, 'reject'])->whereNumber('event')->name('events.reject');
            // Route::get('events/{event}/reviews/create', [AdminReviewController::class, 'create'])->whereNumber('event')->name('reviews.create');
            // Route::post('events/{event}/reviews',       [AdminReviewController::class, 'store'])->whereNumber('event')->name('reviews.store');
        });
} else {
    Route::any('/admin/{any?}', fn () => abort(404))->where('any', '.*');
}