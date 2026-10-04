<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
class PublicSidebarData {
    public function get(): array {
        $api=$this->api('getJournals');
        $journals=$this->rows('journals') ?: ($api['journals']??[]);
        $editors=$this->rows('editors_data') ?: ($api['topEditors']??[]);
        $indexings=$this->rows('indexing') ?: ($api['indexings_unique']??[]);
        $indexings = collect($indexings)->filter(fn ($row) => (string) ($row['isShow'] ?? '') === '1')->sortByDesc('id')->values()->all();
        return compact('journals','editors','indexings')+['topEditors'=>$editors,'globalIndexings'=>$indexings];
    }
    private function api(string $path): array {
        $base=rtrim((string)config('services.public_api.base_url'),'/'); if(!$base)return [];
        try{$json=Http::connectTimeout(5)->timeout(20)->acceptJson()->get($base.'/'.$path)->json()?:[];return isset($json['data'])&&is_array($json['data'])?$json['data']:$json;}catch(\Throwable){return [];}
    }
    private function rows(string $table): array {try{return DB::table($table)->get()->map(fn($r)=>(array)$r)->all();}catch(\Throwable){return [];}}
}
