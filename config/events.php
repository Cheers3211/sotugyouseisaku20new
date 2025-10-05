<?php

return [
    // カンマ区切りの環境変数を配列へ
    'admin_emails' => array_values(array_filter(array_map('trim',
        explode(',', env('EVENT_ADMIN_EMAILS', ''))
    ))),
];
