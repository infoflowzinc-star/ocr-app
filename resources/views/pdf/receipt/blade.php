<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>領収書</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; }
        .box { border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>領収書</h1>
        <p>{{ $usage->yyyymm }}分のご利用分</p>
    </div>

    <div class="section">
        <p><span class="label">事務所名：</span>{{ $usage->office->name }}</p>
        <p><span class="label">仕訳数：</span>{{ $usage->entry_count }} 件</p>
        <p><span class="label">金額：</span>¥{{ number_format($usage->entry_count * 100) }}</p>
    </div>

    <div class="section box">
        <p>上記の内容にて領収いたしました。</p>
        <p>発行日：{{ \Carbon\Carbon::now()->format('Y年m月d日') }}</p>
    </div>

    <div class="section" style="text-align: right;">
        <p>株式会社フロウズ</p>
    </div>
</body>
</html>
