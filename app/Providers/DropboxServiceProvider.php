<?php

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Illuminate\Support\ServiceProvider;
use Spatie\FlysystemDropbox\DropboxAdapter;
use GuzzleHttp\Client;
use Spatie\Dropbox\RefreshableTokenProvider as AutoRefreshingDropBoxTokenService;
class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
//         $guzzle = new Client();

       $refreshToken='Z2HMyEFQ8gIAAAAAAAAAAUeQpTV9V1qCXio5-ZECfD2dvL-Lxs8VIpYe-jaUq6yQ';

      //$token=$this->getRefreshToken($refreshToken);
     // dd($token);
//dd(\Carbon::now()->addSeconds(($token['expires_in'] - 100))->format('g:i A'));
// $res =  $guzzle->request("POST", "https://api.dropbox.com/oauth2/token", [
//     'form_params' => [
//         'grant_type' => 'authorization_code',
//         'code' => 'gfDtvrBpWfAAAAAAAAAArAaZSi9astdkKC8Ob7vOIzU',
        
//         'client_id' => '14ztoiwyiru0abo',
//         'client_secret' => 'lfpf97rm6f0nd2w',
//     ]
// ]);

//         dd(json_decode($res->getBody(), true));
        Storage::extend('dropbox', function ($app, $config) {
            $client = new DropboxClient(
                $config['authorizationToken']
            );
           
            return new Filesystem(new DropboxAdapter($client));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    private function getRefreshToken($refreshToken)
{
    try {
        $client = new \GuzzleHttp\Client();
        $res = $client->request("POST", "https://api.dropbox.com/oauth2/token", [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => '14ztoiwyiru0abo',
                'client_secret' => 'lfpf97rm6f0nd2w',
            ]
        ]);
        if ($res->getStatusCode() == 200) {
            return json_decode($res->getBody(), TRUE);
        } else {
            return false;
        }
    }
    catch (Exception $e) {
        $this->logger->error("[{$e->getCode()}] {$e->getMessage()}");
        return false;
    }
}

}
// array:7 [▼
//   "access_token" => "sl.Baxu3ozGfQ6ANcAeKiwcoyGXPEwaLzQ9suTRGtKVgA4LNzLAbsMfcl4I9yP6RBGfVpZOLnv9aA-jZsIwiSOOUhbmV45LF1k0cgXLjhkLiST5QDj_G-9pRoqr_By4noTEFWur9vEj6I4"
//   "token_type" => "bearer"
//   "expires_in" => 14399
//   "refresh_token" => "Z2HMyEFQ8gIAAAAAAAAAAUeQpTV9V1qCXio5-ZECfD2dvL-Lxs8VIpYe-jaUq6yQ"
//   "scope" => "account_info.read account_info.write file_requests.read file_requests.write files.content.read files.content.write files.metadata.read files.metadata.write"
//   "uid" => "1045515808"
//   "account_id" => "dbid:AADZGj3EYJKKzDyeZBr_eUmM52EsxtRDSsg"
// ]

//curl https://api.dropbox.com/oauth2/token -d grant_type=refresh_token -d refresh_token=Z2HMyEFQ8gIAAAAAAAAAAUeQpTV9V1qCXio5-ZECfD2dvL-Lxs8VIpYe-jaUq6yQ -u 14ztoiwyiru0abo:lfpf97rm6f0nd2w
//https://www.codemzy.com/blog/dropbox-long-lived-access-refresh-token