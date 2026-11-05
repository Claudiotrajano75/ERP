<?php
// Gera o PDF do pedido para preview visual
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = isset($argv[1]) ? (int)$argv[1] : 57;
$user = \App\Models\User::first();
\Auth::loginUsingId($user->id);

$item = \App\Models\Nfe::find($id);
$config = \App\Models\Empresa::find($item->empresa_id);

$html = view('nfe.imprimir', compact('config', 'item'))->render();

$domPdf = new Dompdf\Dompdf(["enable_remote" => true]);
$domPdf->loadHtml($html);
$domPdf->setPaper("A4");
$domPdf->render();

$arquivo = public_path('preview_pedido_' . $id . '.pdf');
file_put_contents($arquivo, $domPdf->output());
echo "PDF gerado: " . $arquivo . " (" . filesize($arquivo) . " bytes)\n";
echo "URL: http://127.0.0.1:8000/preview_pedido_" . $id . ".pdf\n";
