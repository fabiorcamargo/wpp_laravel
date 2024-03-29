<?php

namespace App\Http\Controllers;

use App\Http\Requests\WppRules;
use App\Jobs\WppInstanceCreate;
use App\Jobs\WppInstanceImgSend;
use App\Jobs\WppInstanceListSend;
use App\Jobs\WppInstanceMessageSend;
use App\Jobs\WppInstanceStartSession;
use App\Jobs\WppInstanceStatus;
use App\Models\WppConnect;
use App\Models\WppGroup;
use App\Models\WppMessage;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WppConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = auth()->user()->getWpp()->get();

        return view('wpp.index', ['datas' => $datas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WppRules $request)
    {

        $request['session'] = (string) Str::orderedUuid();
        $request['status'] = 'CRIANDO';
        $wpp = auth()->user()->getWpp()->create($request->all());


        $request->session()->flash('flash.banner', 'Instância enviada para criação');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($wppConnect)
    {

        return view('wpp.show', ['wpp' => WppConnect::find($wppConnect)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WppConnect $wppConnect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WppConnect $wppConnect)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        WppConnect::find($id)->delete();

        return back()->banner('Instância excluída com sucesso');
    }



    public function QrCode($id)
    {
        $wpp = WppConnect::find($id);

        $url = env('URL_API') . '/instance/connect' . $wpp->session . '?number=55' . $wpp->phone;
        try {
            $client = new Client();

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'apikey' => $wpp->token
                ],
            ]);

            // Verifique se a resposta é bem-sucedida (código 200)
            if ($response->getStatusCode() === 200) {
                $image = $response->getBody(); // Obtém o stream da resposta

                // Salvar o stream em um arquivo temporário
                Storage::put('qr.png', $image['base64']);

                // Caminho para o arquivo salvo
                $imagePath = Storage::path('qr.png');

                // Retornar a imagem como resposta
                return response()->file($imagePath);
            } else {
                // Lidar com erros de resposta, se necessário
                return response()->json(['error' => 'Erro ao obter o QR code'], $response->getStatusCode());
            }
        } catch (RequestException $e) {
            // Lidar com exceções, se ocorrerem
            return response()->json(['error' => 'Erro na requisição: ' . $e->getMessage()], 500);
        }
    }

    public function StartSession($id)
    {

        $wpp = WppConnect::find($id);

        $url = env('URL_API') . '/instance/connect/' . $wpp->session . '?number=' . $wpp->phone;

        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'apikey' => $wpp->token
            ])->get($url);

            //dd(json_decode($response->body()));

            if ($response->getStatusCode() === 200) {
                $image = json_decode($response->body()); // Obtém o stream da resposta

                
                $image = $image->base64;

                //dd($image);

                // Salvar o stream em um arquivo temporário
                // Storage::put('qr.png', $image['base64']);

                // Caminho para o arquivo salvo
                // $imagePath = Storage::path('qr.png');

                // Retornar a imagem como resposta
                return $image;
            } else {
                // Lidar com erros de resposta, se necessário
                return response()->json(['error' => 'Erro ao obter o QR code'], $response->getStatusCode());
            }

           
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

    public function StatusSession($id)
    {

        $wpp = WppConnect::find($id);

        $url = env('URL_API') . '/instance/connectionState/' . $wpp->session;

        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'apikey' => env('WPP_KEY')
            ])->get($url);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $responseData = $response->json();
                $status = $responseData['instance']['state'];

                $wpp->update([
                    'status' => $status
                ]);
                return $status;
            } else {
                // Lidar com erros de resposta HTTP
                echo 'Erro na solicitação: ' . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

    public function SendMessage($session, $phone, $msg, $group)
    {

        $wpp = WppConnect::where('session', $session)->first();
        if ($group == false) {
            $phone = strlen($phone) > 11 ? "55" . $phone : $phone;
        }

        $data = [
            'phone' => $phone,
            'type' => 'chat',
            'body' => $msg,
            'group' => $group
        ];

        $mensagem = $wpp->Messages()->create($data);

        dispatch(new WppInstanceMessageSend($mensagem));
    }

    public function SendImg($session, $phone, $msg, $img, $group)
    {

        $wpp = WppConnect::where('session', $session)->first();
        if ($group == false) {
            $phone = strlen($phone) > 11 ? "55" . $phone : $phone;
        }

        $body = json_encode([
            'msg' => $msg,
            'img' => $img
        ]);

        $data = [
            'phone' => $phone,
            'type' => 'img',
            'body' => $body,
            'group' => $group
        ];

        $mensagem = $wpp->Messages()->create($data);

        dispatch(new WppInstanceImgSend($mensagem));
    }

    public function SendList($session, $phone, $desc, $button, $title, $rows, $group)
    {

        $wpp = WppConnect::where('session', $session)->first();
        if ($group == false) {
            $phone = strlen($phone) > 11 ? "55" . $phone : $phone;
        }

        $body = json_encode([
            'description' => $desc,
            'buttonText' => $button,
            'sections' => [
                'title' => $title,
                'rows' => $rows
            ]
        ]);

        $data = [
            'phone' => $phone,
            'group' => $group,
            'type' => 'list',
            'body' => $body
        ];

        $mensagem = $wpp->Messages()->create($data);

        dispatch(new WppInstanceListSend($mensagem));
    }


    public function SendMessageApi(Request $request)
    {
        //dd($request->all());
        $wpp = WppConnect::where('session', $request->session)->first();

        //dd($wpp);
        if ($wpp->user_id == $request->user()->id) {
            $this->SendMessage($wpp->session, $request->phone, $request->body, $request->group);
            return 'Enviado para fila com sucesso';
        } else {
            return 'Não autorizado';
        }
    }

    public function SendImgApi(Request $request)
    {
        //dd($request->all());
        $wpp = WppConnect::where('session', $request->session)->first();

        //dd($wpp);
        if ($wpp->user_id == $request->user()->id) {
            $this->SendImg($wpp->session, $request->phone, $request->body, $request->img, $request->group);
            return 'Enviado para fila com sucesso';
        } else {
            return 'Não autorizado';
        }
    }

    public function SendListApi(Request $request)
    {
        //dd($request->all());
        $wpp = WppConnect::where('session', $request->session)->first();

        //dd($wpp);

        if ($wpp->user_id == $request->user()->id) {
            $this->SendList($wpp->session, $request->phone, $request->desc, $request->button, $request->title, $request->rows, $request->group);
            return 'Enviado para fila com sucesso';
        } else {
            return 'Não autorizado';
        }
    }


    public function StopInstance($id)
    {

        $wpp = WppConnect::find($id);

        $url = 'https://api.meusestudosead.com.br/api/' . $wpp->session .  '/close-session';

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $wpp->token,
            ])->post($url);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $responseData = $response->json();
                $status = $responseData['status'];

                $wpp->update([
                    'status' => $status
                ]);
                return $status;
            } else {
                // Lidar com erros de resposta HTTP
                echo 'Erro na solicitação: ' . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

    public function get_groups($id)
    {

        $wpp = WppConnect::find($id);

        $url = env('URL_API') . '/group/fetchAllGroups/' . $wpp->session . '?getParticipants=false';

        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'apikey' => env('WPP_KEY')
            ])->get($url);

            //dd(json_decode($response));
            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $responseData = json_decode($response);
                //$status = $responseData['status'];

                //dd( ($responseData['response']));

                $this->up_groups($responseData, $wpp);
                //return $status;
            } else {
                // Lidar com erros de resposta HTTP
                echo 'Erro na solicitação: ' . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

    public function up_groups($data, $wpp)
    {
        //dd($data);
        //dd($wpp);

        foreach ($data as $key => $group) {


            if (isset($group->creation)) {
                $create = strlen($group->creation) > 10 ? date("Y-m-d H:i:s", $group->creation / 1000) : date("Y-m-d H:i:s", $group->creation);
            } else {
                $create = '';
            }


            if ($group->id) {
                //dd($wpp->Groups()->where('group_id', $group['contact']['id']['user'])->exists());
                if (!$wpp->Groups()->where('group_id', $group->id)->exists())
                    $wpp->Groups()->create([
                        'group_id' => $group->id,
                        'name' => isset($group->subject) ? $group->subject : 'Sem Nome',
                        'creation' =>  $create
                    ]);
            }
        }
    }
}
