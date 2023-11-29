<?php

namespace App\Jobs;

use App\Models\WppConnect;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class WppInstanceStartSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $url;
    protected $wpp;

    /**
     * Create a new job instance.
     */
    public function __construct(WppConnect $wpp)
    {
        $this->url = 'https://api.meusestudosead.com.br/api/' . $wpp->session . '/' . env('WPP_KEY') . '/start-session';
        $this->wpp = $wpp;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $client = new Client();
        
            $response = $client->request('GET', $this->url, [
                'headers' => [
                    'Authorization' => 'Bearer $2b$10$72saQYKF.qF7IVjY1SyDIus.k.pGf1kQXqRtltHeMrPsiy0WRLg3u',
                ],
            ]);
        
            // Verifique se a resposta é bem-sucedida (código 200)
            if ($response->getStatusCode() === 200) {
                $image = $response->getBody(); // Obtém o stream da resposta
        
                // Salvar o stream em um arquivo temporário
                $tempImagePath = tempnam(sys_get_temp_dir(), 'qr_');
                Storage::put('qr', $image);
        
                // Retornar a imagem como resposta
                return response()->file($tempImagePath);
        
                // Ou, se preferir, você pode passar a imagem para a view do Laravel
                // return view('nome_da_view', ['qrCodeImagePath' => $tempImagePath]);
            } else {
                // Lidar com erros de resposta, se necessário
                return response()->json(['error' => 'Erro ao obter o QR code'], $response->getStatusCode());
            }
        } catch (Exception $e) {
            // Lidar com exceções, se ocorrerem
            return response()->json(['error' => 'Erro na requisição: ' . $e->getMessage()], 500);
        }
    }
}
