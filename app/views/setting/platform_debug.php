<?php
$debug = (array)($data['debug'] ?? []);
$summary = (array)($debug['summary'] ?? []);
$php = (array)($debug['php'] ?? []);
$syntax = (array)($debug['syntax'] ?? []);
$files = (array)($debug['files'] ?? []);
$dbs = (array)($debug['dbs'] ?? []);
$consistency = (array)($debug['consistency'] ?? []);
$disks = (array)($debug['disks'] ?? []);
$services = (array)($debug['services'] ?? []);
$versions = (array)($debug['versions'] ?? []);
$logs = (array)($debug['logs'] ?? []);
$functions = (array)($debug['functions'] ?? []);
$issues = (array)($debug['issues'] ?? []);
$warnings = (array)($debug['warnings'] ?? []);

function td_h($v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function td_status(string $s): string {
    if ($s === 'ok') return 'OK';
    if ($s === 'ng') return 'NG';
    if ($s === 'available') return 'AVAILABLE';
    return 'WARNING';
}
function td_cls(string $s): string {
    if ($s === 'ok' || $s === 'available') return 'ok';
    if ($s === 'ng') return 'ng';
    return 'warn';
}
function td_bool($v): string { return $v === true ? 'YES' : ($v === false ? 'NO' : 'N/A'); }
function td_bytes($v): string {
    if ($v === null || $v === '') return 'N/A';
    $v=(float)$v;
    if($v<1024)return number_format($v,0).' B';
    if($v<1048576)return number_format($v/1024,1).' KB';
    if($v<1073741824)return number_format($v/1048576,1).' MB';
    return number_format($v/1073741824,2).' GB';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TCC iDAS Platform Debug</title>
<style>
body{margin:0;padding:18px;background:#eef1f4;color:#202938;font-family:Arial,sans-serif}.wrap{max-width:1550px;margin:auto}
.toolbar{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:15px}.toolbar h1{margin:0 auto 0 0;font-size:24px}
.btn{height:38px;padding:0 14px;border:1px solid #aeb6c2;border-radius:6px;background:#fff;cursor:pointer}
.card{background:#fff;border-radius:8px;box-shadow:0 2px 7px rgba(0,0,0,.08);overflow:hidden;margin-bottom:14px}
.title{font-weight:800;padding:11px 13px;background:#f8f9fb;border-bottom:1px solid #e3e7ec}
.summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;padding:12px}
.sum{display:flex;justify-content:space-between;gap:12px;padding:10px 12px;border:1px solid #e3e7ec;border-radius:7px;background:#fafbfc;font-weight:700}
.ok{color:#24752a}.ng{color:#c62828}.warn{color:#9a6700}.code{font-family:Consolas,"Courier New",monospace;font-size:13px}
table{width:100%;border-collapse:collapse;table-layout:fixed}th,td{padding:8px 11px;border-bottom:1px solid #edf0f3;text-align:left;vertical-align:top;overflow-wrap:anywhere}th{background:#fafbfc;width:27%}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.full{grid-column:1/-1}.list{margin:0;padding:10px 30px}.muted{color:#667085}
@media(max-width:900px){.grid{grid-template-columns:1fr}.full{grid-column:auto}.summary{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.summary{grid-template-columns:1fr}}
</style>
</head>
<body><div class="wrap">
<div class="toolbar">
<h1>TCC iDAS Platform Debug</h1>
<button class="btn" onclick="location.reload()">Refresh</button>
<button class="btn" onclick="location.href='?url=Settings/platform_debug_report'">Download Debug Report</button>
<button class="btn" onclick="window.open('?syntax_check=1','_blank')">Emergency PHP Syntax Check</button>
<button class="btn" onclick="location.href='?url=Settings/index'">Back</button>
</div>

<div class="card">
<div class="title">Debug Capability Summary</div>
<div class="summary">
<?php foreach($summary as $s): ?>
<div class="sum"><span><?php echo td_h($s['label'] ?? ''); ?></span><span class="<?php echo td_cls((string)($s['status'] ?? 'warning')); ?>"><?php echo td_h(td_status((string)($s['status'] ?? 'warning'))); ?></span></div>
<?php endforeach; ?>
</div>
</div>

<div class="grid">
<div class="card">
<div class="title">PHP Runtime</div><table>
<?php foreach($php as $k=>$v): if($k==='extensions')continue; ?>
<tr><th><?php echo td_h($k); ?></th><td class="code"><?php echo td_h(is_array($v)?json_encode($v):$v); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card">
<div class="title">PHP Extensions</div><table>
<?php foreach((array)($php['extensions'] ?? []) as $k=>$v): ?>
<tr><th><?php echo td_h($k); ?></th><td class="<?php echo $v?'ok':'ng'; ?>"><?php echo td_h(td_bool($v)); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card full">
<div class="title">PHP Syntax Check</div><table>
<tr><th>PHP CLI</th><td class="code"><?php echo td_h($syntax['binary'] ?? ''); ?></td></tr>
<tr><th>Checked</th><td><?php echo td_h($syntax['checked'] ?? 0); ?></td></tr>
<tr><th>Syntax OK</th><td class="ok"><?php echo td_h($syntax['ok'] ?? 0); ?></td></tr>
<tr><th>Syntax NG</th><td class="<?php echo !empty($syntax['ng'])?'ng':'ok'; ?>"><?php echo td_h($syntax['ng'] ?? 0); ?></td></tr>
<?php foreach((array)($syntax['failed'] ?? []) as $f): ?>
<tr><th class="code"><?php echo td_h($f['file'] ?? ''); ?> : <?php echo td_h($f['line'] ?? ''); ?></th><td class="ng code"><?php echo td_h($f['message'] ?? ''); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card">
<div class="title">Function / Component Check</div><table>
<?php foreach($functions as $f): ?>
<tr><th><?php echo td_h($f['name'] ?? ''); ?></th><td class="<?php echo !empty($f['ok'])?'ok':'ng'; ?>"><?php echo !empty($f['ok'])?'OK':'NG'; ?></td><td><?php echo td_h($f['detail'] ?? ''); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card">
<div class="title">Version / TCC Information</div><table>
<?php foreach($versions as $k=>$v): ?><tr><th><?php echo td_h($k); ?></th><td class="code"><?php echo td_h($v); ?></td></tr><?php endforeach; ?>
</table></div>

<div class="card full">
<div class="title">System File Integrity</div><table>
<tr><th>File</th><td>Status</td><td>Size</td><td>Permissions</td><td>SHA-256</td></tr>
<?php foreach($files as $f): ?>
<tr><th class="code"><?php echo td_h($f['file'] ?? ''); ?></th><td class="<?php echo !empty($f['ok'])?'ok':'ng'; ?>"><?php echo !empty($f['ok'])?'OK':'NG'; ?></td><td><?php echo td_h(td_bytes($f['size'] ?? null)); ?></td><td class="code"><?php echo td_h($f['permissions'] ?? ''); ?></td><td class="code"><?php echo td_h($f['sha256'] ?? ''); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card full">
<div class="title">Database Integrity / PRAGMA quick_check</div><table>
<tr><th>Database</th><td>File</td><td>Size</td><td>SQLite</td><td>Integrity</td></tr>
<?php foreach($dbs as $db): ?>
<tr><th><?php echo td_h($db['name'] ?? ''); ?></th><td class="code"><?php echo td_h($db['path'] ?? ''); ?></td><td><?php echo td_h(td_bytes($db['size'] ?? null)); ?></td><td class="<?php echo !empty($db['sqlite_ok'])?'ok':'ng'; ?>"><?php echo !empty($db['sqlite_ok'])?'OK':'NG'; ?></td><td class="<?php echo (($db['integrity_ok']??null)===true)?'ok':'ng'; ?>"><?php echo (($db['integrity_ok']??null)===true)?'OK':'NG'; ?> <?php echo td_h(is_array($db['quick_check']??null)?implode(' | ',$db['quick_check']):''); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card full">
<div class="title">idas_data.db ↔ tcccon.db Consistency</div><table>
<tr><th>Field</th><td>idas_data.db</td><td>tcccon.db</td><td>Match</td></tr>
<?php foreach($consistency as $c): ?>
<tr><th class="code"><?php echo td_h($c['field']??''); ?></th><td><?php echo td_h($c['idas_data']??''); ?></td><td><?php echo td_h($c['tcccon']??''); ?></td><td class="<?php echo !empty($c['match'])?'ok':'ng'; ?>"><?php echo !empty($c['match'])?'OK':'NG'; ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card">
<div class="title">Disk / RAMDISK</div><table>
<?php foreach($disks as $d): ?>
<tr><th class="code"><?php echo td_h($d['path']??''); ?></th><td><?php echo td_h(td_bytes($d['free']??null)); ?> free / <?php echo td_h(td_bytes($d['total']??null)); ?><br><span class="<?php echo !empty($d['exists'])?'ok':'ng'; ?>"><?php echo !empty($d['exists'])?'EXISTS':'MISSING'; ?></span> / <?php echo !empty($d['writable'])?'WRITABLE':'READ-ONLY'; ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card">
<div class="title">Service Status</div><table>
<?php foreach($services as $s): ?>
<tr><th><?php echo td_h($s['name']??''); ?></th><td class="<?php echo td_cls((string)($s['status']??'warning')); ?>"><?php echo td_h(td_status((string)($s['status']??'warning'))); ?></td><td class="code"><?php echo td_h($s['detail']??''); ?></td></tr>
<?php endforeach; ?>
</table></div>

<div class="card full">
<div class="title">Recent Logs</div>
<?php foreach($logs as $log): ?>
<div class="title"><?php echo td_h($log['name']??''); ?> — <span class="code"><?php echo td_h($log['path']??''); ?></span></div>
<?php if(!empty($log['lines'])): ?><ul class="list"><?php foreach($log['lines'] as $line): ?><li class="code"><?php echo td_h($line); ?></li><?php endforeach; ?></ul>
<?php else: ?><div style="padding:12px" class="muted">No readable recent log lines.</div><?php endif; ?>
<?php endforeach; ?>
</div>

<div class="card">
<div class="title">Issues</div>
<?php if($issues): ?><ul class="list"><?php foreach($issues as $x): ?><li class="ng"><?php echo td_h($x); ?></li><?php endforeach; ?></ul><?php else: ?><div style="padding:12px" class="ok">No issues detected.</div><?php endif; ?>
</div>

<div class="card">
<div class="title">Warnings</div>
<?php if($warnings): ?><ul class="list"><?php foreach($warnings as $x): ?><li class="warn"><?php echo td_h($x); ?></li><?php endforeach; ?></ul><?php else: ?><div style="padding:12px" class="ok">No warnings detected.</div><?php endif; ?>
</div>
</div>

<div class="card"><div style="padding:12px" class="muted">Read-only diagnostics. This page does not update settings, write databases, reboot the controller, or perform firmware updates.</div></div>
</div></body></html>
